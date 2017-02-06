<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Comment;
use App\Models\Friends;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Photo;
use App\Models\Posts;
use App\Models\Society;
use App\Models\SocietyMember;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    /**
     * News feed
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $posts = Posts::where('source','=', 'feed')
                        ->Where('user_id','=', Auth::user()->id)
                        ->orWhereIn('user_id', $this->getFriendsIDs())
                        ->orderBy('id', 'DESC')->paginate(5);
        return view('feed.index')->with('posts', $posts);
    }

    public function search(Request $request){
        $s_query = $request->q;
        $query = '%'.strtolower($request->q).'%';
        $users = User::where( 'first_name', 'like', $query )
                        ->orWhere('last_name', 'like', $query)
                        ->orWhere('email', 'like', $query)
                        ->where('type', '=', 'student')->get();

        return view('feed.search')->with('query', $s_query)->with('users', $users);
    }

    /**
     * User friend ids
     * @return mixed
     */
    public function getFriendsIDs(){
        return Friends::where('user','=', Auth::user()->id)->pluck('friend');
    }

    /**
     * Post
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewPost($id){
        $post = Posts::where('id', '=', $id)->first();

        return view('feed.post')->with('post', $post);
    }

    /**
     * Create text post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTextPost(Request $request){
        $post_text = $request->post_text;

        $post = new Posts();
        $post->post_text = $post_text;
        $post->user_id = Auth::user()->id;
        $post->type = 'text';
        $post->source = $request->source;
        $post->society_id = $request->source_id;

        if($post->save()){
            return response()->json([
                'created'   =>  'true',
                'name'      =>  Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'id'        =>  $post->id,
                'image_uri' =>  Auth::user()->image_uri,
                'time_stamp'    =>  $post->created_at->diffForHumans()
            ]);
        }

        return response()->json([
            'created'   =>  'false'
        ]);
    }

    /**
     * Creata file/video post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFilePost(Request $request){
        $file = $request->file('file_upload');
        $ext = $file->getClientOriginalExtension();
        $type = $this->getFileType($ext);
        $post_text = $request->post_text;

        $path = Storage::disk('public')->putFileAs(
            $type, $request->file('file_upload'), str_random(10). '.' . $request->file('file_upload')->getClientOriginalExtension()
        );

        $post = new Posts();
        $post->post_text = $post_text;
        $post->user_id = Auth::user()->id;
        $post->type = $type;
        $post->source = $request->source;
        $post->society_id = $request->source_id;


        if($post->save()){
            if($this->createUploadRecord($post, $path)){
                return response()->json([
                    'created'   =>  'true',
                    'name'      =>  Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'id'        =>  $post->id,
                    'image_uri' =>  Auth::user()->image_uri,
                    'upload_path'  =>  $path,
                    'type'  =>  $type,
                    'user_id'   =>  Auth::user()->id,
                    'time_stamp'    =>  $post->created_at->diffForHumans()
                ]);
            }
        }

        return response()->json([
            'created'   =>  'false'
        ]);
    }

    /**
     * Create file/video post database record
     * @param $post
     * @param $path
     * @return bool|string
     */
    public function createUploadRecord($post, $path){
        if($post->type === 'photo'){
            $photo = new Photo();
            $photo->post_id = $post->id;
            $photo->image_uri = $path;

            return ($photo->save());
        }

        if($post->type === 'video'){
            $video = new Video();
            $video->post_id = $post->id;
            $video->video_uri = $path;

            return ($video->save());
        }
        return '';
    }

    /**
     * File upload type
     * @param $ext
     * @return string
     */
    public function getFileType($ext){
        $photo_ext = ['png', 'jpeg', 'jpg'];
        $video_ext = ['mp4'];

        if(in_array($ext, $photo_ext)){
            return 'photo';
        }

        if(in_array($ext, $video_ext)){
            return 'video';
        }

        return '';
    }

    /**
     * Create post comment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createComment(Request $request){
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->post_id = $request->post_id;
        $comment->user_id = Auth::user()->id;

        if($comment->save()){

            if(!$this->actionUserIsOwner($request->post_id)){
                if($this->createNotification('comment', Auth::user()->id, $this->getContentOwnerId($request->post_id), $request->post_id)){
                    return response()->json([
                        'created'   =>  'true',
                        'id'    =>  $comment->id,
                        'image_uri' =>  Auth::user()->image_uri,
                        'name'      =>  Auth::user()->first_name . ' ' . Auth::user()->last_name,
                        'user_id'   =>  Auth::user()->id
                    ]);
                }
            }

            return response()->json([
                'created'   =>  'true',
                'id'    =>  $comment->id,
                'image_uri' =>  Auth::user()->image_uri,
                'name'      =>  Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'user_id'   =>  Auth::user()->id
            ]);
        }
    }

    public function updateComment(Request $request){
        if(Comment::where('id','=', $request->id)->update([
            'comment'   =>  $request->text
        ])){
            return response()->json([
                'updated'   => true
            ]);
        }
    }

    /**
     * Post like
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likePost(Request $request){
        $post_id = $request->post_id;
        $user_id = Auth::user()->id;

        $like = new Like();
        $like->user_id = $user_id;
        $like->post_id = $post_id;

        if($like->save()){

            if(!$this->actionUserIsOwner($request->post_id)){
                if($this->createNotification('like', Auth::user()->id, $this->getContentOwnerId($request->post_id), $request->post_id)){
                    return response()->json([
                        'like'  =>  'true',
                        'total_likes'   =>  $this->getPostLikeCount($post_id)
                    ]);
                }
            }

            return response()->json([
                'like'  =>  'true',
                'total_likes'   =>  $this->getPostLikeCount($post_id)
            ]);

        }
    }

    /**
     * Remove like
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlikePost(Request $request){
        $post_id = $request->post_id;
        $user_id = Auth::user()->id;

        $like = Like::where('post_id','=', $post_id)->where('user_id', '=', $user_id);

        if($like->delete()){

            if(!$this->actionUserIsOwner($request->post_id)){
                if($this->removeNotification('like', Auth::user()->id, $this->getContentOwnerId($request->post_id), $request->post_id)){
                    return response()->json([
                        'unlike'  =>  'true',
                        'total_likes'   =>  $this->getPostLikeCount($post_id)
                    ]);
                }
            }

            return response()->json([
                'unlike'  =>  'true',
                'total_likes'   =>  $this->getPostLikeCount($post_id)
            ]);

        }
    }

    /**
     * Delete Post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Request $request){
        $post = Posts::where('id','=',$request->id)->first();

        $this->deletePostNotifications($post);
        if($post->type === 'text'){
            if($post->delete()){
                return response()->json([
                    'deleted'   => true
                ]);
            }
        }

        if($post->type === 'photo'){
            if(Storage::disk('public')->delete($post->photo->image_uri)){
                if($post->delete()){
                    return response()->json([
                        'deleted'   => true
                    ]);
                }
            }
        }

        if($post->type === 'video'){
            if(Storage::disk('public')->delete($post->video->video_uri)){
                if($post->delete()){
                    return response()->json([
                        'deleted'   => true
                    ]);
                }
            }
        }
    }

    /**
     * Delete post notifications
     * @param $post
     */
    protected function deletePostNotifications($post){
        Notification::where('type', '=', 'comment')->where('target','=', $post->id)->delete();
        Notification::where('type', '=', 'like')->where('target','=', $post->id)->delete();
    }

    /**
     * Update post text
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editPost(Request $request){
        if(Posts::where('id','=', $request->id)->update([
            'post_text'  =>  $request->text
        ])){
            return response()->json([
                'updated'   => true
            ]);
        }
    }

    /**
     * Delete comment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request){
        $comment = Comment::where('id','=', $request->id)->first();
        $this->deleteCommentNotifications($comment);

        if($comment->delete()){
            return response()->json([
                'deleted'   => true
            ]);
        }
    }

    /**
     * Delete comment notifications
     * @param $comment
     */
    public function deleteCommentNotifications($comment){
        Notification::where('type','=', 'comment')->where('target','=', $comment->id)->delete();
    }

    /**
     * Like count for post
     * @param $post_id
     * @return mixed
     */
    public function getPostLikeCount($post_id){
        return Like::where('post_id', '=', $post_id)->count();
    }

    /**
     * Check if user is owner
     * @param $post_id
     * @return mixed
     */
    public function actionUserIsOwner($post_id){
        return (Posts::where('id', '=' , $post_id)->where('user_id','=', Auth::user()->id)->count());
    }

    /**
     * Return owner id of post
     * @param $post_id
     * @return mixed
     */
    public function getContentOwnerId($post_id){
        return Posts::where('id' , '=', $post_id)->first()->user_id;
    }

    /**
     * Create a new notification
     * @param $type
     * @param $from
     * @param $for
     * @param $target
     * @return bool
     */
    public function createNotification($type, $from, $for, $target){
        $notification = new Notification();
        $notification->type = $type;
        $notification->from = $from;
        $notification->to = $for;
        $notification->target = $target;
        $notification->read = 0;

        return $notification->save();
    }

    /**
     * Remove notification
     * @param $type
     * @param $from
     * @param $for
     * @param $target
     * @return mixed
     */
    public function removeNotification($type, $from, $for, $target){
        $notification = Notification::where('type', '=', $type)->where('from', '=', $from)->where('to', $for)->where('target','=', $target);

        return $notification->delete();
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Comment;
use App\Models\Friends;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Photo;
use App\Models\Posts;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function index(){

        if(Auth::user()->type === 'teacher'){
            $classes = Classes::where('teacher_id', '=', Auth::user()->id)->get();
            return view('teachers.dashboard')->with('classes', $classes)->with('id', NULL);
        }

        $posts = Posts::where('source', '=', 'feed')
                    ->WhereIn('user_id', $this->getFriendsIDs())
                    ->orWhere('user_id', '=', Auth::user()->id)
                    ->orderBy('id', 'DESC')->paginate(5);

        return view('index')->with('posts', $posts)->with('suggestion', $this->getFriendSuggestions());
    }

    public function getFriendsIDs(){
        return Friends::where('user','=', Auth::user()->id)->pluck('friend');
    }

    public function getFriendSuggestions(){
        $friend_ids = Friends::where('user','=', Auth::user()->id)->pluck('friend');
        return User::Where('type','=', 'student')
                    ->Where('verified','=', 1)
                    ->Where('id','!=', Auth::user()->id)
                    ->WhereNotIn('id', $friend_ids)
                    ->inRandomOrder()
                    ->get()
                    ->slice(0,3);
    }

    /**
     * Single post view
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewPost($id){
        $post = Posts::where('id', '=', $id)->first();

        return view('post')->with('post', $post);
    }

    /**
     * Creates a new text post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTextPost(Request $request){
        $post_text = $request->post_text;

        $post = new Posts();
        $post->post_text = $post_text;
        $post->user_id = Auth::user()->id;
        $post->type = 'text';
        $post->source = 'feed';

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
     * Create a new upload post
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
        $post->source = 'feed';


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
     * Createa new photo/video record
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
     * Get uploaded file extension
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
     * Creates a new post comment
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

    public function getPostLikeCount($post_id){
        return Like::where('post_id', '=', $post_id)->count();
    }

    public function actionUserIsOwner($post_id){
        return (Posts::where('id', '=' , $post_id)->where('user_id','=', Auth::user()->id)->count());
    }

    public function getContentOwnerId($post_id){
        return Posts::where('id' , '=', $post_id)->first()->user_id;
    }

    public function createNotification($type, $from, $for, $target){
        $notification = new Notification();
        $notification->type = $type;
        $notification->from = $from;
        $notification->to = $for;
        $notification->target = $target;

        return $notification->save();
    }

    public function removeNotification($type, $from, $for, $target){
        $notification = Notification::where('type', '=', $type)->where('from', '=', $from)->where('to', $for)->where('target','=', $target);

        return $notification->delete();
    }
}

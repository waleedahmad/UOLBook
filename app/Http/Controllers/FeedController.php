<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Comment;
use App\Models\Photo;
use App\Models\Posts;
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

        $posts = Posts::where('source', '=', 'feed')->orderBy('id', 'DESC')->get();

        return view('index')->with('posts', $posts);
    }

    public function viewPost($id){
        $post = Posts::where('id', '=', $id)->first();

        return view('post')->with('post', $post);
    }

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

    public function createComment(Request $request){
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->post_id = $request->post_id;
        $comment->user_id = Auth::user()->id;

        if($comment->save()){
            return response()->json([
                'created'   =>  'true',
                'id'    =>  $comment->id,
                'image_uri' =>  Auth::user()->image_uri,
                'name'      =>  Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'user_id'   =>  Auth::user()->id
            ]);
        }
    }


}

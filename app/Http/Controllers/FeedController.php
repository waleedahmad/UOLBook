<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'image_uri' =>  Auth::user()->image_uri
            ]);
        }

        return response()->json([
            'created'   =>  'false'
        ]);
    }


}

<?php

namespace App\Http\Controllers;

use App\Classes;
use App\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index(){

        if(Auth::user()->type === 'teacher'){
            $classes = Classes::where('teacher_id', '=', Auth::user()->id)->get();
            return view('teachers.dashboard')->with('classes', $classes)->with('id', NULL);
        }

        return view('index');
    }

    public function createPost(Request $request){
        $post_text = $request->post_text;

        $post = new Posts();
        $post->post_text = $post_text;
        $post->user_id = Auth::user()->id;
        $post->type = 'text';

        if($post->save()){
            return response()->json([
                'created'   =>  'true'
            ]);
        }

        return response()->json([
            'created'   =>  'false'
        ]);
    }


}

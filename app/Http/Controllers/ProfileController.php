<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class ProfileController extends Controller
{
    public function getUserProfile($id){
        $user = User::where('id','=',$id)->first();
        $posts = Posts::where('user_id', '=', $id)->orderBy('id','DESC')->get();

        return view('profile')->with('user', $user)->with('posts', $posts);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class ProfileController extends Controller
{
    public function getUserProfile($id){
        $user = User::where('id','=',$id)->first();

        return view('profile')->with('user', $user);
    }
}

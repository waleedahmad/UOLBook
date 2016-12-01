<?php

namespace App\Http\Controllers;

use App\Classes;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index(){

        if(Auth::user()->type === 'teacher'){

            $classes = Classes::where('teacher_id', '=', Auth::user()->id)->get();
            return view('teachers.dashboard')->with('classes', $classes)->with('id', NULL);
        }
    }


}

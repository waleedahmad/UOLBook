<?php

namespace App\Http\Controllers;

use App\Verification;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
    public function getIndex(){
        $requests = Verification::all();
        return view('admin.index')->with('v_requests', $requests);
    }

    public function getUsers(){
        return view('admin.users');
    }

    public function getMessages(){
        return view('admin.messages');
    }

    public function getFilteredRequests($type){
        if($type === 'teachers'){
            $requests = Verification::join('users', 'verification_requests.user_id', '=', 'users.id')->where('users.type','=','teacher')->get();
            return view('admin.index')->with('v_requests', $requests);
        }

        if($type === 'students'){
            $requests = Verification::join('users', 'verification_requests.user_id', '=', 'users.id')->where('users.type','=','student')->get();
            return view('admin.index')->with('v_requests', $requests);
        }
    }
}

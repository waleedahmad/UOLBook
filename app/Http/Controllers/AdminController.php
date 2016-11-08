<?php

namespace App\Http\Controllers;

use App\User;
use App\Verification;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
    public function getIndex(){
        $requests = Verification::join('users', 'verification_requests.user_id', '=', 'users.id')->get();
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
            $requests = Verification::join('users', 'verification_requests.user_id', '=', 'users.id')
                        ->where('users.type','=','teacher')
                        ->get();
            return view('admin.index')->with('v_requests', $requests);
        }

        if($type === 'students'){
            $requests = Verification::join('users', 'verification_requests.user_id', '=', 'users.id')
                            ->where('users.type','=','student')
                            ->get();
            return view('admin.index')->with('v_requests', $requests);
        }
    }

    public function approveUser(Request $request){
        $id = $request->id;

        $user_update = User::where('id','=', $id)->update([
            'verified'  =>  1
        ]);

        if($user_update){
            $v_request = Verification::where('user_id','=',$id);

            if($v_request->delete()){
                return response()->json([
                    'approved'  => true
                ]);
            }
        }

        return response()->json([
            'approved'  =>  false
        ]);
    }
}

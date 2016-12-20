<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;

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

    public function getAllSocieties(){
        return view('admin.societies');
    }

    public function getSocietyRequests(){
        return view('admin.society_requests');
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
        $old_file_path = basename(User::where('id','=',$id)->first()->card_uri);

        $user_update = User::where('id','=', $id)->update([
            'verified'  =>  1,
            'card_uri'  =>  '/id_card/'.$old_file_path
        ]);

        if($user_update){
            $v_request = Verification::where('user_id','=',$id);

            if($v_request->delete()){

                if(Storage::disk('public')->move('requests/'.$old_file_path,  'id_card/'.$old_file_path)){
                    return response()->json([
                        'approved'  => true
                    ]);
                }

            }
        }

        return response()->json([
            'approved'  =>  false
        ]);
    }
}

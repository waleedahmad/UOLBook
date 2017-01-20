<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Society;
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

    /**
     * Show all users
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUsers(){
        $users = User::all();
        return view('admin.users')->with('users', $users);
    }

    /**
     * Show all classes
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllClasses(){
        $classes = Classes::all();
        return view('admin.classes')->with('classes', $classes);
    }

    public function getAllSocieties(){
        $societies = Society::all();
        return view('admin.societies')->with('societies', $societies);;
    }

    /**
     * Get Show all society requests
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSocietyRequests(){
        return view('admin.society_requests');
    }

    /**
     * Show filtered user verification requests
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * Approve user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Disapprove user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disapproveUser(Request $request){
        $id = $request->id;
        $request_file_name = basename(User::where('id','=',$id)->first()->card_uri);

        $user_update = User::where('id','=', $id)->update([
            'card_uri'  =>  ''
        ]);

        if($user_update){
            $v_request = Verification::where('user_id','=',$id);

            if($v_request->delete()){

                if(Storage::disk('public')->delete('requests/'.$request_file_name)){
                    return response()->json([
                        'disapproved'  => true
                    ]);
                }

            }
        }
    }
}

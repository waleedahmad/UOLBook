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
    /**
     * Verification requests view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $requests = Verification::all();
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

    /**
     * Show all societies
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllSocieties(){
        $societies = Society::all();
        return view('admin.societies')->with('societies', $societies);;
    }

    /**
     * Show all society requests
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSocietyRequests(){
        $societies = Society::where('verified', '!=', true)->get();
        return view('admin.society_requests')->with('societies', $societies);
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
        $v_request = Verification::where('id','=',$id)->first();
        $old_file_path = basename($v_request->card_uri);

        $options = [
            'verified'  =>  1,
            'card_uri'  =>  '/id_card/'.$old_file_path
        ];

        if($v_request->type === 'student'){
            $options['registration_id'] = $v_request->registration_no;
        }

        $user_update = User::where('id','=', $v_request->user_id)->update($options);

        if($user_update){
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
        $v_request = Verification::where('id','=',$id)->first();
        $request_file_name = basename($v_request->card_uri);

        if($v_request->delete()){
            if(Storage::disk('public')->delete('requests/'.$request_file_name)){
                return response()->json([
                    'disapproved'  => true
                ]);
            }

        }
    }

    /**
     * Delete class
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteClass(Request $request){
        $id = $request->id;

        $class = Classes::where('id','=', $id);
        $class->first()->purgeUploads();

        if($class->delete()){
            return response()->json([
                'deleted'  => true
            ]);
        }
    }

    /***
     * Delete users
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(Request $request){
        $user_id = $request->user_id;

        $user = User::where('id','=', $user_id)->first();
        $user->purgeUploads();
        if($user->delete()){
            return response()->json([
                'deleted'  => true
            ]);
        }

    }

    /**
     * Approve society
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveSociety(Request $request){
        $society = Society::where('id','=', $request->id)->first();
        $society->verified = true;

        if($society->save()){
            return response()->json([
                'approved'  =>  true
            ]);
        }
    }

    /**
     * Dispparove society
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disApproveSociety(Request $request){
        $society = Society::where('id','=', $request->id)->first();

        if($society->delete()){
            return response()->json([
                'disapproved'  =>  true
            ]);
        }
    }
}

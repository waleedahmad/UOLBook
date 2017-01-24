<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\Society;
use App\Models\SocietyMember;
use App\Models\SocietyRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SocietyController extends Controller
{
    public function showAllSocieties(){
        $societies = Society::where('user_id','=', Auth::user()->id)->get();
        $suggestions = Society::where('user_id','!=', Auth::user()->id)->get();
        return view('societies')
            ->with('societies', $societies)
            ->with('suggestions', $suggestions);
    }

    public function getSocietyForm(){
        return view('societies.create_society');
    }

    public function createSociety(Request $request){

        $rules = [
            'society_name'  =>  'required',
            'society_type'  =>  'required'
        ];

        if($request->hasFile('display_image')) {
            $rules['display_image'] =  'required|file|mimes:jpeg,bmp,png,jpg';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()){
            $society = new Society();
            $society->name = $request->society_name;
            $society->type = $request->society_type;
            $society->verified = false;
            $society->image_uri = $this->storageImageAndReturnImageURI($request);
            $society->user_id = Auth::user()->id;

            if($society->save()){
                return redirect('/societies/all');
            }

        }else{
            return redirect('/societies/create')->withErrors($validator)->withInput();
        }
    }

    private function storageImageAndReturnImageURI($request)
    {
        if($request->hasFile('display_image')){
            $file = $request->file('display_image');
            $ext = $file->extension();
            $path = '/society/'.str_random(10).'.'.$ext;

            if(Storage::disk('public')->put($path,  File::get($file))){
                return $path;
            }
        }else{
            return '/default/img/society_icon.png';
        }
    }

    public function getSociety($id){
        $society = Society::where('id','=', $id)->first();
        $posts = Posts::where('society_id', '=', $id)->paginate(10);
        return view('societies.feed')
            ->with('society', $society)
            ->with('posts', $posts)
            ->with('isVerified', $this->societyIsVerified($id))
            ->with('isMember', $this->isSocietyMember($id))
            ->with('requestPending', $this->societyRequestPending($id));

    }

    public function getSocietySettings($id){
        $society = Society::where('id','=', $id)->first();
        return view('societies.settings')
            ->with('society', $society)
            ->with('isVerified', $this->societyIsVerified($id))
            ->with('isMember', $this->isSocietyMember($id))
            ->with('requestPending', $this->societyRequestPending($id));;
    }

    public function getSocietyRequests($id){
        $society = Society::where('id','=', $id)->first();
        $requests = SocietyRequest::where('society_id','=', $id)->get();
        return view('societies.requests')
            ->with('society', $society)
            ->with('isVerified', $this->societyIsVerified($id))
            ->with('isMember', $this->isSocietyMember($id))
            ->with('requestPending', $this->societyRequestPending($id))
            ->with('requests',$requests);
    }

    public function societyMembers($id){
        $society = Society::where('id','=', $id)->first();
        $members = SocietyMember::where('society_id','=',$id)->get();
        return view('societies.members')
            ->with('society', $society)
            ->with('isVerified', $this->societyIsVerified($id))
            ->with('isMember', $this->isSocietyMember($id))
            ->with('requestPending', $this->societyRequestPending($id))
            ->with('members', $members);
    }

    public function societyIsVerified($id){
        return Society::where('id','=', $id)->first()->verified;
    }

    private function isSocietyMember($id)
    {
        return SocietyMember::where('society_id', '=', $id)->where('user_id','=', Auth::user()->id)->count();
    }

    private function societyRequestPending($id)
    {
        return SocietyRequest::where('user_id','=', AUth::user()->id)->where('society_id','=', $id)->count();
    }

    public function joinRequest(Request $request){

        $society_request =  new SocietyRequest();
        $society_request->user_id = Auth::user()->id;
        $society_request->society_id = $request->id;

        if($society_request->save()){
            return response()->json([
                'created'  =>  true
            ]);
        }
    }

    public function cancelJoinRequests(Request $request){
        $society_request = SocietyRequest::where('user_id', '=', Auth::user()->id)->where('society_id', '=', $request->id);

        if($society_request->delete()){
            return response()->json([
                'canceled'  =>  true
            ]);
        }
    }

    public function acceptJoinRequests(Request $request){
        $soc_request = SocietyRequest::where('id','=', $request->id)->first();

        $member = new SocietyMember();
        $member->user_id = $soc_request->user_id;
        $member->society_id = $soc_request->society_id;

        if($member->save() && $soc_request->delete()){
            return response()->json([
                'accepted'  =>  true
            ]);
        }
    }

    public function disApproveJoinRequest(Request $request){
        $soc_request = SocietyRequest::where('id','=', $request->id)->first();

        if($soc_request->delete()){
            return response()->json([
                'disapproved'  =>  true
            ]);
        }
    }

    public function removeUser(Request $request){
        $soc_request = SocietyMember::where('id','=', $request->id)->first();

        if($soc_request->delete()){
            return response()->json([
                'removed'  =>  true
            ]);
        }
    }

}

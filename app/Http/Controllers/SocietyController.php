<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\Society;
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
        return view('societies.feed')->with('society', $society)->with('posts', $posts);
    }

}

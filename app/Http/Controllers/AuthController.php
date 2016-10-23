<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Requests;

class AuthController extends Controller
{
    public function getLogin(){
        return view('auth.login');
    }

    public function getRegister(){
        return view('auth.register');
    }

    public function postLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' =>  'required|email',
            'password'  =>  'required',
        ]);

        if($validator->passes()){
            if($this->authenticateUser($request)){
                return redirect('/');
            }
            $request->session()->flash('message', 'Invalid email or password');
            return redirect('/login');


        }else{
            return redirect('/login')->withErrors($validator)->withInput();
        }
    }

    public function postRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'firstName' =>  'required',
            'lastName'  =>  'required',
            'email' =>  'required|email|unique:users',
            'password'  =>  'required|min:6',
            'gender'    =>  'required',
            'usertype'  =>  'required'
        ]);

        if($validator->passes()){
            if($this->createUser($request)){
                $request->session()->flash('message','Account created successfully');
                return redirect('/login');
            }else{
                $request->session()->flash('message','Unable to register your account');
                return redirect('/register');
            }
        }else{
            return redirect('/register')->withErrors($validator)->withInput();
        }
    }

    public function createUser($request){
        $user = new User();

        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->gender = $request->gender;
        $user->type = $request->usertype;
        $user->image_uri = '/assets/img/default_image.png';

        if($user->save()){
            return true;
        }

        return false;
    }

    private function authenticateUser($request){
        return Auth::attempt([
            'email' =>  $request->email,
            'password'  =>  $request->password
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}

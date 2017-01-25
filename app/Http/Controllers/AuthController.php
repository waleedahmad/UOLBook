<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Http\Requests;

class AuthController extends Controller
{
    /**
     * Login form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin(){
        return view('auth.login');
    }

    /**
     * Registration form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRegister(){
        return view('auth.register');
    }

    /**
     * Login user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' =>  'required|email',
            'password'  =>  'required',
        ]);

        if($validator->passes()){
            if($this->authenticateUser($request)){
                if(Auth::user()->type === 'admin'){
                    return redirect('/admin');
                }
                return redirect('/');
            }
            $request->session()->flash('message', 'Invalid email or password');
            return redirect('/login');


        }else{
            return redirect('/login')->withErrors($validator)->withInput();
        }
    }

    /**
     * Register user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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

    /**
     * Create new user
     * @param $request
     * @return bool
     */
    public function createUser($request){
        $user = new User();

        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->gender = $request->gender;
        $user->type = $request->usertype;
        $user->image_uri = ($request->gender == 'male') ? 'default/img/default_img_male.jpg' : 'default/img/default_img_female.jpg';
        $user->card_uri = '';
        $user->registration_id = '';
        $user->verified = 0;

        if($user->save()){
            return true;
        }

        return false;
    }

    /**
     * Authenticate user
     * @param $request
     * @return mixed
     */
    private function authenticateUser($request){
        return Auth::attempt([
            'email' =>  $request->email,
            'password'  =>  $request->password
        ]);
    }

    /**
     * Logout user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    /**
     * Student verification form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStudentVerification(){
        return view('verify.student');
    }

    /**
     * Teacher verification form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTeacherVerification(){
        return view('verify.teacher');
    }

    /**
     * Student verification
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifyStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_no' => 'required|unique:users,registration_id|min:10',
            'id_card' => 'required|file|mimes:jpeg,bmp,png,jpg'
        ]);

        if ($validator->passes()) {

            if($this->validationRequestExist(Auth::user())){
                $request->session()->flash('message', 'Validation Request Pending');
                return redirect('/verify/student');
            }else{
                $file = $request->file('id_card');
                $ext = $file->extension();
                $path = '/requests/'.str_random(10).'.'.$ext;
                if($this->uploadFile($path, $file)){
                    $verify_request = new Verification();
                    $verify_request->user_id = Auth::user()->id;
                    $verify_request->type = 'user';

                    $user_update = User::where('id','=', Auth::user()->id)->update([
                        'registration_id'   => $request->registration_no,
                        'card_uri'  =>  $path
                    ]);

                    if($verify_request->save() && $user_update){
                        $request->session()->flash('message','Verification Request Submitted');
                        return redirect('/verify/student');
                    }

                }
            }

        }
        return redirect('/verify/student')->withErrors($validator);
    }

    /**
     * Teacher verification
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifyTeacher(Request $request){
        $validator = Validator::make($request->all(), [
            'id_card' => 'required|file|mimes:jpeg,bmp,png,jpg'
        ]);

        if ($validator->passes()) {
            if($this->validationRequestExist(Auth::user())){
                $request->session()->flash('message', 'Validation Request Pending');
                return redirect('/verify/teacher');
            }else{
                $file = $request->file('id_card');
                $ext = $file->extension();
                $path = '/requests/'.str_random(10).'.'.$ext;
                if($this->uploadFile($path, $file)){
                    $verify_request = new Verification();
                    $verify_request->user_id = Auth::user()->id;
                    $verify_request->type = 'user';

                    $user_update = User::where('id','=', Auth::user()->id)->update([
                        'registration_id'   => '',
                        'card_uri'  =>  $path
                    ]);

                    if($verify_request->save() && $user_update){
                        $request->session()->flash('message','Verification Request Submitted');
                        return redirect('/verify/teacher');
                    }

                }
            }
        }

        return redirect('/verify/teacher')->withErrors($validator);
    }

    /**
     * Returns file extension from path
     * @param $file
     * @return string
     */
    public function getFileExtension($file){
        return strtolower(File::extension($file));
    }

    /**
     * Upload file to path
     * @param $path
     * @param $file
     * @return mixed
     */
    public function uploadFile($path, $file){
        return Storage::disk('public')->put($path,  File::get($file));
    }

    /**
     * Check if validation request already exists
     * @param $user
     * @return bool
     */
    private function validationRequestExist($user){
        $user = Verification::where('user_id','=', $user->id);

        if($user->count()){
            return true;
        }
        return false;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

use App\Http\Requests;

class TeacherController extends Controller
{
    public function showAddClassForm(){
        return view('teachers.add_class');
    }

    public function showClass($id){
        $class = Classes::where('id','=',$id);
        if($class->count()){
            $class = $class->first();
        }
        $classes = Classes::where('teacher_id', '=', Auth::user()->id)->get();
        return view('teachers.dashboard')->with('classes', $classes)->with('id', $id)->with('class', $class);
    }

    public function  saveClass(Request $request){
        $validator = Validator::make($request->all(), [
            'subject_code'  =>  'required',
            'subject_name'  =>  'required',
            'subject_semester'  => 'required'
        ]);

        if($validator->passes()){
            $subject_name = $request->subject_name;
            $subject_code = $request->subject_code;
            $subject_semester = $request->subject_semester;

            $class = new Classes();

            $class->subject_name = $subject_name;
            $class->subject_code = $subject_code;
            $class->subject_semester = $subject_semester;
            $class->teacher_id = Auth::user()->id;

            if($class->save()){
                return redirect('/');
            }
        }else{
            return redirect('/teacher/addClass')->withErrors($validator);
        }
    }
}

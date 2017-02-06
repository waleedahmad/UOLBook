<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\User;
use Validator;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    /**
     * User classes and suggestions
     * @return \Illuminate\View\View
     */
    public function showAllClasses(){
        $classes = Classes::whereIn('id', $this->getClassIDs())->get();
        $suggestions = Classes::whereNotIn('id', $this->getClassIDs())->get();
        return view('classes')->with('classes', $classes)->with('suggestions', $suggestions);
    }

    public function showAllTeachers(){
        $teachers = User::whereIn('id', $this->getTeachersIDs())->get();
        $suggestions = User::whereNotIn('id', $this->getTeachersIDs())->where('type','=', 'teacher')->get();
        return view('teachers')->with('teachers', $teachers)->with('suggestions', $suggestions);
    }

    public function getTeacherClasses($id){
        $user = User::where('id','=', $id);

        if($user->count()){
            $teacher = $user->first();
            return view('classes')->with('teacher', $teacher);
        }

        return redirect('/teachers/all');
    }


    /**
     * User class ids
     * @return mixed
     */
    protected function getClassIDs(){
        return Student::where('user_id', '=', Auth::user()->id)->pluck('class_id')->toArray();
    }

    protected function getTeachersIDs(){
        return Classes::WhereIn('id', $this->getClassIDs())->pluck('teacher_id')->toArray();
    }
}

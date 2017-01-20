<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use Validator;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    /**
     * Show user classes and suggestions
     * @return \Illuminate\View\View
     */
    public function showAllClasses(){
        $classes = Classes::whereIn('id', $this->userClassIDs())->get();
        $suggestions = Classes::whereNotIn('id', $this->userClassIDs())->get();
        return view('classes')->with('classes', $classes)->with('suggestions', $suggestions);
    }


    /**
     * Get user classes IDs
     * @return mixed
     */
    protected function userClassIDs(){
        return Student::where('user_id', '=', Auth::user()->id)->pluck('class_id')->toArray();
    }
}

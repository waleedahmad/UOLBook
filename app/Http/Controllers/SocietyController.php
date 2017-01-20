<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SocietyController extends Controller
{
    public function showAllSocieties(){

        return view('societies');
    }
}

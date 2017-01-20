<?php

namespace App\Http\Middleware;

use App\Models\Classes;
use Closure;
use Illuminate\Support\Facades\Auth;

class ClassMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->type === 'teacher'){
            if($this->teacherIsClassOwner($request->route('id'))){
                return $next($request);
            }
            dd("Failed");
            return redirect('/');
        }

        if(Auth::user()->type === 'student'){
            return $next($request);
        }

    }

    protected function teacherIsClassOwner($id){
        $classes = Classes::where('id','=', $id)->where('teacher_id', '=', Auth::user()->id);
        if($classes->count()){
            return true;
        }
        return false;
    }
}

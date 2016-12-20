<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserIsNotVerified
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
        if(!Auth::user()->verified){
            if(Auth::user()->type === 'teacher'){
                return redirect('/verify/teacher');
            }else if(Auth::user()->type === 'student'){
                return redirect('/verify/student');
            }
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserIsVerified
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

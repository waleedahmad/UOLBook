<?php

namespace App\Http\Middleware;

use App\Models\Friends;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class SidebarMiddleware
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
        view()->composer('feed.right_sidebar', function($view){
            $view->with('suggestions', $this->getFriendSuggestions());
        });
        return $next($request);
    }

    public function getFriendSuggestions(){
        $friend_ids = Friends::where('user','=', Auth::user()->id)->pluck('friend');
        return User::Where('type','=', 'student')
            ->Where('verified','=', 1)
            ->Where('id','!=', Auth::user()->id)
            ->WhereNotIn('id', $friend_ids)
            ->inRandomOrder()
            ->get()
            ->slice(0,3);
    }
}

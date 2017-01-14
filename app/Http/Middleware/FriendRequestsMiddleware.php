<?php

namespace App\Http\Middleware;

use App\Models\FriendRequests;
use App\Models\Notification;
use Closure;
use Illuminate\Support\Facades\Auth;

class FriendRequestsMiddleware
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
        $friend_requests = FriendRequests::where('to','=', Auth::user()->id)->orderBy('created_at','DESC')->get();
        $notifications = Notification::where('to','=', Auth::user()->id)->where('read','=', 0)->orderBy('created_at','DESC')->get();

        view()->composer('navbar', function($view) use ($friend_requests, $notifications){
            $view->with('friend_requests', $friend_requests);
            $view->with('notifications', $notifications);
        });
        return $next($request);
    }
}

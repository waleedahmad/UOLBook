<?php

namespace App\Http\Middleware;

use App\Models\Conversation;
use App\Models\FriendRequests;
use App\Models\Notification;
use Closure;
use Illuminate\Support\Facades\Auth;

class NavbarMiddleware
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
        $conversations = Conversation::where('from','=', Auth::user()->id)->orderBy('updated_at','DESC')->orderBy('read',false)->get();
        $con_count = Conversation::where('from','=', Auth::user()->id)->where('read','=', false)->count();

        view()->composer('navbar', function($view) use ($friend_requests, $notifications, $conversations, $con_count){
            $view->with('friend_requests', $friend_requests);
            $view->with('notifications', $notifications);
            $view->with('conversations', $conversations);
            $view->with('con_count', $con_count);
        });
        return $next($request);
    }
}

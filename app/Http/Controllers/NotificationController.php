<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;

class NotificationController extends Controller
{

    /**
     * Read notification
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function readNotification(Request $request){
        $notification = Notification::where('id','=', $request->id)->first();

        if($notification->type === 'comment' || $notification->type === 'like'){
            $notification->read = 1;
            if($notification->save()){
                return response()->json([
                    'read'  =>  true,
                    'redirect'  =>  '/post/'.$notification->target
                ]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\FriendRequests;
use App\Models\Friends;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function getUserProfile($id){
        $user = User::where('id','=',$id)->first();
        $posts = Posts::where('user_id', '=', $id)->orderBy('id','DESC')->paginate(5);

        $show_profile = $this->profileViewerIsYourFriend($id) || Auth::user()->id === intval($id);
        $request_pending = ($show_profile) ? false : $this->friendRequestPending($id);
        $friends = $this->getAllFriends();

        return view('profile')
                ->with('user', $user)
                ->with('posts', $posts)
                ->with('show_profile', $show_profile)
                ->with('request_pending', $request_pending)
                ->with('friends', $friends);
    }

    public function getFriends($id){
        $user = User::where('id','=',$id)->first();
        $show_profile = $this->profileViewerIsYourFriend($id) || Auth::user()->id === intval($id);
        $request_pending = ($show_profile) ? false : $this->friendRequestPending($id);
        $friends = $this->getAllFriends();

        return view('profile_friends')
            ->with('user', $user)
            ->with('show_profile', $show_profile)
            ->with('request_pending', $request_pending)
            ->with('friends', $friends);
    }

    public function profileViewerIsYourFriend($id){
        return Friends::where('user','=', $id)->where('friend','=', Auth::user()->id)->count();
    }

    public function friendRequestPending($id){
        return FriendRequests::where('from','=', Auth::user()->id)->where('to', '=', $id)->count();
    }

    public function getAllFriends(){
        return Friends::where('user','=', Auth::user()->id)->get();
    }


    public function addFriendRequest(Request $request){
        $from = Auth::user()->id;
        $for = $request->user_id;

        $friend_request = new FriendRequests();
        $friend_request->from = $from;
        $friend_request->to = $for;

        if($friend_request->save()){
            return response()->json([
                'created'   =>  'true'
            ]);
        }
    }

    public function removeFriendRequest(Request $request){
        $from = Auth::user()->id;
        $for = $request->user_id;

        $friend_request = FriendRequests::where('from', '=', $from)->where('to', '=', $for);

        if($friend_request->delete()){
            return response()->json([
                'removed'   =>  'true'
            ]);
        }
    }

    public function deleteFriendRequest(Request $request){
        $friend_request = FriendRequests::where('id','=', $request->request_id);

        if($friend_request->delete()){
            return response()->json([
                'removed'   =>  'true'
            ]);
        }
    }

    public function acceptFriendRequest(Request $request){
        $friend_request = FriendRequests::where('id','=', $request->request_id)->first();

        if($this->createFriendConnection($friend_request->from, $friend_request->to)){
            if($friend_request->delete()){
                return response()->json([
                    'accepted'   =>  'true'
                ]);
            }
        }
    }

    public function removeFriend(Request $request){
        $connection_1 = Friends::where('user','=',  $request->user_id)->where('friend','=', Auth::user()->id);
        $connection_2 = Friends::where('user','=', Auth::user()->id)->where('friend','=', $request->user_id);

        if($connection_1->delete() && $connection_2->delete()){
            return response()->json([
                'removed'   =>  'true'
            ]);
        }
    }

    public function createFriendConnection($from, $for){

        $connection_1 = new Friends();
        $connection_1->user = $from;
        $connection_1->friend = $for;

        $connection_2 = new Friends();
        $connection_2->user = $for;
        $connection_2->friend = $from;

        if($connection_1->save() && $connection_2->save()){
            return true;
        }

        return false;
    }


}

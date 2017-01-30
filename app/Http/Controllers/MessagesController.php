<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Friends;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function getMessages(){
        $conversations = Conversation::where('from','=', Auth::user()->id)->get();
        return view('messages.messages')->with('conversations', $conversations);;
    }

    public function renderConversation($id){
        $conversations = Conversation::where('from','=', Auth::user()->id)->get();
        $conversation = Conversation::where('id','=', $id)->first();
        return view('messages.conversation')
            ->with('conversation', $conversation)
            ->with('conversations', $conversations);
    }


    public function getUserFriends(){
        $users = User::whereIn('id', $this->getFriendsIDs())->get();

        $response = [];

        foreach($users as $user){
            array_push($response, [
                'label' =>  $user->first_name . ' ' . $user->last_name,
                'value' =>  $user->first_name . ' ' . $user->last_name,
                'icon'  =>  '/storage/'.$user->image_uri,
                'id'    =>  $user->id,
            ]);
        }
        return response()->json($response);
    }

    public function getFriendsIDs(){
        return Friends::where('user','=', Auth::user()->id)->pluck('friend');
    }

    public function getOrCreateConversation(Request $request){
        $user_id = $request->id;

        if($this->conversationExist(Auth::user()->id, $user_id)){
            return response()->json([
                'id'    =>  $this->getConversationID(Auth::user()->id, $user_id)
            ]);
        }else{
            return response()->json([
                'id'    =>  $this->createConversationBridgeAndReturnID(Auth::user()->id, $user_id)
            ]);
        }
    }

    public function conversationExist($from, $to){
        return Conversation::where('from','=', $from)->where('to','=', $to)->count();
    }

    public function getConversationID($from, $to){
        return Conversation::where('from','=', $from)->where('to','=', $to)->first()->id;
    }

    public function createConversationBridgeAndReturnID($from, $to){
        $conversation_1 = new Conversation();
        $conversation_2 = new Conversation();

        $conversation_1->from = $from;
        $conversation_1->to = $to;

        $conversation_2->from = $to;
        $conversation_2->to = $from;

        if($conversation_1->save() && $conversation_2->save()){
            return $conversation_1->id;
        }
    }
}

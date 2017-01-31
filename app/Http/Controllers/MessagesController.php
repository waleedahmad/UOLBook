<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Friends;
use App\Models\Message;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    /**
     * Render messages view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMessages(){
        $conversations = Conversation::where('from','=', Auth::user()->id)->orderBy('updated_at','DESC')->get();
        return view('messages.messages')->with('conversations', $conversations);
    }

    /**
     * Render conversation view
     * @param $id
     * @return \Illuminate\View\View
     */
    public function renderConversation($id){
        $this->markConversation($id, true);
        $conversations = Conversation::where('from','=', Auth::user()->id)->orderBy('updated_at','DESC')->get();
        $conversation = Conversation::where('id','=', $id)->first();
        return view('messages.conversation')
            ->with('conversation', $conversation)
            ->with('conversations', $conversations);
    }

    /**
     * Setup new conversation view
     * @param $id
     * @return \Illuminate\View\View
     */
    public function newConversation($id){
        if($this->conversationExist(Auth::user()->id, $id)){
            return redirect('/message/'.$this->getConversationID(Auth::user()->id, $id));
        }

        if(in_array($id, $this->getFriendsIDs()->toArray())){
            $user = User::where('id','=', $id)->first();
            $conversations = Conversation::where('from','=', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            return view('messages.new_conversation')
                ->with('conversations', $conversations)
                ->with('new_user', $user);
        }

        return redirect('/messages/all');
    }

    /**
     * Get user friends for auto complete
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Get user connections
     * @return mixed
     */
    public function getFriendsIDs(){
        return Friends::where('user','=', Auth::user()->id)->pluck('friend');
    }

    /**
     * Check conversation
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkConversation(Request $request){
        $user_id = $request->id;

        if($this->conversationExist(Auth::user()->id, $user_id)){
            return response()->json([
                'exists'    =>  true,
                'id'    =>  $this->getConversationID(Auth::user()->id, $user_id)
            ]);
        }else{
            return response()->json([
                'exists'    =>  false,
            ]);
        }
    }

    /**
     * Check if conversation between two users exist
     * @param $from
     * @param $to
     * @return mixed
     */
    public function conversationExist($from, $to){
        return Conversation::where('from','=', $from)->where('to','=', $to)->count();
    }

    /**
     * Get conversation id between two users
     * @param $from
     * @param $to
     * @return mixed
     */
    public function getConversationID($from, $to){
        return Conversation::where('from','=', $from)->where('to','=', $to)->first()->id;
    }

    /**
     * Create new conversation and return ID
     * @param $from
     * @param $to
     * @return mixed
     */
    public function createConversationBridgeAndReturnID($from, $to, $read){
        $conversation = new Conversation();
        $conversation->from = $from;
        $conversation->to = $to;
        $conversation->read = $read;

        if($conversation->save()){
            return $conversation->id;
        }
    }

    /**
     * Create new chat message
     * @param $con_id
     * @param $from
     * @param $to
     * @param $new_message
     * @return bool
     */
    public function createMessage($con_id, $from, $to, $new_message){
        $message = new Message();
        $message->conversation_id = $con_id;
        $message->from = $from;
        $message->to = $to;
        $message->message = $new_message;

        if($message->save()){
            return true;
        }
    }

    /**
     * Setup conversation between two users
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createConversation(Request $request){
        $user = $request->user_id;
        $message = $request->message;

        $first = ($this->conversationExist(Auth::user()->id,$user)) ? $this->getConversationID(Auth::user()->id,$user) : $this->createConversationBridgeAndReturnID(Auth::user()->id,$user, true);
        $second = ($this->conversationExist($user, Auth::user()->id)) ? $this->getConversationID($user, Auth::user()->id) : $this->createConversationBridgeAndReturnID($user, Auth::user()->id, false);


        $new_message = $this->createMessage($first, Auth::user()->id, $user, $message);
        $new_message2 = $this->createMessage($second, Auth::user()->id, $user, $message);


        if($new_message && $new_message2){
            return response()->json([
                'created'    =>  true,
                'id'    => $first
            ]);
        }
    }

    /**
     * Create new chat message
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newMessage(Request $request){
        $message = $request->message;
        $con_id = $request->con_id;
        $user = $request->user_id;

        $con2_id = ($this->conversationExist($user, Auth::user()->id)) ? $this->getConversationID($user, Auth::user()->id) : $this->createConversationBridgeAndReturnID($user, Auth::user()->id, false);

        $new_message = $this->createMessage($con_id, Auth::user()->id, $user, $message);
        $new_message2 =  $this->createMessage($con2_id, Auth::user()->id, $user, $message);

        $this->touchConversationTimStamp($con_id);
        $this->touchConversationTimStamp($con2_id);


        if($new_message && $new_message2 & $this->markConversation($con2_id, false)){
            return response()->json([
                'created'    =>  true,
            ]);
        }
    }

    /**
     * Mark conversation as read or unread upon action
     * @param $id
     * @param $read
     * @return mixed
     */
    public function markConversation($id, $read){
        $conversation = Conversation::where('id', '=', $id)->first();
        if(!$conversation->read){
            if($read){
                $conversation->read = $read;
            }
        }

        if($conversation->read){
            if(!$read){
                $conversation->read = $read;
            }
        }
        return $conversation->save();
    }

    /**
     * Update conversation timestamp
     * @param $id
     */
    public function touchConversationTimStamp($id){
        $conversation = Conversation::where('id', '=', $id)->first();
        $conversation->touch();
    }

    /**
     * Delete conversation
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteConversation(Request $request){
        $con_id = $request->id;

        $conversation = Conversation::where('id','=', $con_id);

        if($conversation->delete()){
            return response()->json([
                'deleted'    =>  true,
            ]);
        }
    }
}

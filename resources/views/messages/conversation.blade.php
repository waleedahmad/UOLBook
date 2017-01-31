@extends('layout')

@section('title')
    Messages - UOLBook
@endsection

@section('content')

    @include('messages.sidebar')

    <div class="messages col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="messages-content">
            <div class="title">
                <input id="friends" placeholder="Type friends name">

                <div class="current-user">
                    {{$conversation->friend->first_name}} {{$conversation->friend->last_name}}
                </div>
            </div>

            <div class="thread">
                @foreach($conversation->getMessages() as $message)
                    <div class="@if($message->from === Auth::user()->id) your-message @else their-message @endif">
                        {{$message->message}}
                    </div>
                @endforeach
            </div>

            <div class="sender">
                <textarea type="text" id="chat-message" data-type="message" data-conversation-id="{{$conversation->id}}" data-user-id="{{$conversation->friend->id}}" placeholder="Type a message"></textarea>
            </div>
        </div>
    </div>

@endsection
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
                    New message to <b>{{$new_user->first_name}} {{$new_user->last_name}}</b>
                </div>
            </div>

            <div class="thread">
            </div>

            <div class="sender">
                <textarea type="text" id="chat-message" data-type="new_message" data-user-id="{{$new_user->id}}" placeholder="Type a message"></textarea>
            </div>
        </div>
    </div>

@endsection
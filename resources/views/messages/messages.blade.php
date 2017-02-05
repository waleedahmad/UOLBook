@extends('layout')

@section('title')
    Messages - UOLBook
@endsection

@section('content')

    @include('messages.sidebar')

    <div class="messages col-xs-12 col-sm-12 col-md-9 col-lg-9 messages">
        <div class="messages-content">
            <div class="title">
                <input id="friends" placeholder="Type friends name">

                <div class="current-user">

                </div>
            </div>

            <div class="thread">
                <div class="alert alert-info" role="alert">
                    Choose a conversation from sidebar or <a href="#" class="alert-link new-message">Create</a> a new conversation.
                </div>
            </div>


        </div>
    </div>

@endsection
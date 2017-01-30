@extends('layout')

@section('title')
    Messages - UOLBook
@endsection

@section('content')

    @include('messages.sidebar')

    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 messages">
        <div class="messages-content">
            <div class="title">
                <input id="friends" class="form-control">

                <div class="current-user">
                    Waleed Ahmad
                </div>
            </div>

            <div class="thread">
                This is Thread
            </div>

            <div class="sender">
                <input type="text" class="form-control" placeholder="Type a message">
            </div>
        </div>
    </div>

@endsection
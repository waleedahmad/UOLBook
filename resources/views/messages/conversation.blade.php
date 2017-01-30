@extends('layout')

@section('title')
    Messages - UOLBook
@endsection

@section('content')

    @include('messages.sidebar')

    <div class="messages col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="title">
            <input id="friends" class="form-control">

            <div class="current-user">
                Waleed Ahmad
            </div>
        </div>
    </div>

@endsection
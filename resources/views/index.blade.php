@extends('layout')

@section('title')
    UOLBook
@endsection

@section('content')

    <div class="left-sidebar col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="user-box">
            <div class="dp col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <a href="/profile/{{Auth::user()->id}}">
                    <img src="{{Auth::user()->image_uri}}" alt="">
                </a>
            </div>

            <div class="details col-xs-9 col-sm-9 col-md-9 col-lg-10">
                <a class="name" href="/profile/{{Auth::user()->id}}">
                    {{Auth::user()->first_name .' '. Auth::user()->last_name}}
                </a>
            </div>
        </div>
    </div>

    <div class="content col-xs-12 col-sm-12 col-md-7 col-lg-7">
        @include('status')

        @include('posts')
    </div>

    <div class="right-sidebar col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="suggestions">
            <div class="header">
                Friend Suggestions
            </div>

            @foreach($suggestion as $user)
                <div class="users">
                    <div class="img col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <img src="{{$user->image_uri}}" alt="{{$user->name}}">
                    </div>
                    <div class="name col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <a href="/profile/{{$user->id}}">{{$user->first_name }} {{$user->last_name}}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
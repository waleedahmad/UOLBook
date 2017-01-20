@extends('layout')

@section('title')
    {{$user->first_name . ' ' . $user->last_name}} - UolBook
@endsection

@section('content')

    <div class="container-fluid profile-header">
        <div class="container">

            <div class="row profile-header-content">
                <div class="col-xs-2 profile-pic">
                    <img src="/storage/{{$user->image_uri}}" class="img-thumbnail">
                </div>
                <div class="col-xs-10 profile-about">
                    <h2>{{$user->first_name . ' ' . $user->last_name}}</h2>
                    <p><i class="glyphicons glyphicons-riflescope"></i> <a href="#">{{$user->registration_id}}</a></p>
                </div>
            </div>
        </div>

        @if($show_profile)
            @if(Auth::user()->id != $user->id)
                <button class="btn btn-default requests remove-friend " data-user-id="{{$user->id}}">Remove Friend</button>
            @endif
        @else
            @if($request_pending)
                <button class="btn btn-default requests remove-request" data-user-id="{{$user->id}}">Cancel Request</button>
            @else
                <button class="btn btn-default requests add-friend" data-user-id="{{$user->id}}">Add Friend</button>
            @endif
        @endif
    </div>

    @if($show_profile)
        <div class="left-sidebar col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <ul class="list-group">

                <li class="list-group-item">
                    <a href="/profile/{{$user->id}}/friends">
                        Friends
                    </a>
                    <span class="badge">{{$friends->count()}}</span>
                </li>


                <li class="list-group-item">
                    <a href="/user/{{$user->id}}/societies">
                        Societies
                    </a>
                </li>
            </ul>
        </div>
    @endif

    @if($show_profile)
        <div class="content col-xs-12 col-sm-12 col-md-7 col-lg-7">
            @if(Auth::user()->id === $user->id)
                @include('feed.status')
            @endif

            <div @if(Auth::user()->id != $user->id) class="posts-only" @endif>
                @include('feed.posts')
            </div>
        </div>
    @else
        <div class="alert alert-info no-friend-alert" role="alert">{{$user->first_name . ' ' . $user->last_name}} is not in your friends list</div>

    @endif


@endsection
@extends('layout')

@section('title')
    {{$user->first_name . ' ' . $user->last_name}} - UolBook
@endsection

@section('content')

    <div class="container-fluid profile-header">
        <div class="container">
            <div class="row profile-header-content">
                <div class="col-xs-2 profile-pic">
                    <div class="image-holder">
                        <img src="/storage/{{$user->image_uri}}">
                    </div>
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
            <div class="profile-friends">
                @foreach($friends as $friend)
                    <div class="media friend">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object" src="/storage/{{$friend->connection->image_uri}}" alt="{{$friend->connection->first_name}}">
                            </a>
                        </div>
                        <div class="media-body">
                            <a href="/profile/{{$friend->connection->id}}"><h4 class="media-heading">{{$friend->connection->first_name . ' '. $friend->connection->last_name}}</h4></a>
                        </div>
                    </div>
                @endforeach

                @if(!$friends->count())
                        <div class="alert alert-info" role="alert">You currently have no friends</div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-info no-friend-alert" role="alert">{{$friend->connection->first_name . ' ' . $friend->connection->last_name}} is not in your friends list</div>

    @endif


@endsection
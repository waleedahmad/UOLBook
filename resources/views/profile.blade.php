@extends('layout')

@section('title')
    {{$user->first_name . ' ' . $user->last_name}} - UolBook
@endsection

@section('content')

    <div class="container-fluid profile-header">
        <div class="container">

            <div class="row profile-header-content">
                <div class="col-md-3 col-sm-3 col-xs-4 profile-pic"><img src="{{$user->image_uri}}" class="img-thumbnail"></div>
                <div class="col-md-9 col-sm-9 col-xs-8 profile-about">
                    <h2>{{$user->first_name . ' ' . $user->last_name}}</h2>
                    <p><i class="glyphicons glyphicons-riflescope"></i> <a href="#">{{$user->registration_id}}</a></p>
                </div>
            </div>

        </div>
    </div>

    <div class="left-sidebar col-xs-12 col-sm-12 col-md-2 col-lg-2">

    </div>

    <div class="content col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div id="new_status" class="create-post">
            <ul class="navbar-nav col-xs-12" id="post_header" role="navigation">
                <li><a href="#"><span class="glyphicon glyphicon-pencil"></span>Update Status</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-picture"></span>Add Photos/Video</a></li>
            </ul>
            <div class="col-xs-12" id="post_content">
                <img alt="profile picture" class="col-xs-1" src="{{Auth::user()->image_uri}}">
                <div class="textarea_wrap"><textarea class="col-xs-11" id="post-text" placeholder="What's on your mind?"></textarea></div>
            </div>
            <div class="col-xs-12" id="post_footer">
                <ul class="navbar-nav col-xs-7">
                    {{--<li><a href="#"><span class="glyphicon glyphicon-camera"></span></a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span></a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-sunglasses"></span></a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-map-marker"></span></a></li>--}}
                </ul>
                <div class="col-xs-5">
                    <button class="btn btn-primary post-now">Post</button>
                </div>
            </div>
        </div>

        <div class="posts">
            @foreach($posts as $post)
                @if($post->type === 'text')
                    <div class="post">
                        <div class="col-xs-1 img-holder">
                            <img alt="profile picture" class="user-img" src="{{Auth::user()->image_uri}}">
                        </div>

                        <div class="col-xs-11 post-content">
                            <div class="name">
                                <a href="/profile/{{$post->user->id}}">
                                    {{$post->user->first_name . ' ' . $post->user->last_name}}
                                </a>
                            </div>

                            <div class="text">
                                {{$post->post_text}}
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
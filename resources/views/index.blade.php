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
        <div id="new_status" class="create-post">
            <ul class="navbar-nav col-xs-12" id="post_header" role="navigation">
                <li><a href="#" id="status-post"><span class="glyphicon glyphicon-pencil"></span>Update Status</a></li>
                <li><a href="#" id="file-post"><span class="glyphicon glyphicon-picture" ></span>Add Photos/Video</a></li>
            </ul>
            <div class="col-xs-12" id="post_content">
                <img alt="profile picture" class="col-xs-1" src="{{Auth::user()->image_uri}}">
                <div class="textarea_wrap"><textarea class="col-xs-11" id="post-text" placeholder="What's on your mind?"></textarea></div>
            </div>
            <div class="col-xs-12" id="post_footer">
                <ul class="navbar-nav col-xs-7">
                    <li><a href="#"><span class="glyphicon glyphicon-camera"></span></a></li>
                </ul>
                <div class="col-xs-5">
                    <button class="btn btn-primary post-now" data-id-type="text">Post</button>
                </div>
            </div>
        </div>

        <div class="posts">
            @foreach($posts as $post)
                @if($post->type === 'text')
                    <div class="post" data-post-id="{{$post->id}}">
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

    <div class="right-sidebar col-xs-12 col-sm-12 col-md-3 col-lg-3">
        Sidebar
    </div>

@endsection
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
        <div class="posts post">
            <div class="post-wrap">
                @if(Auth::check())
                    @if(Auth::user()->id == $post->user_id)
                        <div class="dropdown post-dropdown">
                            <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                <li><a href="#">Delete post</a></li>
                                <li><a href="#">Edit post</a></li>
                            </ul>
                        </div>
                    @endif
                @endif

                <div class="post" data-post-id="{{$post->id}}">
                    <div class="col-xs-1 img-holder">
                        <img alt="profile picture" class="user-img" src="{{$post->user->image_uri}}">
                    </div>

                    <div class="col-xs-11 post-content">
                        <div class="name">
                            <a href="/profile/{{$post->user->id}}">
                                {{$post->user->first_name . ' ' . $post->user->last_name}}
                            </a>
                        </div>

                        <div class="link">
                            <a href="/post/{{$post->id}}">{{$post->created_at->diffForHumans()}}</a>
                        </div>

                        @if($post->type === 'text')
                            <div class="text">
                                {{$post->post_text}}
                            </div>
                        @elseif($post->type === 'photo')
                            <div class="text">
                                {{$post->post_text}}
                            </div>

                            <div class="photo">
                                <img src="/storage/{{$post->photo->image_uri}}" alt="">
                            </div>
                        @elseif($post->type === 'video')
                            <div class="text">
                                {{$post->post_text}}
                            </div>

                            <div class="video">
                                <video controls>
                                    <source src="/storage/{{$post->video->video_uri}}" type="video/mp4">
                                </video>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-sidebar col-xs-12 col-sm-12 col-md-3 col-lg-3">
        Sidebar
    </div>

@endsection
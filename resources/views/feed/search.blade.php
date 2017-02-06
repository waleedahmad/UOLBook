@extends('layout')

@section('title')
    {{$query}} - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="content col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="search">
            <h1>
                Results for : <b>{{$query}}</b>
            </h1>
            @foreach($users as $user)
                <div class=" media result">
                    <div class="col-xs-2 image">
                        <div class="image-holder">
                            <img class="media-object" src="/storage/{{$user->image_uri}}" alt="{{$user->first_name}}">
                        </div>
                    </div>
                    <div class="col-xs-10">
                        <div class="name">
                            <a href="/profile/{{$user->id}}"><h4 class="media-heading">{{$user->first_name . ' '. $user->last_name}}</h4></a>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(!$users->count())
                <div class="alert alert-info" role="alert">No results found for <b>{{$query}}</b></div>
            @endif
        </div>
    </div>

    @include('feed.right_sidebar')

@endsection
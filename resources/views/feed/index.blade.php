@extends('layout')

@section('title')
    UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="content col-xs-12 col-sm-12 col-md-7 col-lg-7">
        @include('feed.status', ['source'   =>  'feed', 'source_id' =>  0])

        @include('feed.posts')
    </div>

    @include('feed.right_sidebar')

@endsection
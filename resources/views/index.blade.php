@extends('layout')

@section('title')
    UOLBook
@endsection

@section('content')

    <div class="content col-xs-1 col-sm-1 col-md-8 col-lg-8">
        <div class="create-post">
            <textarea class="form-control" id="post-text" rows="3" style="resize: none" placeholder="What's on your mind"></textarea>
            <button class="btn btn-default pull-right post-now">
                Post
            </button>
        </div>
    </div>

    <div class="sidebar col-xs-1 col-sm-1 col-md-4 col-lg-4">
        Sidebar
    </div>

@endsection
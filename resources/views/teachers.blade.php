@extends('layout')

@section('title')
    Teachers - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="all-teachers col-xs-12 col-sm-12 col-md-7 col-lg-7">

        <div class="teachers">

            <h1>
                Find Courses
            </h1>
            Please enter your course secret code provided by your teacher to join their class.

            <div class="secret">
                <div class="col-xs-6">
                    <input type="text" class="form-control" id="course-secret" placeholder="Course Secret">
                </div>
                <div class="col-xs-4">
                    <button class="btn btn-default" id="find-class">Find Class</button>
                </div>
            </div>
        </div>

        <div class="teachers">

            <h1>
                Your Teachers
            </h1>
            @foreach($teachers as $user)
                <div class=" media teacher">
                    <div class="col-xs-1 image">
                        <div class="image-holder">
                            <img class="media-object" src="/storage/{{$user->image_uri}}" alt="{{$user->first_name}}">
                        </div>
                    </div>
                    <div class="col-xs-11">
                        <div class="name">
                            <a href="/teacher/{{$user->id}}/classes"><h4 class="media-heading">{{$user->first_name . ' '. $user->last_name}}</h4></a>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(!$teachers->count())
                <div class="alert alert-info" role="alert">You're currently not a student of any teacher, please view suggestion and request instructor to become student their offered courses.</div>
            @endif
        </div>

        {{--<div class="teachers">
            <h1>
                Teachers at University of Lahore
            </h1>
            @foreach($suggestions as $user)
                <div class="media teacher">
                    <div class="col-xs-1 image">
                        <div class="image-holder">
                            <img class="media-object" src="/storage/{{$user->image_uri}}" alt="{{$user->first_name}}">
                        </div>
                    </div>
                    <div class="col-xs-11">
                        <div class="name">
                            <a href="/teacher/{{$user->id}}/classes"><h4 class="media-heading">{{$user->first_name . ' '. $user->last_name}}</h4></a>
                        </div>
                    </div>
                </div>
            @endforeach

            @if(!$suggestions->count())
                <div class="alert alert-info" role="alert">No teachers found.</div>
            @endif
        </div>--}}
    </div>

    @include('feed.right_sidebar')

@endsection
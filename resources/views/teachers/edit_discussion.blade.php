@extends('layout')

@section('title')


    @if(isset($class))
        {{$class->course->code}} / {{$class->course->name}}  / {{$class->instructor->first_name . ' '. $class->instructor->last_name}}
    @else
        {{Auth::user()->first_name . ' ' . Auth::user()->last_name }} - Teachers Dashboard
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        @if(Auth::user()->type === 'teacher')
            @include('teachers.sidebar')
        @else
            @include('feed.left_sidebar')
        @endif

        <div class="teacher-content col-xs-12 col-sm-9 col-md-9 col-lg-7">

            <div class="dashboard">
                @include('teachers.navbar')

                @if(Auth::user()->type === 'student')
                    @if(!$is_student && isset($class))
                        <div class="alert alert-warning" role="alert">You're not a student of this class. Enroll by clicking below join class button.</div>

                        @if(!$request_pending)
                            <button class="btn btn-default student-join-request center-block" data-id="{{$class->id}}">
                                Join Class
                            </button>
                        @else
                            <button class="btn btn-default student-cancel-request center-block" data-id="{{$class->id}}">
                                Cancel Request
                            </button>
                        @endif
                    @endif
                @endif

                @if(isset($class) && Auth::user()->type === 'teacher' || Auth::user()->type === 'student' && $is_student)
                    {{--Post Discussion--}}
                    <div class="post-discussion">
                        <h3>
                            Post Discussion
                        </h3>
                        <form class="form-horizontal" method="post" action="/class/{{$class->id}}/discussion/update" enctype="multipart/form-data">

                            <div class="form-group @if($errors->has('discussion_title')) has-error @endif">
                                <label for="discussion_title" class="col-sm-3 control-label">Discussion Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="discussion_title" placeholder="Discussion Title" value="{{$discussion->title}}">
                                    @if($errors->has('discussion_title'))
                                        {{$errors->first('discussion_title')}}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('discussion')) has-error @endif">
                                <label for="discussion" class="col-sm-3 control-label">Discussion</label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control" name="discussion" placeholder="Discussion">{{$discussion->body}}</textarea>
                                    @if($errors->has('discussion'))
                                        {{$errors->first('discussion')}}
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{$class->id}}">
                            <input type="hidden" name="discussion_id" value="{{$discussion->id}}">

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    @if(Session::has('message'))
                                        <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                                    @endif
                                    <button type="submit" class="btn btn-default">Update Dicussion</button>
                                </div>
                            </div>
                            {{csrf_field()}}
                        </form>
                    </div>


                @endif
            </div>
        </div>

        @if(Auth::user()->type === 'student')
            @include('feed.right_sidebar')
        @endif
    </div>
@endsection
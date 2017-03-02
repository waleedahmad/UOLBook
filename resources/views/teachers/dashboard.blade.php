@extends('layout')

@section('title')


    @if(isset($class))
        {{$class->subject_code}} / {{$class->subject_name}} / {{$class->subject_semester}}th semester / {{$class->instructor->first_name . ' '. $class->instructor->last_name}}
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
                    <div class="class-discussions">

                        {{--Post Discussion--}}
                        <div class="post-discussion">
                            <h3>
                                Post Discussion
                            </h3>
                            <form class="form-horizontal" method="post" action="/class/{{$class->id}}/discussion/post" enctype="multipart/form-data">

                                <div class="form-group @if($errors->has('discussion_title')) has-error @endif">
                                    <label for="discussion_title" class="col-sm-3 control-label">Discussion Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="discussion_title" placeholder="Discussion Title" value="{{old('discussion_title')}}">
                                        @if($errors->has('discussion_title'))
                                            {{$errors->first('discussion_title')}}
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if($errors->has('discussion')) has-error @endif">
                                    <label for="discussion" class="col-sm-3 control-label">Discussion</label>
                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control" name="discussion" placeholder="Discussion">{{old('discussion')}}</textarea>
                                        @if($errors->has('discussion'))
                                            {{$errors->first('discussion')}}
                                        @endif
                                    </div>
                                </div>

                                <input type="hidden" name="id" value="{{$class->id}}">

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        @if(Session::has('message'))
                                            <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                                        @endif
                                        <button type="submit" class="btn btn-default">Post discussion</button>
                                    </div>
                                </div>
                                {{csrf_field()}}
                            </form>
                        </div>

                        {{--Discussions--}}
                        <div class="discussions">
                            <h3>
                                Class Discussions
                            </h3>

                            @if($discussions->count())
                                @foreach($discussions as $discussion)

                                    <div class="discussion">

                                        @if($discussion->user->id === Auth::user()->id)
                                            <div class="dropdown discussion-dropdown">
                                                <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <span class="caret"></span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                                    <li><a href="#" class="delete-discussion" data-view="main" data-class-id="{{$class->id}}" data-id="{{$discussion->id}}">Delete Discussion</a></li>
                                                    <li><a href="/class/{{$class->id}}/discussion/{{$discussion->id}}/edit/" class="edit-discussion" data-id="{{$discussion->id}}">Edit Discussion</a></li>
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="col-xs-1">
                                            <div class="image-holder">
                                                <img alt="profile picture" class="user-img" src="/storage/{{$discussion->user->image_uri}}">
                                            </div>
                                        </div>

                                        <div class="col-xs-11">
                                            <div class="title">
                                                <a href="/class/{{$class->id}}/discussions/{{$discussion->id}}">
                                                    {{$discussion->title}}
                                                </a>
                                            </div>

                                            <div class="credits">
                                                By {{$discussion->user->first_name . ' '. $discussion->user->last_name}}
                                                - {{$discussion->replies->count()}} replies -
                                                {{$discussion->created_at->diffForHumans()}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info" role="alert">No Discussions.</div>
                            @endif

                            <div class="text-center">
                                {{$discussions->links()}}
                            </div>
                        </div>
                    </div>

                @endif
            </div>
        </div>

        @if(Auth::user()->type === 'student')
            @include('feed.right_sidebar')
        @endif
    </div>
@endsection
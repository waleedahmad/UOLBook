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

                        {{--Discussion--}}

                        <div class="discussions">
                            <div class="discussion">

                                @if($discussion->user->id === Auth::user()->id)
                                    <div class="dropdown discussion-dropdown">
                                        <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                            <li><a href="#" class="delete-discussion" data-view="discussion" data-class-id="{{$class->id}}" data-id="{{$discussion->id}}">Delete Discussion</a></li>
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
                                </div>

                                <div class="col-xs-12 body">
                                    {{$discussion->body}}
                                </div>

                                <div class="col-xs-12 credits">
                                    By {{$discussion->user->first_name . ' '. $discussion->user->last_name}}
                                    - {{$discussion->replies->count()}} replies -
                                    {{$discussion->created_at->diffForHumans()}}
                                </div>
                            </div>
                        </div>

                        {{--Discussion Replies--}}

                        <div class="replies">
                            @if($discussion->replies->count())
                                @foreach($discussion->replies as $reply)

                                    <div class="reply" data-reply-id="{{$reply->id}}">
                                        @if($reply->user->id === Auth::user()->id)
                                            <div class="dropdown reply-dropdown">
                                                <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <span class="caret"></span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                                    <li><a href="#" class="delete-reply" data-class-id="{{$class->id}}" data-id="{{$reply->id}}">Delete Reply</a></li>
                                                    <li><a href="#" class="edit-reply" data-class-id="{{$class->id}}" data-id="{{$reply->id}}">Edit Reply</a></li>
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="col-xs-1">
                                            <div class="image-holder">
                                                <img alt="profile picture" class="user-img" src="/storage/{{$reply->user->image_uri}}">
                                            </div>
                                        </div>

                                        <div class="col-xs-11 text">
                                            {{$reply->reply}}
                                        </div>

                                        <div class="col-xs-12 credits">
                                            By {{$reply->user->first_name . ' '. $reply->user->last_name}} -
                                            {{$reply->created_at->diffForHumans()}}
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class=" col-xs-12 post-reply">
                            <form action="/class/{{$class->id}}/discussion/reply" method="POST">
                                <div class="form-group @if($errors->has('reply')) has-error @endif">
                                    <label for="exampleInputEmail1"><h4>Reply to discussion</h4></label>
                                    <textarea type="text" class="form-control" name="reply" placeholder="Reply to discussion"></textarea>
                                    @if($errors->has('reply'))
                                        {{$errors->first('reply')}}
                                    @endif
                                </div>
                                <input type="hidden" name="class_id" value="{{$class->id}}">
                                <input type="hidden" name="discussion_id" value="{{$discussion->id}}">
                                <button type="submit" class="btn btn-default pull-right">Reply</button>
                                {{csrf_field()}}
                            </form>
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
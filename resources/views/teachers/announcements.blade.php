@extends('layout')

@section('title')


    @if(isset($class))
        Annoucements - {{$class->subject_code}} / {{$class->subject_name}} / {{$class->subject_semester}}th semester / {{$class->instructor->first_name . ' '. $class->instructor->last_name}}
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

            <div class="class-announcements">

                @include('teachers.navbar')

                @if(Auth::user()->type === 'teacher')
                    <h3>
                        Make an Announcement
                    </h3>
                    <div class="announce">
                        <form class="form-horizontal" method="post" action="/class/announcement" enctype="multipart/form-data">

                            <div class="form-group @if($errors->has('announcement_title')) has-error @endif">
                                <label for="announcement_title" class="col-sm-3 control-label">Announcement Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="announcement_title" placeholder="Announcement Title" value="{{old('announcement_title')}}">
                                    @if($errors->has('announcement_title'))
                                        {{$errors->first('announcement_title')}}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('announcement')) has-error @endif">
                                <label for="announcement" class="col-sm-3 control-label">Announcement</label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control" name="announcement" placeholder="Announcement">{{old('announcement')}}</textarea>
                                    @if($errors->has('announcement'))
                                        {{$errors->first('announcement')}}
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{$class->id}}">

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    @if(Session::has('message'))
                                        <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                                    @endif
                                    <button type="submit" class="btn btn-default">Make Announcement</button>
                                </div>
                            </div>
                            {{csrf_field()}}
                        </form>
                    </div>
                @endif

                <div class="announcements">
                    <h3>
                        Announcements
                    </h3>
                    @if($announcements->count())
                        @foreach($announcements as $announcement)

                            <div class="panel panel-default announcement">
                                <div class="panel-heading">
                                    <div class="panel-title"><b>{{$announcement->title}}</b></div>

                                    @if(Auth::user()->type === 'teacher')
                                        <span class="glyphicon glyphicon-remove remove-announcement pull-right" data-id="{{$announcement->id}}" aria-hidden="true"></span>
                                    @endif
                                </div>
                                <div class="panel-body">
                                    {{$announcement->announcement}}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info" role="alert">No Announcements.</div>
                    @endif
                </div>
            </div>
        </div>

        @if(Auth::user()->type === 'student')
            @include('feed.right_sidebar')
        @endif
    </div>
@endsection
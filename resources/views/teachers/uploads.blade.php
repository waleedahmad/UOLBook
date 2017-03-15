@extends('layout')

@section('title')
    @if(isset($class))
        Uploads - {{$class->course->code}} / {{$class->course->name}}  / {{$class->instructor->first_name . ' '. $class->instructor->last_name}}
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
            <div class="class-uploads">
                @include('teachers.navbar')

                @if(Auth::user()->type === 'teacher')
                    <div class="upload-form">

                        <h3>
                            Upload Course Material
                        </h3>
                        <form class="form-horizontal" method="post" action="/class/upload" enctype="multipart/form-data">

                            <div class="form-group @if($errors->has('caption')) has-error @endif">
                                <label for="caption" class="col-sm-3 control-label">File Caption</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="caption" placeholder="File Caption">
                                    @if($errors->has('caption'))
                                        {{$errors->first('caption')}}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('material_file')) has-error @endif">
                                <label for="registration_no" class="col-sm-3 control-label">Material File</label>
                                <div class="col-sm-9">
                                    <input type="file" name="material_file">
                                    <p class="help-block">Attach course related files.</p>

                                    @if($errors->has('material_file'))
                                        {{$errors->first('material_file')}}
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{$class->id}}">


                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    @if(Session::has('material_message'))
                                        <div class="alert alert-info" role="alert">{{Session::get('material_message')}}</div>
                                    @endif
                                    <button type="submit" class="btn btn-default">Upload File</button>
                                </div>
                            </div>
                            {{csrf_field()}}
                        </form>
                    </div>
                @endif

                <h3>
                    Course Material
                </h3>

                <div class="uploads">

                    @if($uploads->count())
                        @foreach($uploads as $file)
                            <div class="upload">
                                <a target="_blank" href="/storage{{$file->file_uri}}">{{$file->caption}}</a> - {{$file->created_at->diffForHumans()}}

                                @if(Auth::user()->type === 'teacher')
                                    <span class="glyphicon glyphicon-remove remove-upload pull-right" data-id="{{$file->id}}" aria-hidden="true"></span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info" role="alert">No File uploads.</div>
                    @endif


                </div>
            </div>
        </div>

        @if(Auth::user()->type === 'student')
            @include('feed.right_sidebar')
        @endif
    </div>
@endsection
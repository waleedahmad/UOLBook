@extends('layout')


@section('title')
    Add Class - UOLBook
@endsection

@section('content')

    <div class="container-fluid">
        @include('teachers.sidebar')

        <div class="content teacher-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="section-title">
                Please fill in following details for your new class.
            </div>

            <div class="add-class row col-sm-12 col-xs-12 col-md-8 col-lg-8">
                <form class="form-horizontal" method="post" action="/saveClass">

                    <div class="form-group @if($errors->has('subject_code')) has-error @endif">
                        <label for="email" class="col-sm-3 control-label">Semester</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="subject_code" id="subject-name">
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{$course->id}}">{{$course->name}} - {{$course->code}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('subject_code'))
                                {{$errors->first('subject_code')}}
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('subject_section')) has-error @endif">
                        <label for="email" class="col-sm-3 control-label">Section</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="subject_section" id="subject-sections" disabled>
                                <option value="">Select Section</option>
                            </select>
                            @if($errors->has('subject_section'))
                                {{$errors->first('subject_section')}}
                            @endif
                        </div>
                    </div>

                    {{csrf_field()}}

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            @if(Session::has('message'))
                                <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                            @endif
                            <button type="submit" class="btn btn-default">Create Class</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
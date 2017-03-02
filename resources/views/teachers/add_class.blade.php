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

                    <div class="form-group @if($errors->has('subject_name')) has-error @endif">
                        <label for="email" class="col-sm-3 control-label">Subject Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="subject_name" placeholder="Subject Name" value="{{old('subject_name')}}">
                            @if($errors->has('subject_name'))
                                {{$errors->first('subject_name')}}
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('subject_code')) has-error @endif">
                        <label for="email" class="col-sm-3 control-label">Subject Code</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="subject_code" placeholder="Subject Code" value="{{old('subject_code')}}">
                            @if($errors->has('subject_code'))
                                {{$errors->first('subject_code')}}
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('subject_semester')) has-error @endif">
                        <label for="email" class="col-sm-3 control-label">Semester</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="subject_semester">
                                <option value="">Select Semester</option>
                                <option value="1">1st Semester</option>
                                <option value="2">2nd Semester</option>
                                <option value="3">3rd Semester</option>
                                <option value="4">4th Semester</option>
                                <option value="5">5th Semester</option>
                                <option value="6">6th Semester</option>
                                <option value="7">7th Semester</option>
                                <option value="8">8th Semester</option>
                            </select>
                            @if($errors->has('subject_semester'))
                                {{$errors->first('subject_semester')}}
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
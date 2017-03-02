@extends('layout')


@section('title')
    Student Verification - UOLBook
@endsection

@section('content')
    <div class="auth row col-sm-12 col-xs-12 col-md-7 col-lg-7 center-block" style="float: none">

        <form class="form-horizontal" method="post" action="/verify/student" enctype="multipart/form-data">
            <div class="form-group">
                <h2 class="form-group text-center">
                    Verification Form
                </h2>
            </div>
            <div class="form-group @if($errors->has('registration_no')) has-error @endif">
                <label for="registration_no" class="col-sm-3 control-label">Registration No # </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="registration_no" placeholder="Registration No #" value="{{old('registration_no')}}">
                    @if($errors->has('registration_no'))
                        {{$errors->first('registration_no')}}
                    @endif
                </div>
            </div>

            <div class="form-group @if($errors->has('id_card')) has-error @endif">
                <label for="registration_no" class="col-sm-3 control-label">Upload Photo</label>
                <div class="col-sm-9">
                    <input type="file" name="id_card">
                    <p class="help-block">Please upload a photo or scan of your university card.</p>
                    @if($errors->has('id_card'))
                        {{$errors->first('id_card')}}
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    @if(Session::has('message'))
                        <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                    @endif
                    <button type="submit" class="btn btn-default">Request Verification</button>
                </div>
            </div>
            {{csrf_field()}}
        </form>
    </div>
@endsection
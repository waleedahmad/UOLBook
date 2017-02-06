@extends('layout')


@section('title')
    Reset Password - UOLBook
@endsection

@section('content')
    <div class="auth row col-sm-12 col-xs-12 col-md-6 col-lg-6 center-block" style="float: none">
        <form class="form-horizontal" method="post" action="/reset/password">

            <div class="form-group @if($errors->has('password')) has-error @endif">
                <label for="password" class="col-sm-3 control-label">New Password</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    @if($errors->has('password'))
                        {{$errors->first('password')}}
                    @endif
                </div>
            </div>

            <div class="form-group @if($errors->has('confirm_password')) has-error @endif">
                <label for="confirm_password" class="col-sm-3 control-label">Confirm Password</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                    @if($errors->has('confirm_password'))
                        {{$errors->first('confirm_password')}}
                    @endif
                </div>
            </div>

            <input type="hidden" name="token" value="{{$reset->token}}">
            <input type="hidden" name="email" value="{{$reset->email}}">


            {{csrf_field()}}

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                    @if(Session::has('message'))
                        <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                    @endif
                    <button type="submit" class="btn btn-default">Reset Password</button>
                </div>
            </div>
        </form>
    </div>
@endsection
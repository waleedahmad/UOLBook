@extends('layout')


@section('title')
    Login - UOLBook
@endsection

@section('content')
    <div class="auth row col-sm-12 col-xs-12 col-md-5 col-lg-5 center-block" style="float: none">
        <form class="form-horizontal" method="post" action="/login">

            <div class="form-group @if($errors->has('email')) has-error @endif">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" placeholder="Email">
                    @if($errors->has('email'))
                        {{$errors->first('email')}}
                    @endif
                </div>
            </div>

            <div class="form-group @if($errors->has('password')) has-error @endif">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    @if($errors->has('password'))
                        {{$errors->first('password')}}
                    @endif
                </div>
            </div>


            {{csrf_field()}}

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    @if(Session::has('message'))
                        <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                    @endif
                    <button type="submit" class="btn btn-default">Sign in</button>
                </div>
            </div>
        </form>
    </div>
@endsection
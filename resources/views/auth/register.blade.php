@extends('layout')


@section('title')
    Register - UOLBook
@endsection

@section('content')
    <div class="auth row col-sm-12 col-xs-12 col-md-6 col-lg-6 center-block" style="float: none">
        <form class="form-horizontal" method="post" action="/register">
            <div class="form-group @if($errors->has('firstName')) has-error @endif">
                <label for="firstName" class="col-sm-3 control-label">First Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="firstName" placeholder="First Name">
                    @if($errors->has('firstName'))
                        {{$errors->first('firstName')}}
                    @endif
                </div>
            </div>

            <div class="form-group @if($errors->has('lastName')) has-error @endif">
                <label for="lastName" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-9 pull-right">
                    <input type="text" class="form-control" name="lastName" placeholder="Last Name">
                    @if($errors->has('lastName'))
                        {{$errors->first('lastName')}}
                    @endif
                </div>
            </div>

            <div class="form-group @if($errors->has('email')) has-error @endif">
                <label for="email" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" name="email" placeholder="Email">
                    @if($errors->has('email'))
                        {{$errors->first('email')}}
                    @endif
                </div>
            </div>

            <div class="form-group @if($errors->has('password')) has-error @endif">
                <label for="password" class="col-sm-3 control-label">Password</label>
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

            <div class="form-group @if($errors->has('gender')) has-error @endif">
                <label for="gender" class="col-sm-3 control-label">Gender</label>
                <div class="col-sm-9">
                    <select name="gender" class="form-control">
                        <option value="">Choose Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    @if($errors->has('gender'))
                        {{$errors->first('gender')}}
                    @endif
                </div>
            </div>

            <div class="form-group @if($errors->has('usertype')) has-error @endif">
                <label class="col-sm-3 control-label">Account Type</label>
                <div class="col-sm-9">
                    <div class="form-group">
                        <div class="radio">
                            <label>
                                <input type="radio" name="usertype" value="student" checked>
                                Register as Student
                            </label>
                        </div>

                        <div class="radio">
                            <label>
                                <input type="radio" name="usertype" value="teacher">
                                Register as Teacher
                            </label>
                        </div>

                        @if($errors->has('usertype'))
                            {{$errors->first('usertype')}}
                        @endif
                    </div>

                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    @if(Session::has('message'))
                        <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                    @endif
                    <button type="submit" class="btn btn-default">Register</button>
                </div>
            </div>
            {{csrf_field()}}
        </form>
    </div>
@endsection
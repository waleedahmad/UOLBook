@extends('layout')


@section('title')
    Profile Settings - UOLBook
@endsection

@section('content')
    <div class="profile-settings">
        <div class="profile-setting row col-sm-12 col-xs-12 col-md-6 col-lg-6 center-block">

            <h3 class="title">
                Profile Picture
            </h3>

            <form class="form-horizontal" method="post" action="/user/settings/update/picture" enctype="multipart/form-data">
                <div class="form-group @if($errors->has('profile_pic')) has-error @endif">
                    <label for="registration_no" class="col-sm-3 control-label">Profile Photo</label>
                    <div class="col-sm-9">

                        <div class="image-holder">
                            <img class="profile-pic" src="/storage/{{Auth::user()->image_uri}}" >
                        </div>

                        <input type="file" name="profile_pic">
                        <p class="help-block">This photo will be displayed on your profile.</p>

                        @if($errors->has('profile_pic'))
                            {{$errors->first('profile_pic')}}
                        @endif
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        @if(Session::has('picture_message'))
                            <div class="alert alert-info" role="alert">{{Session::get('picture_message')}}</div>
                        @endif
                        <button type="submit" class="btn btn-default">Upload Photo</button>
                    </div>
                </div>
                {{csrf_field()}}
            </form>
        </div>

        @if(Auth::user()->type === 'student')
            <div class="profile-setting row col-sm-12 col-xs-12 col-md-6 col-lg-6 center-block">

                <h3 class="title">
                    Profile Cover
                </h3>

                <form class="form-horizontal" method="post" action="/user/settings/update/cover" enctype="multipart/form-data">
                    <div class="form-group @if($errors->has('profile_cover')) has-error @endif">
                        <label for="profile_cover" class="col-sm-3 control-label">Profile Cover</label>
                        <div class="col-sm-9">

                            <input type="file" name="profile_cover">
                            <p class="help-block">This photo will be displayed as your profile cover.</p>

                            @if($errors->has('profile_cover'))
                                {{$errors->first('profile_cover')}}
                            @endif
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            @if(Session::has('picture_message'))
                                <div class="alert alert-info" role="alert">{{Session::get('picture_message')}}</div>
                            @endif
                            <button type="submit" class="btn btn-default">Upload Cover</button>
                        </div>
                    </div>
                    {{csrf_field()}}
                </form>
            </div>
        @endif

        <div class="profile-setting row col-sm-12 col-xs-12 col-md-6 col-lg-6 center-block" id="profile">
            <h3 class="title">
                Profile Settings
            </h3>

            <form class="form-horizontal" method="post" action="/user/settings/update">
                <div class="form-group @if($errors->has('firstName')) has-error @endif">
                    <label for="firstName" class="col-sm-3 control-label">First Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="firstName" placeholder="First Name" value="{{Auth::user()->first_name}}">
                        @if($errors->has('firstName'))
                            {{$errors->first('firstName')}}
                        @endif
                    </div>
                </div>

                <div class="form-group @if($errors->has('lastName')) has-error @endif">
                    <label for="lastName" class="col-sm-3 control-label">Last Name</label>
                    <div class="col-sm-9 pull-right">
                        <input type="text" class="form-control" name="lastName" placeholder="Last Name" value="{{Auth::user()->last_name}}">
                        @if($errors->has('lastName'))
                            {{$errors->first('lastName')}}
                        @endif
                    </div>
                </div>

                <div class="form-group @if($errors->has('gender')) has-error @endif">
                    <label for="gender" class="col-sm-3 control-label">Gender</label>
                    <div class="col-sm-9">
                        <select name="gender" class="form-control">
                            <option value="">Choose Gender</option>
                            <option @if(Auth::user()->gender === 'male') selected="selected" @endif value="male">Male</option>
                            <option @if(Auth::user()->gender === 'female') selected="selected" @endif value="female">Female</option>
                            <option @if(Auth::user()->gender === 'other') selected="selected" @endif value="other">Other</option>
                        </select>
                        @if($errors->has('gender'))
                            {{$errors->first('gender')}}
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        @if(Session::has('message'))
                            <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                        @endif
                        <button type="submit" class="btn btn-default">Update Details</button>
                    </div>
                </div>
                {{csrf_field()}}
            </form>
        </div>


        <div class="profile-setting row col-sm-12 col-xs-12 col-md-6 col-lg-6 center-block" id="password">

            <h3 class="title">
                Change Password
            </h3>
            <form class="form-horizontal" method="post" action="/user/settings/update/password">
                <div class="form-group @if($errors->has('password')) has-error @endif">
                    <label for="old_password" class="col-sm-3 control-label">Old Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" name="old_password" placeholder="Old Password">
                        @if($errors->has('old_password'))
                            {{$errors->first('old_password')}}
                        @endif
                    </div>
                </div>

                <div class="form-group @if($errors->has('password')) has-error @endif">
                    <label for="password" class="col-sm-3 control-label">New Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" name="password" placeholder="New Password">
                        @if($errors->has('password'))
                            {{$errors->first('password')}}
                        @endif
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        @if(Session::has('password_message'))
                            <div class="alert alert-info" role="alert">{{Session::get('password_message')}}</div>
                        @endif
                        <button type="submit" class="btn btn-default">Change Password</button>
                    </div>
                </div>
                {{csrf_field()}}
            </form>
        </div>
    </div>


@endsection
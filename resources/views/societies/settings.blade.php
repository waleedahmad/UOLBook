@extends('layout')

@section('title')
    {{$society->name}} - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="content society col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="society-header" style="background-image : url('/storage{{$society->image_uri}}')">
            <div class="society-header-content">
                <div class="col-xs-10 society-about">
                    <h2>{{$society->name}}</h2>
                    <h5>
                        {{ucfirst($society->type)}}
                    </h5>
                </div>

                @if(Auth::user()->id != $society->user_id && $isVerified)
                    @if($isMember)
                        <button class="btn btn-default requestBtn leave-society" data-id="{{$society->id}}">Leave Society</button>
                    @else
                        @if($requestPending)
                            <button class="btn btn-default requestBtn cancel-society-request" data-id="{{$society->id}}">Cancel Request</button>
                        @else
                            <button class="btn btn-default requestBtn join-society" data-id="{{$society->id}}">Join Society</button>
                        @endif
                    @endif
                @endif
            </div>
        </div>

        @include('societies.navbar')

        @if($isVerified)
            @if(Auth::user()->id === $society->user_id)
                <div class="society-settings">

                    <h3>
                        Society Cover
                    </h3>

                    <form class="form-horizontal" method="post" action="/society/settings/cover/update" enctype="multipart/form-data">
                        <div class="form-group @if($errors->has('cover_photo')) has-error @endif">
                            <label for="registration_no" class="col-sm-3 control-label">Cover Photo</label>
                            <div class="col-sm-9">
                                <input type="file" name="cover_photo">
                                <p class="help-block">This photo will be displayed on your society page.</p>

                                @if($errors->has('cover_photo'))
                                    {{$errors->first('cover_photo')}}
                                @endif
                            </div>
                        </div>

                        <input type="hidden" name="society_id" value="{{$society->id}}">


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

                    <h3>
                        Society Settings
                    </h3>
                    <form class="form-horizontal" method="post" action="/society/settings/update" enctype="multipart/form-data">

                        <div class="form-group @if($errors->has('society_name')) has-error @endif">
                            <label for="society_name" class="col-sm-3 control-label">Society Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="society_name" placeholder="Society Name" value="{{$society->name}}">
                                @if($errors->has('society_name'))
                                    {{$errors->first('society_name')}}
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if($errors->has('society_type')) has-error @endif">
                            <label for="society_type" class="col-sm-3 control-label">Society Type</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="society_type">
                                    <option value="">Select Type</option>
                                    <option value="arts" @if($society->type === 'arts') selected="selected" @endif>Arts</option>
                                    <option value="science" @if($society->type === 'science') selected="selected" @endif>Science</option>
                                    <option value="sports" @if($society->type === 'sports') selected="selected" @endif>Sports</option>
                                    <option value="gaming" @if($society->type === 'gaming') selected="selected" @endif>Gaming</option>
                                </select>
                                @if($errors->has('society_type'))
                                    {{$errors->first('society_type')}}
                                @endif
                            </div>
                        </div>

                        <input type="hidden" name="society_id" value="{{$society->id}}">

                        {{csrf_field()}}

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                @if(Session::has('message'))
                                    <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                                @endif
                                <button type="submit" class="btn btn-default">Update Society</button>
                            </div>
                        </div>
                    </form>

                    <h3>
                        Delete Society
                    </h3>

                    <button class="btn btn-default delete-society" data-role="user" data-id="{{$society->id}}">
                        Delete Society
                    </button>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    You're not a member of this society.
                </div>
            @endif
        @else
            <div class="alert alert-info" role="alert">
                This society is awaiting verification.
            </div>
        @endif
    </div>

    @include('feed.right_sidebar')

@endsection
@extends('layout')


@section('title')
    Create Society - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="societies-content col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="societies">
            <h1 class="section-title">
                Please fill in following details for your Society.
            </h1>

            <div class="add-society row col-sm-12 col-xs-12 col-md-12 col-lg-12">
                <form class="form-horizontal" method="post" action="/societies/create" enctype="multipart/form-data">

                    <div class="form-group @if($errors->has('society_name')) has-error @endif">
                        <label for="society_name" class="col-sm-3 control-label">Society Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="society_name" placeholder="Society Name">
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
                                <option value="arts">Arts</option>
                                <option value="science">Science</option>
                                <option value="sports">Sports</option>
                                <option value="gaming">Gaming</option>
                            </select>
                            @if($errors->has('society_type'))
                                {{$errors->first('society_type')}}
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('display_image')) has-error @endif">
                        <label for="display_image" class="col-sm-3 control-label">Society Cover</label>
                        <div class="col-sm-9">
                            <input type="file" name="display_image">
                            <p class="help-block">This photo will be displayed on your society page.</p>

                            @if($errors->has('display_image'))
                                {{$errors->first('display_image')}}
                            @endif
                        </div>
                    </div>

                    {{csrf_field()}}

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            @if(Session::has('message'))
                                <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                            @endif
                            <button type="submit" class="btn btn-default">Create Society</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('feed.right_sidebar')
@endsection
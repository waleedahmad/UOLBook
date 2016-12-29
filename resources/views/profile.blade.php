@extends('layout')

@section('title')
    {{$user->first_name . ' ' . $user->last_name}} - UolBook
@endsection

@section('content')

    <div class="container-fluid profile-header">
        <div class="container">

            <div class="row profile-header-content">
                <div class="col-md-3 col-sm-3 col-xs-4 profile-pic"><img src="{{$user->image_uri}}" class="img-thumbnail"></div>
                <div class="col-md-9 col-sm-9 col-xs-8 profile-about">
                    <h2>{{$user->first_name . ' ' . $user->last_name}}</h2>
                    <p><i class="glyphicons glyphicons-riflescope"></i> <a href="#">{{$user->registration_id}}</a></p>
                </div>
            </div>

        </div>
    </div>

    <div class="left-sidebar col-xs-12 col-sm-12 col-md-2 col-lg-2">

    </div>

    <div class="content col-xs-12 col-sm-12 col-md-7 col-lg-7">
        @include('status')

        @include('posts')
    </div>
@endsection
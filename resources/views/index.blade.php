@extends('layout')

@section('title')
    UOLBook
@endsection

@section('content')
    {{Auth::user()}}

    <img src="{{Auth::user()->image_uri}}" alt="">
@endsection
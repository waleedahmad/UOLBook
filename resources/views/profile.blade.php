@extends('layout')

@section('title')
    {{$user->first_name . ' ' . $user->last_name}} - UolBook
@endsection

@section('content')
    {{Auth::user()}}

    <img src="{{Auth::user()->image_uri}}" alt="">
@endsection
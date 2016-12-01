@extends('layout')

@section('title')
    {{Auth::user()->first_name . ' ' . Auth::user()->last_name }} - Teachers Dashboard
@endsection

@section('content')
    <div class="container-fluid admin-verification">
        @include('teachers.sidebar')

        <div class="content col-xs-12 col-sm-9 col-md-9 col-lg-9">
            @if(isset($class))
                {{$class->subject_code}} / {{$class->subject_name}} / {{$class->subject_semester}}
            @endif
        </div>
    </div>
@endsection
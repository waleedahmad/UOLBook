@extends('layout')

@section('title')
    {{$teacher->first_name}} {{ $teacher->last_name }} / Classes - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="all-classes col-xs-12 col-sm-12 col-md-7 col-lg-7">

        <div class="info">
            <div class="teacher-info">
                <div class="col-xs-2 image">
                    <div class="image-holder">
                        <img src="/storage/{{$teacher->image_uri}}" alt="">
                    </div>
                </div>

                <div class="col-xs-10">
                    <div class="name">
                        {{$teacher->first_name}} {{ $teacher->last_name }}
                    </div>
                </div>
            </div>
        </div>

        <div class="classes">
            <h1>
                Your Enrolled Courses
            </h1>

            @if($teacher->enrolledClasses->count())
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            Course
                        </th>

                        <th>
                            Course Code
                        </th>

                        <th>Semester</th>

                        <th></th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($teacher->enrolledClasses as $class)
                        <tr class="request">
                            <td>
                                {{$class->course->name}}
                            </td>

                            <td>
                                {{$class->course->code}}
                            </td>

                            <td>
                                <a href="/class/{{$class->id}}">view</a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @else
                <div class="alert alert-info" role="alert">
                    You're not currently enrolled in any class of {{$teacher->first_name}} {{ $teacher->last_name }}
                </div>
            @endif

        </div>

        {{--<div class="classes">
            <h1>
                Other Courses
            </h1>

            @if($teacher->otherClasses->count())
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            Course
                        </th>

                        <th>
                            Course Code
                        </th>

                        <th>Section</th>

                        <th></th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($teacher->otherClasses as $class)
                        <tr class="request">
                            <td>
                                {{$class->course->name}}
                            </td>

                            <td>
                                {{$class->course->code}}
                            </td>

                            <td>
                                {{$class->section}}
                            </td>

                            <td>
                                <a href="/class/{{$class->id}}">view</a>
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @else
                <div class="alert alert-info" role="alert">
                    {{$teacher->first_name}} {{ $teacher->last_name }} is not currently teacher any other courses.
                </div>
            @endif
        </div>--}}

    </div>

    @include('feed.right_sidebar')

@endsection
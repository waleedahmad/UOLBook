@extends('layout')

@section('title')
    @if(isset($class))
        Students - {{$class->course->code}} / {{$class->course->name}}  / {{$class->instructor->first_name . ' '. $class->instructor->last_name}}
    @else
        {{Auth::user()->first_name . ' ' . Auth::user()->last_name }} - Teachers Dashboard
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        @if(Auth::user()->type === 'teacher')
            @include('teachers.sidebar')
        @else
            @include('feed.left_sidebar')
        @endif

        <div class="teacher-content col-xs-12 col-sm-9 col-md-9 col-lg-7">

            <div class="students-list">
                @include('teachers.navbar')

                @if($students->count())
                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                Student Photo
                            </th>
                            <th>
                                Student Name
                            </th>

                            <th>
                                Student id
                            </th>

                            <th>
                                Action
                            </th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($students as $student)
                            <tr class="student">
                                <td>
                                    <div class="image-holder">
                                        <img src="/storage/{{$student->user->image_uri}}" alt="">
                                    </div>
                                </td>

                                <td>
                                    {{$student->user->first_name . ' ' . $student->user->last_name}}
                                </td>

                                <td>
                                    {{$student->user->registration_id}}
                                </td>

                                <td>
                                    <button class="btn btn-danger remove-student" data-id="{{$student->id}}">Remove</button>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info" role="alert">There are no students currently enrolled in this class.</div>
                @endif


            </div>
        </div>

        @if(Auth::user()->type === 'student')
            @include('feed.right_sidebar')
        @endif
    </div>
@endsection
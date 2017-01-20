@extends('layout')

@section('title')
    Classes - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="all-classes col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="classes">
            <h1>
                Your Classes
            </h1>
            @if($classes->count())
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            Instructor
                        </th>

                        <th>
                            Subject Name
                        </th>

                        <th>
                            Subject Code
                        </th>

                        <th>Semester</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($classes as $class)
                        <tr class="request">
                            <td>
                                {{$class->instructor->first_name . ' ' . $class->instructor->last_name}}
                            </td>

                            <td>
                                {{$class->subject_name}}
                            </td>

                            <td>
                                {{$class->subject_code}}
                            </td>

                            <td>
                                {{$class->subject_semester}} Semester
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
                    You're not currently not a student of any class, please view suggestion and request instructor to become a student of any class.
                </div>

            @endif
        </div>

        <div class="suggestions">
            <h1>
                Class Suggestions
            </h1>
            @if($suggestions->count())
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            Instructor
                        </th>

                        <th>
                            Subject Name
                        </th>

                        <th>
                            Subject Code
                        </th>

                        <th>Semester</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($suggestions as $class)
                        <tr class="request">
                            <td>
                                {{$class->instructor->first_name . ' ' . $class->instructor->last_name}}
                            </td>

                            <td>
                                {{$class->subject_name}}
                            </td>

                            <td>
                                {{$class->subject_code}}
                            </td>

                            <td>
                                {{$class->subject_semester}} Semester
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
                    No suggestions available at this moment!
                </div>
            @endif
        </div>
    </div>

    @include('feed.right_sidebar')

@endsection
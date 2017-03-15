@extends('layout')

@section('title')
    Classes / Admin - UOLBook
@endsection

@section('content')
    <div class="admin-verification">
        @include('admin.sidebar')
        <div class="admin-content col-xs-12 col-sm-9 col-md-9 col-lg-9">

            @if($classes->count())
                <table class="table classes">
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

                        <th>Section</th>

                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($classes as $class)
                        <tr class="class">
                            <td>
                                {{$class->instructor->first_name . ' ' . $class->instructor->last_name}}
                            </td>

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
                                <button class="btn btn-danger delete-class" data-id="{{$class->id}}">Delete Class</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @else
                <div class="alert alert-info" role="alert">No classes found.</div>
            @endif

        </div>

    </div>
@endsection
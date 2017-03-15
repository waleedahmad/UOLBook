@extends('layout')


@section('title')
    Class Secrets - UOLBook
@endsection

@section('content')

    <div class="container-fluid">
        @include('teachers.sidebar')

        <div class="content teacher-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="section-title">
                Student Class Access Keys
            </div>

            <div class="add-class row col-xs-12">
                @if($classes->count())
                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                Course Name
                            </th>

                            <th>
                                Course Code
                            </th>

                            <th>
                                Section
                            </th>

                            <th>
                                Access Key
                            </th>

                        </tr>
                        </thead>

                        <tbody>

                        @foreach($classes as $class)
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
                                    {{$class->secret}}
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
                        Select any class in sidebar or <a href="/addClass" class="alert-link">Create Class</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
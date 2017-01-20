@extends('layout')

@section('title')
    @if(isset($class))
        Join Requests - {{$class->subject_code}} / {{$class->subject_name}} / {{$class->subject_semester}}th semester / {{$class->instructor->first_name . ' '. $class->instructor->last_name}}
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

            <div class="join-requests">
                @include('teachers.navbar')

                @if($requests->count())
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

                        @foreach($requests as $request)
                            <tr class="request">
                                <td>
                                    <div class="image-holder">
                                        <img src="/storage/{{$request->user->image_uri}}" alt="">
                                    </div>
                                </td>

                                <td>
                                    {{$request->user->first_name . ' ' . $request->user->last_name}}
                                </td>

                                <td>
                                    {{$request->user->registration_id}}
                                </td>

                                <td>
                                    <button class="btn btn-default accept-join-request" data-id="{{$request->id}}">Approve</button>
                                    <button class="btn btn-danger disapprove-join-request" data-id="{{$request->id}}">Disapprove</button>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info" role="alert">No news join requests.</div>
                @endif
            </div>
        </div>

        @if(Auth::user()->type === 'student')
            @include('feed.right_sidebar')
        @endif
    </div>
@endsection
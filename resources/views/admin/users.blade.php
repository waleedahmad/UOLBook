@extends('layout')

@section('title')
    Users / Admin - UOLBook
@endsection

@section('content')
    @include('admin.sidebar')

    <div class="admin-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                @if(Request::path() === 'admin/users/teachers')
                    Filter: Teachers
                @elseif(Request::path() === 'admin/users/students')
                    Filter: Students
                @else
                    Filter Users
                @endif
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li><a href="/admin/users/teachers">Teacher Requests</a></li>
                <li><a href="/admin/users/students">Student Requests</a></li>
            </ul>
        </div>

        <table class="table users">
            <thead>
            <tr>
                <th>
                    Profile Pic
                </th>

                <th>
                    Email/Username
                </th>

                <th>
                    Account Created
                </th>

                <th>Type</th>

                <th>
                    Action
                </th>
            </tr>
            </thead>

            <tbody>

            @foreach($users as $user)
                <tr class="request">
                    <td>
                        <a href="/storage/{{$user->image_uri}}" target="_blank">
                            <img class="media-object" src="/storage/{{$user->image_uri}}" alt="..."  style="width: 50px; height: 50px;">
                        </a>
                    </td>

                    <td>
                        @if($user->type === 'teacher' || $user->type === 'admin')
                            {{$user->email}}
                        @else
                            {{$user->registration_id}}
                        @endif
                    </td>

                    <td>
                        {{$user->created_at}}
                    </td>

                    <td>
                        {{$user->type}}
                    </td>

                    <td>
                        @if($user->type != 'admin')
                            <button class="btn btn-danger delete-user" data-id="{{$user->id}}">Remove User</button>
                        @endif
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>
@endsection
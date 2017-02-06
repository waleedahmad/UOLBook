@extends('layout')

@section('title')
    Users / Admin - UOLBook
@endsection

@section('content')
    @include('admin.sidebar')

    <div class="admin-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
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
                    Verified
                </th>

                <th>
                    Action
                </th>
            </tr>
            </thead>

            <tbody>

            @foreach($users as $user)
                <tr class="user">
                    <td>
                        <a href="/storage/{{$user->image_uri}}" target="_blank">
                            <div class="image-holder">
                                <img src="/storage/{{$user->image_uri}}" alt="..." >
                            </div>
                        </a>
                    </td>

                    <td>
                        @if($user->type === 'teacher' || $user->type === 'admin')
                            {{$user->email}}
                        @else
                            @if($user->registration_id)
                                {{$user->registration_id}}
                            @else
                                {{$user->email}}
                            @endif
                        @endif
                    </td>

                    <td>
                        {{$user->created_at}}
                    </td>

                    <td>
                        {{$user->type}}
                    </td>

                    <td>
                        @if($user->verified)
                            Yes
                        @else
                            No
                        @endif
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
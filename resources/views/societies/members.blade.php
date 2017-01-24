@extends('layout')

@section('title')
   Memebers / {{$society->name}} - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="content society col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="society-header" style="background-image : url('/storage{{$society->image_uri}}')">
            <div class="society-header-content">
                <div class="col-xs-10 society-about">
                    <h2>{{$society->name}}</h2>
                    <h5>
                        {{ucfirst($society->type)}}
                    </h5>
                </div>

                @if(Auth::user()->id != $society->user_id && $isVerified)
                    @if($isMember)
                        <button class="btn btn-default requestBtn leave-society" data-id="{{$society->id}}">Leave Society</button>
                    @else
                        @if($requestPending)
                            <button class="btn btn-default requestBtn cancel-society-request" data-id="{{$society->id}}">Cancel Request</button>
                        @else
                            <button class="btn btn-default requestBtn join-society" data-id="{{$society->id}}">Join Society</button>
                        @endif
                    @endif
                @endif
            </div>
        </div>

        @include('societies.navbar')

        @if($isVerified)
            @if(Auth::user()->id === $society->user_id || $isMember)
                @if($members->count())
                    <div class="society-requests">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>
                                    Picture
                                </th>
                                <th>
                                    Name
                                </th>

                                <th>
                                    Student id
                                </th>

                                @if(Auth::user()->id === $society->user_id)
                                <th>
                                    Action
                                </th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($members as $member)
                                <tr class="request">
                                    <td>
                                        <div class="image-holder">
                                            <img src="/storage/{{$member->user->image_uri}}" alt="">
                                        </div>
                                    </td>

                                    <td>
                                        <a href="/profile/{{$member->user->id}}">{{$member->user->first_name . ' ' . $member->user->last_name}}</a>
                                    </td>

                                    <td>
                                        {{$member->user->registration_id}}
                                    </td>

                                    @if(Auth::user()->id === $society->user_id)
                                    <td>
                                        <button class="btn btn-danger remove-soc-member" data-id="{{$member->id}}">Remove</button>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info" role="alert">No members.</div>
                @endif
            @else
                <div class="alert alert-info" role="alert">
                    You're not a member of this society.
                </div>
            @endif
        @else
            <div class="alert alert-info" role="alert">
                This society is awaiting verification.
            </div>
        @endif
    </div>

    @include('feed.right_sidebar')

@endsection
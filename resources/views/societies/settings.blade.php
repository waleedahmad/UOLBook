@extends('layout')

@section('title')
    {{$society->name}} - UOLBook
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
            @if(Auth::user()->id === $society->user_id)

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
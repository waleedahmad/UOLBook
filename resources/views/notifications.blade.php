@extends('layout')

@section('title')
    Teachers - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="notification-content col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="notifications">
            @foreach($notifications as $notification)
                <div class="notification" data-id="{{$notification->id}}">
                    <div class="img col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <div class="image-holder">
                            <img src="/storage/{{$notification->fromUser->image_uri}}" alt="">
                        </div>
                    </div>

                    <div class="text col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <div>
                            @if($notification->type === 'like')
                                {{$notification->fromUser->first_name . ' ' . $notification->fromUser->last_name}} liked you post
                            @elseif($notification->type === 'comment')
                                {{$notification->fromUser->first_name . ' ' . $notification->fromUser->last_name}} commented on your post
                            @endif
                        </div>
                        <div>
                            {{$notification->created_at->diffForHumans()}}
                        </div>
                    </div>
                </div>
            @endforeach

            @if(!$notifications->count())
                <div class="alert alert-info" role="alert">
                    You currently have no activity on your content.
                </div>
            @endif
        </div>
    </div>

    @include('feed.right_sidebar')

@endsection
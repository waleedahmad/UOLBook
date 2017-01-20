<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">UOLBook</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())

                    @if(Auth::user()->type === 'teacher')
                        <li>
                            <a href="/addClass">Create Class</a>
                        </li>
                    @endif

                    @if(Auth::user()->type === 'student' && Auth::user()->verified)
                        <li class="dropdown friend-requests">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span> @if($friend_requests->count()) <span class="badge">{{$friend_requests->count()}}</span> @endif</span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($friend_requests as $f_request)
                                    <li class="request">
                                        <div class="img col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                            <div class="image-holder">
                                                <img src="/storage/{{$f_request->fromUser->image_uri}}" alt="">
                                            </div>
                                        </div>

                                        <div class="text col-xs-4 col-sm-7 col-md-7 col-lg-7">
                                            <a href="/profile/{{$f_request->fromUser->id}}">{{$f_request->fromUser->first_name . ' ' . $f_request->fromUser->last_name}}</a> Sent you friend requests
                                        </div>

                                        <div class="accept col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <button class="btn btn-default btn-sm pull-right accept-request" data-request-id="{{$f_request->id}}">Accept</button>
                                            <button class="btn btn-danger btn-sm pull-right delete-request" data-request-id="{{$f_request->id}}">Delete</button>
                                        </div>
                                    </li>
                                @endforeach

                                @if(!$friend_requests->count())
                                    <li class="no-requests">
                                        You current have no Friend Requests
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <li class="dropdown notifications">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="glyphicon glyphicon-bell" aria-hidden="true"></span> @if($notifications->count()) <span class="badge">{{$notifications->count()}}</span> @endif</span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($notifications as $notification)
                                    <li class="notification" data-id="{{$notification->id}}">
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
                                    </li>
                                @endforeach

                                @if(!$notifications->count())
                                    <li class="no-requests">
                                        You current have no Notifications
                                    </li>

                                @endif
                                    <a class="all-notifications" href="/notifications/all">See all notifications</a>
                            </ul>
                        </li>
                    @endif
                <li>
                    <a href="/">Home</a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->first_name .' ' .Auth::user()->last_name}} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->type != 'teacher' && Auth::user()->type != 'admin')
                            <li><a href="/profile/{{Auth::user()->id}}">Profile</a></li>
                        @endif
                        <li><a href="/user/settings">Setting</a></li>
                        <li>
                            <a href="/logout">Logout</a>
                        </li>
                    </ul>
                </li>
                @else
                    <li><a href="/register">Register</a></li>
                    <li><a href="/login">Login</a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
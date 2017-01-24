@extends('layout')

@section('title')
    Classes - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="societies-content col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="societies">
            <h1>
                Your Societies
            </h1>
            @if($societies->count())
                @foreach($societies as $society)
                    <div class="media society">
                        <div class="media-left">
                            <a href="#">
                                <div class="img-holder">
                                    <img class="media-object" src="/storage/{{$society->image_uri}}" alt="{{$society->name}}">
                                </div>
                            </a>
                        </div>
                        <div class="media-body">
                            <a href="/society/{{$society->id}}"><h4 class="media-heading">{{$society->name}}</h4></a>
                            <h5>
                                {{ucfirst($society->type)}}
                            </h5>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info" role="alert">
                    You're currently not an admin of any society, please view suggestions or
                    <a href="/societies/create" class="alert-link">Create Society</a>.
                </div>

            @endif
        </div>

        <div class="societies">
            <h1>
                Society Suggestions
            </h1>
            @if($suggestions->count())
                @foreach($suggestions as $society)
                    <div class="media society">
                        <div class="media-left">
                            <a href="#">
                                <div class="img-holder">
                                    <img class="media-object" src="/storage/{{$society->image_uri}}" alt="{{$society->name}}">
                                </div>
                            </a>
                        </div>
                        <div class="media-body">
                            <a href="/society/{{$society->id}}"><h4 class="media-heading">{{$society->name}}</h4></a>
                            <h5>
                                {{ucfirst($society->type)}}
                            </h5>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info" role="alert">
                    You're currently not an admin of any society, please view suggestions or
                    <a href="/societies/create" class="alert-link">Create Society</a>.
                </div>

            @endif
        </div>
    </div>

    @include('feed.right_sidebar')

@endsection
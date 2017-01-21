@extends('layout')

@section('title')
    Classes - UOLBook
@endsection

@section('content')

    @include('feed.left_sidebar')

    <div class="all-societies col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="societies">
            <h1>
                Your Societies
            </h1>
            @if($societies->count())
                @foreach($societies as $society)
                    <div class="media society">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object" src="/storage/{{$society->image_uri}}" alt="{{$society->name}}">
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

        <div class="suggestions">
            <h1>
                Class Suggestions
            </h1>
            @if($suggestions->count())
                <table class="table">
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

                        <th>Semester</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($suggestions as $class)
                        <tr class="request">
                            <td>
                                {{$class->instructor->first_name . ' ' . $class->instructor->last_name}}
                            </td>

                            <td>
                                {{$class->subject_name}}
                            </td>

                            <td>
                                {{$class->subject_code}}
                            </td>

                            <td>
                                {{$class->subject_semester}} Semester
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
                    No suggestions available at this moment!
                </div>
            @endif
        </div>
    </div>

    @include('feed.right_sidebar')

@endsection
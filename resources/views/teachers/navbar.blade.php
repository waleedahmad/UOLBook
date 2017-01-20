@if(isset($class))
    <div class="section-title">
        {{$class->subject_code}} / {{$class->subject_name}} / {{$class->subject_semester}}th semester / {{$class->instructor->first_name . ' '. $class->instructor->last_name}}
    </div>
@else
    @if(Auth::user()->type === 'teacher')
        <div class="alert alert-info" role="alert">
            Select any class in sidebar or <a href="/addClass" class="alert-link">Create Class</a>
        </div>
    @endif
@endif

@if(isset($class) && Auth::user()->type === 'teacher' || Auth::user()->type === 'student' && $is_student)
    <ul class="nav nav-pills">
        <li role="presentation" @if(Request::path() === 'class/'.$class->id ) class="active" @endif><a href="/class/{{$class->id}}">Discussions</a></li>
        <li role="presentation" @if(Request::path() === 'class/'.$class->id.'/announcements' ) class="active" @endif><a href="/class/{{$class->id}}/announcements">Announcements</a></li>
        <li role="presentation" @if(Request::path() === 'class/'.$class->id.'/material' ) class="active" @endif><a href="/class/{{$class->id}}/material">Course Material</a></li>
        @if(Auth::user()->type === 'teacher')
            <li role="presentation" @if(Request::path() === 'class/'.$class->id.'/requests' ) class="active" @endif><a href="/class/{{$class->id}}/requests">Requests</a></li>
            <li role="presentation" @if(Request::path() === 'class/'.$class->id.'/students' ) class="active" @endif><a href="/class/{{$class->id}}/students">Students</a></li>
        @endif
    </ul>
@endif
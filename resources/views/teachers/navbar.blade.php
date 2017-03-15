@if(isset($class))
    <div class="section-title">
        {{$class->course->code}} / {{$class->course->name}} / {{$class->instructor->first_name . ' '. $class->instructor->last_name}} / Section ({{$class->section}})
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
            <li role="presentation" @if(Request::path() === 'class/'.$class->id.'/requests' ) class="active" @endif><a href="/class/{{$class->id}}/requests">
                    <span class="badge pull-right">{{\App\Models\ClassJoin::where('class_id','=', $class->id)->count()}}</span>  Requests  </a>
            </li>
            <li role="presentation" @if(Request::path() === 'class/'.$class->id.'/students' ) class="active" @endif><a href="/class/{{$class->id}}/students">
                    <span class="badge pull-right">{{\App\Models\Student::where('class_id','=', $class->id)->count()}}</span> Students   </a>
            </li>
        @endif
    </ul>
@endif
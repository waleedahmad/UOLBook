<div class="sidebar col-xs-12 col-sm-3 col-md-3 col-lg-3">
    <ul class="nav nav-pills nav-stacked">
        @foreach($classes as $class)
            <li role="presentation" @if(Request::path() === 'teacher/class/'.$class->id ) class="active" @endif><a href="/teacher/class/{{$class->id}}">{{$class->subject_name}} ({{$class->subject_code}})</a></li>
        @endforeach
    </ul>
</div>
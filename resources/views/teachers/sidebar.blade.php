<div class="sidebar teacher-sidebar col-xs-12 col-sm-3 col-md-3 col-lg-3">

    <div class="section-title">
        Your Classes
    </div>

    <ul class="nav nav-pills nav-stacked">
        @foreach($classes as $class)
            <li role="presentation"
                @if(Request::path() === 'class/'.$class->id ||
                    Request::path() === 'class/'.$class->id.'/announcements' ||
                    Request::path() === 'class/'.$class->id.'/material' ||
                    Request::path() === 'class/'.$class->id.'/requests' ||
                    Request::path() === 'class/'.$class->id.'/students' ||
                    isset($discussions) &&  Request::path() === 'class/'.$class->id ||
                    isset($d_id) && Request::path() === 'class/'.$class->id.'/discussions/'.$d_id)
                    class="active"
                @endif
            >
                <a href="/class/{{$class->id}}">{{$class->course->name}} - ({{$class->section}}) ({{$class->course->code}})</a>
            </li>
        @endforeach
    </ul>
</div>
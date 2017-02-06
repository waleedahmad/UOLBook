<div class="admin-sidebar col-xs-12 col-sm-3 col-md-3 col-lg-3">
    <ul class="nav nav-pills nav-stacked">
        <li role="presentation"
            @if(Request::path() === 'admin' || Request::path() === 'admin/teachers' || Request::path() === 'admin/students') class="active" @endif
        >
            <a href="/admin">Verification Requests <span class="badge pull-right">{{\App\Models\Verification::count()}}</span></a>
        </li>
        <li role="presentation" @if(Request::path() === 'admin/users') class="active" @endif><a href="/admin/users">Users <span class="badge pull-right">{{\App\Models\User::count()}}</span></a></li>
        <li role="presentation" @if(Request::path() === 'admin/classes') class="active" @endif><a href="/admin/classes">Classes <span class="badge pull-right">{{\App\Models\Classes::count()}}</span></a></li>
        <li role="presentation" @if(Request::path() === 'admin/societies') class="active" @endif><a href="/admin/societies">Societies <span class="badge pull-right">{{\App\Models\Society::count()}}</span></a></li>
        <li role="presentation" @if(Request::path() === 'admin/society/requests') class="active" @endif><a href="/admin/society/requests">Society Requests <span class="badge pull-right">{{\App\Models\Society::where('verified', '!=' , true)->count()}}</span>  </a></li>
    </ul>
</div>


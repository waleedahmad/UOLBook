<div class="admin-sidebar col-xs-12 col-sm-3 col-md-3 col-lg-3">
    <ul class="nav nav-pills nav-stacked">
        <li role="presentation"
            @if(Request::path() === 'admin' || Request::path() === 'admin/teachers' || Request::path() === 'admin/students') class="active" @endif
        >
            <a href="/admin">Verification Requests</a>
        </li>
        <li role="presentation" @if(Request::path() === 'admin/users') class="active" @endif><a href="/admin/users">Users</a></li>
        <li role="presentation" @if(Request::path() === 'admin/classes') class="active" @endif><a href="/admin/classes">Classes</a></li>
        <li role="presentation" @if(Request::path() === 'admin/societies') class="active" @endif><a href="/admin/societies">Societies</a></li>
        <li role="presentation" @if(Request::path() === 'admin/society/requests') class="active" @endif><a href="/admin/society/requests">Society Requests</a></li>
    </ul>
</div>
<div class="sidebar col-xs-12 col-sm-3 col-md-3 col-lg-3">
    <ul class="nav nav-pills nav-stacked">
        <li role="presentation"
            @if(Request::path() === 'admin' || Request::path() === 'admin/teachers' || Request::path() === 'admin/students') class="active" @endif
        >
            <a href="/admin">Verification Requests</a>
        </li>
        <li role="presentation" @if(Request::path() === 'admin/users') class="active" @endif><a href="/admin/users">Users</a></li>
        <li role="presentation" @if(Request::path() === 'admin/messages') class="active" @endif><a href="/admin/messages">Messages</a></li>
    </ul>
</div>
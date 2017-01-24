<div class="links">
    <ul>
        <li>
            <a href="/society/{{$society->id}}" @if(Request::path() === 'society/'.$society->id) class="active" @endif>Posts</a>
        </li>

        <li>
            <a href="/society/{{$society->id}}/members" @if(Request::path() === 'society/'.$society->id.'/members') class="active" @endif>Members</a>
        </li>

        @if(Auth::user()->id === $society->user_id)
            <li>
                <a href="/society/{{$society->id}}/requests" @if(Request::path() === 'society/'.$society->id.'/requests') class="active" @endif>Requests</a>
            </li>

            <li>
                <a href="/society/{{$society->id}}/settings" @if(Request::path() === 'society/'.$society->id.'/settings') class="active" @endif>Settings</a>
            </li>
        @endif
    </ul>
</div>
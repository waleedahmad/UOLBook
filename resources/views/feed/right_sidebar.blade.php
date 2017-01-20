<div class="right-sidebar col-xs-12 col-sm-12 col-md-3 col-lg-3">
    @if($suggestions->count())
        @foreach($suggestions as $user)
            <div class="suggestions">
                <div class="header">
                    Friend Suggestions
                </div>

                <div class="users">
                    <div class="img col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <div class="image-holder">
                            <img src="/storage/{{$user->image_uri}}" alt="{{$user->name}}">
                        </div>
                    </div>
                    <div class="name col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <a href="/profile/{{$user->id}}">{{$user->first_name }} {{$user->last_name}}</a>
                    </div>
                </div>
            </div>

        @endforeach
    @endif

</div>
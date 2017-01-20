<div class="left-sidebar col-xs-12 col-sm-12 col-md-2 col-lg-2">
    <div class="user-box">
        <div class="dp col-xs-3 col-sm-3 col-md-3 col-lg-2">
            <a href="/profile/{{Auth::user()->id}}">
                <div class="image-holder">
                    <img src="/storage/{{Auth::user()->image_uri}}" alt="">
                </div>
            </a>
        </div>

        <div class="details col-xs-9 col-sm-9 col-md-9 col-lg-10">
            <a class="name" href="/profile/{{Auth::user()->id}}">
                {{Auth::user()->first_name .' '. Auth::user()->last_name}}
            </a>
        </div>
    </div>

    <div class="links">
        <ul class="nav nav-stacked">
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation" @if(Request::path() === '/') class="active" @endif><a href="/">
                        <span class="glyphicon glyphicon-picture" aria-hidden="true"> </span> News Feed</a>
                </li>

                <li role="presentation" @if(Request::path() === 'classes/all') class="active" @endif><a href="/classes/all">
                        <span class="glyphicon glyphicon-book" aria-hidden="true"> </span> Classes</a>
                </li>

                <li role="presentation" @if(Request::path() === 'societies/all') class="active" @endif><a href="/societies/all">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"> </span> Societies</a>
                </li>
            </ul>
        </ul>
    </div>
</div>
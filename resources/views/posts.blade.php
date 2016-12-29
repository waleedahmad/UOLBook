<div class="posts">
    @if(count($posts))
        @foreach($posts as $post)
            <div class="post-wrap">
                @if(Auth::check())
                    @if(Auth::user()->id == $post->user_id)
                        <div class="dropdown post-dropdown">
                            <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                <li><a href="#">Delete post</a></li>
                                <li><a href="#">Edit post</a></li>
                            </ul>
                        </div>
                    @endif
                @endif

                <div class="post" data-post-id="{{$post->id}}">
                    <div class="col-xs-1 img-holder">
                        <img alt="profile picture" class="user-img" src="{{$post->user->image_uri}}">
                    </div>

                    <div class="col-xs-11 post-content">
                        <div class="name">
                            <a href="/profile/{{$post->user->id}}">
                                {{$post->user->first_name . ' ' . $post->user->last_name}}
                            </a>
                        </div>
                        
                        <div class="link">
                            <a href="/post/{{$post->id}}">{{$post->created_at->diffForHumans()}}</a>
                        </div>

                        @if($post->type === 'text')
                            <div class="text">
                                {{$post->post_text}}
                            </div>
                        @elseif($post->type === 'photo')
                            <div class="text">
                                {{$post->post_text}}
                            </div>

                            <div class="photo">
                                <img src="/storage/{{$post->photo->image_uri}}" alt="">
                            </div>
                        @elseif($post->type === 'video')
                            <div class="text">
                                {{$post->post_text}}
                            </div>

                            <div class="video">
                                <video controls>
                                    <source src="/storage/{{$post->video->video_uri}}" type="video/mp4">
                                </video>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
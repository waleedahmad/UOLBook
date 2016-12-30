<div class="posts">
    @if(count($posts))
        @foreach($posts as $post)
            <div class="post-wrap" data-post-id="{{$post->id}}">
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


                <div class="comments">
                    @if(Auth::check())
                        <div class="comment-box">
                            <div class="comment-box col-xs-12">
                                <img alt="profile picture" class="col-xs-1" src="{{Auth::user()->image_uri}}">
                                <input class="col-xs-11 comment-holder" data-post-id="{{$post->id}}" name="post_text" placeholder="Comment">
                            </div>
                        </div>
                    @endif

                    <div class="comments-wrapper">
                        @foreach($post->firstTwoComments as $comment)
                            <div class="comment">
                                <div class="col-xs-12">
                                    <img alt="profile picture" class="dp col-xs-1" src="{{$comment->user->image_uri}}">
                                    <div class="text col-xs-11">
                                        <a href="/profile/{{$comment->user->id}}}">{{$comment->user->first_name . ' ' . $comment->user->last_name}} </a>
                                        {{$comment->comment}}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if(count($post->comments) > 2)
                            <a class="more-comment" href="/post/{{$post->id}}">More comments</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
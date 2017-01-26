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
                                <li><a href="#" class="delete-post" data-id="{{$post->id}}">Delete post</a></li>
                                <li><a href="#" class="edit-post" data-id="{{$post->id}}">Edit post</a></li>
                            </ul>
                        </div>
                    @endif
                @endif

                <div class="post" data-post-id="{{$post->id}}">
                    <div class="col-xs-1">
                        <div class="image-holder">
                            <img alt="profile picture" class="user-img" src="/storage/{{$post->user->image_uri}}">
                        </div>
                    </div>

                    <div class="col-xs-11 post-content">
                        <div class="name">
                            <a href="/profile/{{$post->user->id}}">
                                {{$post->user->first_name . ' ' . $post->user->last_name}}
                            </a>

                            @if($post->source === 'society' && !isset($society))
                                posted in

                                <a href="/society/{{$post->society_id}}">{{$post->societyName()}}</a>
                            @endif
                        </div>
                        
                        <div class="link">
                            <a href="/post/{{$post->id}}">{{$post->created_at->diffForHumans()}}</a>
                        </div>

                        <div class="text">
                            {{$post->post_text}}
                        </div>

                        @if($post->type === 'photo')
                            <div class="photo">
                                <img src="/storage/{{$post->photo->image_uri}}" alt="">
                            </div>
                        @elseif($post->type === 'video')
                            <div class="video">
                                <video controls>
                                    <source src="/storage/{{$post->video->video_uri}}" type="video/mp4">
                                </video>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="actions">
                    <span class="post-like @if($post->isLikedByAuthUser()) liked @endif" data-post-id="{{$post->id}}" >
                        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Like
                        <span class="post-like-count">({{$post->getLikesCount()}})</span>
                    </span>
                </div>


                <div class="comments">
                    @if(Auth::check())
                        <div class="comment-box col-xs-12">
                            <div class="col-xs-1">
                                <div class="image-holder">
                                    <img alt="profile picture"  src="/storage/{{Auth::user()->image_uri}}">
                                </div>
                            </div>

                            <input class="col-xs-11 comment-holder" data-post-id="{{$post->id}}" placeholder="Comment">
                        </div>
                    @endif

                    <div class="comments-wrapper">
                        @foreach($post->firstTwoComments as $comment)
                            <div class="comment" data-comment-id="{{$comment->id}}">

                                <div class="dropdown comment-dropdown">
                                    <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                        <li><a href="#" class="delete-comment" data-id="{{$comment->id}}">Delete comment</a></li>
                                        <li><a href="#" class="edit-comment" data-id="{{$comment->id}}">Edit comment</a></li>
                                    </ul>
                                </div>

                                <div class="col-xs-12">

                                    <div class="col-xs-1">
                                        <div class="image-holder">
                                            <img alt="profile picture"src="/storage/{{$comment->user->image_uri}}">
                                        </div>
                                    </div>

                                    <div class="text col-xs-11">
                                        <a href="/profile/{{$comment->user->id}}">{{$comment->user->first_name . ' ' . $comment->user->last_name}}</a>
                                        <span class="comment-text">
                                            {{$comment->comment}}
                                        </span>
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

        <div>
            {{$posts->links()}}
        </div>
    @endif
</div>
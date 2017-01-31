<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 messages-sidebar">
    <div class="title">
        <a href="/messages/all">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>Messages
        </a>
        <span class="glyphicon glyphicon-pencil pull-right new-message" aria-hidden="true"></span>
    </div>

    <div class="conversations">
        @if(isset($new_user))
            <a href="#">
                <div class="message-row active">
                    <div class="col-xs-2">
                        <div class="image-holder">
                            <img src="/storage/{{$new_user->image_uri}}" alt="">
                        </div>
                    </div>

                    <div class="col-xs-10">
                        <div class="name">
                            New message <b>{{$new_user->first_name}} {{$new_user->last_name}}</b>
                        </div>

                        <div class="message">

                        </div>
                    </div>
                </div>
            </a>
        @endif
        @foreach($conversations as $conversation)
            <a href="/message/{{$conversation->id}}">
                <div class="message-row @if(Request::path() === 'message/'.$conversation->id) active @endif @if(!$conversation->read) unread @endif">
                    <div class="col-xs-2">
                        <div class="image-holder">
                            <img src="/storage/{{$conversation->friend->image_uri}}" alt="">
                        </div>
                    </div>

                    <div class="col-xs-10">
                        <div class="name">
                            {{$conversation->friend->first_name}} {{$conversation->friend->last_name}}
                        </div>

                        <div class="message">
                            {{substr($conversation->getFirstMessage[0]->message, 0, 30)}}
                        </div>
                    </div>

                    <span class="glyphicon glyphicon-trash pull-right remove-conversation" data-conversation-id="{{$conversation->id}}"></span>
                </div>
            </a>
        @endforeach
    </div>
</div>
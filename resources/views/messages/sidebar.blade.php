<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 messages-sidebar">
    <div class="title">
        Messages <span class="glyphicon glyphicon-pencil pull-right new-message" aria-hidden="true"></span>
    </div>

    <div class="conversations">
        @foreach($conversations as $conversation)
            {{$conversation}}
        @endforeach
    </div>
</div>
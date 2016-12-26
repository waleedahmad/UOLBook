$(".create-post .post-now").on('click', function(e){
    var text = $("#post-text").val(),
        token = $("meta[name=token]").attr('content');

    if(text.length > 0){
        $.ajax({
            type : 'POST',
            url : '/posts/text/create',
            data : {
                post_text : text,
                _token : token
            },
            success : function(res){
                if(res.created){
                    $("#post-text").val('');
                    var $postDOM = generatePOSTDOM(res.id, text, res.name, res.image_uri);
                    $(".posts").prepend($postDOM);
                }
            }
        });
    }
});

function generatePOSTDOM(id, text, name, image_uri){
    return `
        <div class="post" data-post-id="${id}">
            <div class="col-xs-1 img-holder">
                <img alt="profile picture" class="user-img" src="${image_uri}">
            </div>
    
            <div class="col-xs-11 post-content">
                <div class="name">
                    <a href="/profile/{{$post->user->id}}">
                        ${name}
                    </a>
                </div>
    
                <div class="text">
                    ${text}
                </div>
            </div>
        </div>`;
}

$("#status-post").on('click', function(e){
    e.preventDefault();
    console.log("Clicked");
    $("#new_status .file-holder").css({"display" : "block"});
});

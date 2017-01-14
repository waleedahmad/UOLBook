/**
 * Create new comment
 */
$('.comment-holder').on('keypress', initComments);

function initComments(e){
    if(e.which === 13){
        let _this = $(this),
            comment = $(this).val(),
            post_id = $(this).attr('data-post-id');

        if(comment.length){
            $.ajax({
                type : 'POST',
                url : '/comment/create',
                data : {
                    _token : token,
                    comment : comment,
                    post_id : post_id
                },
                success: function(res){

                    if(res.created === 'true'){
                        $(_this).val('');
                        let $commentDOM = generateCommentDOM(res.id ,res.image_uri, comment, res.name, res.user_id);
                        $(_this).parents('.comments').children('.comments-wrapper').prepend($commentDOM);
                        registerCommentEventHandlers();
                        registerCommentEditEventHanlders();
                    }
                }
            });
        }
    }
}

$('.delete-comment').on('click', initDeleteComment);

function initDeleteComment(e){
    e.preventDefault();
    let id = $(this).attr('data-id'),
        $comment = $('[data-comment-id="'+id+'"]'),
        token = $("meta[name=token]").attr('content');

    bootbox.confirm({
        message: "Are you sure you want to delete this comment?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/comment/delete',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.deleted === true){
                            $($comment).slideUp(function(){
                                $(this).remove();
                            });

                            toastr.success("Comment removed!");
                        }
                    }
                })

            }
        }
    });
}

$('.edit-comment').on('click', initEditComment);

function initEditComment(e) {
    e.preventDefault();
    let id = $(this).attr('data-id'),
        $comment = $('[data-comment-id="' + id + '"]'),
        text = $.trim($($comment).find('.col-xs-12').find('.text').find('.comment-text').text()),
        token = $("meta[name=token]").attr('content');

    $('.edit-comment-text').remove();
    $('.comment').find('.text').show();
    $($comment).find('.col-xs-12').find('.text').hide();
    $($comment).find('.col-xs-12').append(generateEditCommentDOM(id, text));
    registerCommentEditEventHanlders();
}

$('.edit-comment-text').keyup(editComment);

function editComment(e){
    let id = $(this).attr('data-comment-id'),
        token = $("meta[name=token]").attr('content'),
        text = $.trim($(this).val()),
        _this = this;


    if(e.which === 27){
        $(this).siblings('.text').show();
        $(this).remove();
    }

    if(e.which === 13){
        if($(this).val().length){
            $.ajax({
                type: 'POST',
                url: '/comment/update',
                data : {
                    _token : token,
                    id : id,
                    text : text
                },
                success : function(res){
                    if(res.updated){
                        $(_this).siblings('.text').find('.comment-text').text(text);
                        $(_this).siblings('.text').show();
                        $(_this).remove();
                    }
                }
            });
        }else{
            $(this).attr('placeholder', 'Comment required..');
        }
    }
}

/**
 * Rebind comment holder events
 */
function registerCommentEventHandlers(){
    $('.comment-holder').off('keypress');
    $('.comment-holder').on('keypress', initComments);

    $('.delete-comment').off('click', initDeleteComment);
    $('.delete-comment').on('click', initDeleteComment);
    $('.edit-comment').off('click', initEditComment);
    $('.edit-comment').on('click', initEditComment);

}

function registerCommentEditEventHanlders(){
    $('.edit-comment-text').off('keyup', editComment);
    $('.edit-comment-text').keyup(editComment);
}

function generateCommentDOM(id,image_uri, comment, name, user_id){
    return `<div class="comment" data-comment-id="${id}">
                
                <div class="dropdown comment-dropdown">
                    <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                        <li><a href="#" class="delete-comment" data-id="${id}">Delete comment</a></li>
                        <li><a href="#" class="edit-comment" data-id="${id}">Edit comment</a></li>
                    </ul>
                </div>


                <div class="col-xs-12">
                    <img alt="profile picture" class="dp col-xs-1" src="${image_uri}">
                    
                    
                    <div class="text col-xs-11">
                        <a href="/profile/${user_id}">${name}</a>
                        <span class="comment-text">
                            ${comment}
                        </span>
                    </div>
                </div>
            </div>`;
}

function generateEditCommentDOM(id, comment){
    return `<input class="col-xs-11 edit-comment-text" data-comment-id="${id}" value="${comment}" placeholder="Comment">`;
}


/**
 * Delete discussions
 */
$('.delete-discussion').on('click', initDeleteDiscussion);

function initDeleteDiscussion(e){
    e.preventDefault();
    let id = $(this).attr('data-id'),
        view = $(this).attr('data-view'),
        class_id = $(this).attr('data-class-id'),
        token = $("meta[name=token]").attr('content'),
        _this = this;

    bootbox.confirm({
        message: "Are you sure you want to delete this discussion?",
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
                    type : 'DELETE',
                    url : `/class/${class_id}/discussion/delete`,
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.deleted === true){
                            if(view === 'main'){
                                $(_this).parents('.discussion').slideUp(function(){
                                    $(this).remove();
                                });

                                toastr.success("Discussion removed!");
                            }

                            if(view === 'discussion'){
                                window.location = `/class/${class_id}`;
                            }
                        }
                    }
                })

            }
        }
    });
}

/**
 * Delete reply
 */
$('.delete-reply').on('click', initDeleteDiscussionReply);

function initDeleteDiscussionReply(e){
    e.preventDefault();
    let id = $(this).attr('data-id'),
        class_id = $(this).attr('data-class-id'),
        token = $("meta[name=token]").attr('content'),
        _this = this;

    bootbox.confirm({
        message: "Are you sure you want to delete discussion reply?",
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
                    type : 'DELETE',
                    url : `/class/${class_id}/discussion/reply/delete`,
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.deleted === true){
                            $(_this).parents('.reply').slideUp(function(){
                                $(this).remove();
                            });
                        }
                    }
                })

            }
        }
    });
}

/**
 * Edit reply
 */
$('.edit-reply').on('click', initEditReply);

function initEditReply(e){
    e.preventDefault();
    let id = $(this).attr('data-id'),
        class_id = $(this).attr('data-class-id'),
        token = $("meta[name=token]").attr('content'),
        $reply = $('[data-reply-id="'+id+'"]'),
        text = $.trim($($reply).find('.text').text());
    console.log($reply);

    bootbox.prompt({
        title: "Edit Reply ",
        inputType : 'textarea',
        value : text,
        callback: function (result) {
            if(result){
                if(result.length){
                    $.ajax({
                        type : 'PUT',
                        url : `/class/${class_id}/discussion/reply/edit`,
                        data : {
                            id : id,
                            text : result,
                            _token : token
                        },
                        success : function(res){
                            if(res.updated === true){
                                $($reply).find('.text').text(result);
                            }
                        }
                    });
                }
            }
        }
    });
}

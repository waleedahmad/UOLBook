if($(".messages").length){
    $("#chat-message[data-type='message']").focus();
    $("#chat-message[data-type='new_message']").focus();


    if($('.thread').length){
        $('.thread').scrollTop($('.thread')[0].scrollHeight);
    }



    var $project = $('#friends');

    var friends = [];

    /**
     * Setup auto complete for new message input field
     */
    $.ajax({
        type : 'GET',
        url : '/user/friends',
        success : function(res){
            friends = res;
            console.log(friends);
            $project.autocomplete({
                minLength: 0,
                source: friends,
                focus: function( event, ui ) {
                    $project.val( ui.item.label );
                    return false;
                },
                select: function( event, ui ) {
                    handleConversationRequest(ui);
                }
            });

            $project.data( "ui-autocomplete" )._renderItem = function( ul, item ) {

                var $li = $('<li>'),
                    $li_a = $('<a class="friend-item">'),
                    $img_holder = $('<div>'),
                    $img = $('<img>'),
                    $pic = $('<div>'),
                    $name = $('<div>');

                $img.attr({
                    src: item.icon,
                    alt: item.label
                });

                $img_holder.addClass('img-holder').append($img);

                $pic.addClass('col-xs-1').append($img_holder);
                $name.addClass('col-xs-11').append(item.label);

                $li.append($li_a);

                $li.find('.friend-item').append($pic).append($name);
                $li.attr('data-value', item.label);

                return $li.appendTo(ul);
            };
        }
    });

    /**
     * Show users input field
     */
    $(".new-message").on('click', function(e){
        $('.messages').find('#friends').show().focus();
        $('.current-user').hide();
    });

    /**
     * New conversation
     * @param ui
     */
    function handleConversationRequest(ui){
        let id = ui.item.id;

        $.ajax({
            type : 'GET',
            url : '/messages/conversation/exist',
            data : {
                id : id
            },
            success : function(res){
                if(res.exists){
                    window.location = '/message/'+res.id;
                }else{
                    window.location = '/message/new/'+ui.item.id
                }
            }
        })
    }

    /**
     * Send chat messages
     */
    $('#chat-message').keydown(function(e){
        let type = $(this).attr('data-type'),
            user = $(this).attr('data-user-id'),
            message = $(this).val();
        if(e.which === 13){
            e.preventDefault();
            if(message.length > 0){
                if(type === 'new_message'){
                    createNewThreadAndRedirect(user, message);
                }
                if(type === 'message'){
                    let con_id = $(this).attr('data-conversation-id');
                    postMessage(user, message, con_id);
                }
            }else{
                showMessageModel('Chat message field is empty', 'Chat messages appears to be blank. Please write something before sending a message.');
            }
        }
    });

    /**
     * Creates a new message thread and redirect to that thread
     * @param user
     * @param message
     */
    function createNewThreadAndRedirect(user, message){
        $.ajax({
            type : 'GET',
            url : '/messages/conversation/create',
            data : {
                user_id : user,
                message : message
            },
            success : function(res){
                if(res.created){
                    window.location = '/message/'+res.id;
                }
            }
        });
    }

    /**
     * Post message
     * @param user
     * @param message
     * @param con_id
     */
    function postMessage(user, message, con_id){
        console.log(user, message, con_id);

        $.ajax({
            type : 'POST',
            url : '/message/new',
            data : {
                user_id : user,
                message : message,
                con_id : con_id,
                _token : token
            },
            success : function(res){
                if(res.created){
                    $('.messages-content').find('.thread').append(getMessageDOM(message));
                    $('.thread').scrollTop($('.thread')[0].scrollHeight);
                    clearChatMessage();
                }
            }
        })
    }

    /**
     * Returns new message DOM
     * @param message
     * @returns {string}
     */
    function getMessageDOM(message){
        return `
            <div class="your-message">
                ${message}
            </div>`;
    }

    /**
     * Clear chat message
     */
    function clearChatMessage(){
        $("#chat-message").val('');
    }

    /**
     * Remove conversations
     */
    $('.remove-conversation').on('click', function(e){
        e.preventDefault();
        let id = $(this).attr('data-conversation-id');

        bootbox.confirm({
            message: "Are you sure you want to delete this post?",
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
                        url : '/messages/conversation/',
                        data : {
                            _token : token,
                            id : id
                        },
                        success: function(res){
                            if(res.deleted){
                                window.location = '/messages/all';
                            }
                        }

                    })
                }
            }
        });
    });
}







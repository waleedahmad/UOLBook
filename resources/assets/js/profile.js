/**
 * Add friend
 */
$('.add-friend').on('click', initAddFriend);

function initAddFriend(e){
    var user_id = $(this).attr('data-user-id'),
        _this = this;

    $.ajax({
        type : 'POST',
        url : '/user/friend/request',
        data : {
            _token : token,
            user_id : user_id
        },
        success : function(res){
            if(res.created === 'true'){
                $(_this).removeClass('add-friend').addClass('remove-request').text('Cancel Request');
                $(_this).off('click', initAddFriend);
                $(_this).on('click', initRemoveRequest);
            }
        }
    })
}

/**
 * Remove friend request
 */
$('.remove-request').on('click', initRemoveRequest);


function initRemoveRequest(e){
    var user_id = $(this).attr('data-user-id'),
        _this = this;

    $.ajax({
        type : 'POST',
        url : '/user/request/remove',
        data : {
            _token : token,
            user_id : user_id
        },
        success : function(res){
            if(res.removed === 'true'){
                $(_this).removeClass('remove-request').addClass('add-friend').text('Add Friend');
                $(_this).off('click', initRemoveRequest);
                $(_this).on('click', initAddFriend);
            }
        }
    });
}

/**
 * Remove friend
 */
$('.remove-friend').on('click', function(e){
    e.preventDefault();

    var user_id = $(this).attr('data-user-id'),
        _this = this;

    $.ajax({
        type : 'POST',
        url : '/user/friend/remove',
        data : {
            _token : token,
            user_id : user_id
        },
        success : function(res){
            if(res.removed === 'true'){
                location.reload();
            }
        }
    });
});

/**
 * Accept friend request
 */
$('.accept-request').on('click', function(e){
    e.stopPropagation();
    var request_id = $(this).attr('data-request-id'),
        _this = this;

    $.ajax({
        type : 'POST',
        url : '/user/friend/request/accept',
        data : {
            _token : token,
            request_id : request_id
        },
        success : function(res){
            if(res.accepted === 'true'){
                $(_this).parents('.request').html('Friend Request Accepted').css({'padding' : '20px'});
            }
        }
    });
});

/**
 * Delete request
 */
$('.delete-request').on('click', function(e){
    e.stopPropagation();
    var request_id = $(this).attr('data-request-id'),
        _this = this;

    $.ajax({
        type : 'POST',
        url : '/user/friend/request/remove',
        data : {
            _token : token,
            request_id : request_id
        },
        success : function(res){
            if(res.removed === 'true'){
                $(_this).parents('.request').slideUp('slow').remove();
            }
        }
    });
});
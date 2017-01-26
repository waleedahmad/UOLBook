$('.approve-society').on('click', initApproveSociety);

function initApproveSociety(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initApproveSociety);

    bootbox.confirm({
        message: "Are you sure you want to approve this society?",
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
            $(_this).on('click', initAcceptClassJoinRequest);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/admin/society/approve',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.approved){
                            $(_this).parents('.society').slideUp(function(){
                                $(this).remove();
                            });
                        }
                    }
                });

            }

            $(_this).on('click', initApproveSociety);
        }
    });
}

$('.disapprove-society').on('click', initDisapproveSociety);

function initDisapproveSociety(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initDisapproveSociety);

    bootbox.confirm({
        message: "Are you sure you want to disapprove this society?",
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
            $(_this).on('click', initDisapproveSociety);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/admin/society/disapprove',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.disapproved){
                            $(_this).parents('.society').slideUp(function(){
                                $(this).remove();
                            });
                        }
                    }
                });

            }

            $(_this).on('click', initApproveSociety);
        }
    });
}

/**
 * Request Call JOIN
 */
$('.join-society').on('click', initJoinSociety);

function initJoinSociety(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initJoinSociety);

    $.ajax({
        type : 'POST',
        url : '/society/request/join',
        data : {
            id : id,
            _token : token
        },
        success : function(res){
            console.log(res);
            if(res.created){
                $(_this).removeClass('.join-society').addClass('.cancel-society-request').on('click', initCancelSocietyJoinRequest).text('Cancel Request');
            }
        }
    });
}

/**
 * CANCEL JOIN Request
 */
$('.cancel-society-request').on('click', initCancelSocietyJoinRequest);

function initCancelSocietyJoinRequest(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initJoinClassRequest);

    $.ajax({
        type : 'POST',
        url : '/society/request/cancel',
        data : {
            id : id,
            _token : token
        },
        success : function(res){
            console.log(res);
            if(res.canceled){
                $(_this).removeClass('.cancel-society-request').addClass('.join-society').on('click', initJoinSociety).text('Join Society');
            }
        }
    });
}

/**
 * ACCEPT JOIN Request
 */
$('.soc-acp-req').on('click', initAcceptSocietyJoinRequest);

function initAcceptSocietyJoinRequest(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initAcceptClassJoinRequest);

    bootbox.confirm({
        message: "Are you sure you want to approve this user?",
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

            $(_this).on('click', initAcceptClassJoinRequest);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/society/request/accept',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.accepted){
                            $(_this).parents('.request').slideUp(function(){
                                $(this).remove();
                            });
                        }
                    }
                });
            }
        }
    });
}

/**
 * Disapprove JOIN Request
 */
$('.soc-dis-req').on('click', initDisapproveSocietyJoinRequests);

function initDisapproveSocietyJoinRequests(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initDisapproveStudentRequest);

    bootbox.confirm({
        message: "Are you sure you want to disapprove this user?",
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

            $(_this).on('click', initDisapproveSocietyJoinRequests);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/society/request/disapprove',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.disapproved){
                            $(_this).parents('.request').slideUp(function(){
                                $(this).remove();
                            });
                        }
                    }
                });
            }
        }
    });
}

$('.remove-soc-member').on('click', initRemoveSocietyMember);

function initRemoveSocietyMember(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initDisapproveStudentRequest);

    bootbox.confirm({
        message: "Are you sure you want to remove this user?",
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

            $(_this).on('click', initDisapproveSocietyJoinRequests);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/society/member/remove',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.removed){
                            $(_this).parents('.request').slideUp(function(){
                                $(this).remove();
                            });
                        }
                    }
                });
            }
        }
    });
}

$('.leave-society').on('click', initLeaveSociety);

function initLeaveSociety(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initLeaveSociety);

    bootbox.confirm({
        message: "Are you sure you want to leave this society?",
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

            $(_this).on('click', initLeaveSociety);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/society/member/leave',
                    data : {
                        society_id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.left){
                            window.location.reload();
                        }
                    }
                });
            }
        }
    });
}

$('.delete-society').on('click', initDeleteSociety);

function initDeleteSociety(e){
    let id = $(this).attr('data-id'),
        role = $(this).attr('data-role'),
        _this = $(this);

    let url = (role === 'admin') ? '/admin/society/delete' : '/society/delete';

    $(this).off('click', initDeleteSociety);

    bootbox.confirm({
        message: "Are you sure you want to delete this society?",
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

            $(_this).on('click', initDeleteSociety);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : url,
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(role === 'admin'){
                            if(res.deleted){
                                $(_this).parents('.society').slideUp(function(e){
                                    $(this).remove();
                                });
                            }
                        }

                        if(role === 'user'){
                            if(res.deleted){
                                window.location = '/societies/all';
                            }
                        }
                    }
                });
            }
        }
    });
}
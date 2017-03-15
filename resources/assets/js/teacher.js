/**
 * Request Call JOIN
 */
$('.student-join-request').on('click', initJoinClassRequest);

function initJoinClassRequest(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initJoinClassRequest);

    $.ajax({
        type : 'POST',
        url : '/class/request/join',
        data : {
            id : id,
            _token : token
        },
        success : function(res){
            console.log(res);
            if(res.created){
                $(_this).removeClass('.student-join-request').addClass('.student-cancel-request').on('click', initCancelClassRequest).text('Cancel Request');
            }
        }
    });
}

/**
 * CANCEL JOIN Request
 */
$('.student-cancel-request').on('click', initCancelClassRequest);

function initCancelClassRequest(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initJoinClassRequest);

    $.ajax({
        type : 'POST',
        url : '/class/request/cancel',
        data : {
            id : id,
            _token : token
        },
        success : function(res){
            console.log(res);
            if(res.deleted){
                $(_this).removeClass('.student-cancel-request').addClass('.student-join-request').on('click', initJoinClassRequest).text('Join Class');
            }
        }
    });
}

/**
 * ACCEPT JOIN Request
 */
$('.accept-join-request').on('click', initAcceptClassJoinRequest);

function initAcceptClassJoinRequest(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initAcceptClassJoinRequest);

    bootbox.confirm({
        message: "Are you sure you want to approve this student?",
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
                    url : '/class/request/accept',
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
$('.disapprove-join-request').on('click', initDisapproveStudentRequest);

function initDisapproveStudentRequest(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initDisapproveStudentRequest);

    bootbox.confirm({
        message: "Are you sure you want to disapprove this student?",
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

            $(_this).on('click', initDisapproveStudentRequest);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/class/request/disapprove',
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

/**
 * Remove Enrolled Student
 */
$('.remove-student').on('click', initDeleteStudent);

function initDeleteStudent(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initDeleteStudent);

    bootbox.confirm({
        message: "Are you sure you want to delete this student?",
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

            $(_this).on('click', initDeleteStudent);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/class/remove/student',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.removed){
                            $(_this).parents('.student').slideUp(function(){
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
 * Remove file upload
 */
$('.remove-upload').on('click', initRemoveFileUpload);

function initRemoveFileUpload(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initDeleteStudent);

    bootbox.confirm({
        message: "Are you sure you want to remove this file?",
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

            $(_this).on('click', initDeleteStudent);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/class/upload/remove',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.deleted){
                            $(_this).parents('.upload').slideUp(function(){
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
 * Remove announcement
 */
$('.remove-announcement').on('click', initRemoveAnnouncement);

function initRemoveAnnouncement(e){
    let id = $(this).attr('data-id'),
        _this = $(this);

    $(this).off('click', initDeleteStudent);

    bootbox.confirm({
        message: "Are you sure you want to remove this announcement?",
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

            $(_this).on('click', initDeleteStudent);
            if(result){
                $.ajax({
                    type : 'POST',
                    url : '/class/announcement/remove',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.deleted){
                            $(_this).parents('.announcement').slideUp(function(){
                                $(this).remove();
                            });
                        }
                    }
                });
            }
        }
    });
}

if($("#subject-name").length){
    $("#subject-name").select2();
}

$("#subject-name").on('change', function(e){
    let course_id = $(this).val();
    let options = $("#subject-sections");
   if(course_id.length){
       $.ajax({
           type : 'GET',
           url : '/course/sections',
           data : {
               id : course_id,
               _token : token
           },
           success : function(res){
               console.log(res.sections);
               console.log(res.sections.length);
               if(res.sections.length){
                   console.log(res.sections);
                   $(options).empty();
                   $(options).removeAttr('disabled');
                   options.append($("<option />").val("").text("Select Section"));
                   $.each(res.sections, function() {
                       options.append($("<option />").val(this).text(this));
                   });
               }else{
                   $(options).attr('disabled', 'disabled');
                   $(options).empty();
                   options.append($("<option />").val("").text("Select Section"));
                   bootbox.alert("No sections available for this course!");
               }
           }
       });
   }else{
       $(options).attr('disabled', 'disabled');
       $(options).empty();
       options.append($("<option />").val("").text("Select Section"));
   }
});

$("#find-class").on('click', function(e){
    let secret = $('#course-secret').val();

    if(secret.length){
        $.ajax({
            type : 'POST',
            url : '/course/find',
            data : {
                code : secret,
                _token : token
            },
            success : function(res){
                console.log(res);
                if(res.found){
                    let code = res.class_id;
                    $.ajax({
                        type : 'GET',
                        url : '/course/count',
                        success : function(res){
                            if(res.allowed){
                                window.location = '/class/'+code;
                            }else{
                                bootbox.alert("You're not allowed join more than 6 classes.");
                            }
                        }
                    });



                }else{
                    bootbox.alert("We are unable to find your class, please contact your respective teacher.");
                }
            }
        });
    }else{
        bootbox.alert("Please enter your course secret code provided by your teacher ");
    }
});

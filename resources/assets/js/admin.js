$('.approve-user').on('click', function(e){
    var id = $(this).attr('data-id'),
        token = $("meta[name=token]").attr('content'),
        _this = this;

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
            console.log(result, id);
            if(result){

                $.ajax({
                    type : 'POST',
                    url : '/admin/approve',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.approved === true){
                            $(_this).parents('.request').slideUp(function(){
                                $(this).remove();
                            });
                            toastr.success("User approved!");
                        }
                    }
                })

            }
        }
    });


});

$('.disapprove-user').on('click', function(e){
    var id = $(this).attr('data-id'),
        token = $("meta[name=token]").attr('content'),
        _this = this;

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
            console.log(result, id);
            if(result){

                $.ajax({
                    type : 'POST',
                    url : '/admin/disapprove',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.disapproved === true){
                            $(_this).parents('.request').slideUp(function(){
                                $(this).remove();
                            });
                            toastr.success("User disapproved!");
                        }
                    }
                })

            }
        }
    });
});


$('.delete-class').on('click', function(e){
    var id = $(this).attr('data-id'),
        token = $("meta[name=token]").attr('content'),
        _this = this;

    bootbox.confirm({
        message: "Are you sure you want to delete this class?",
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
            console.log(result, id);
            if(result){

                $.ajax({
                    type : 'DELETE',
                    url : '/admin/class/delete',
                    data : {
                        id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.deleted === true){
                            $(_this).parents('.class').slideUp(function(){
                                $(this).remove();
                            });
                            toastr.success("Class disapproved!");
                        }
                    }
                })

            }
        }
    });
});


$('.delete-user').on('click', function(e){
    var id = $(this).attr('data-id'),
        token = $("meta[name=token]").attr('content'),
        _this = this;

    bootbox.confirm({
        message: "Are you sure you want to delete this user?",
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
            console.log(result, id);
            if(result){

                $.ajax({
                    type : 'DELETE',
                    url : '/admin/user/delete',
                    data : {
                        user_id : id,
                        _token : token
                    },
                    success : function(res){
                        if(res.deleted === true){
                            $(_this).parents('.user').slideUp(function(){
                                $(this).remove();
                            });

                            toastr.success("User Removed!");
                        }
                    }
                })

            }
        }
    });
});
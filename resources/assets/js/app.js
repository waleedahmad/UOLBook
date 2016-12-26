$('.approve-user').on('click', function(e){
    var id = $(this).attr('data-id'),
        token = $("meta[name=token]").attr('content'),
        _this = this;

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
            }
        }
    })
});


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

$(".create-post .post-now").on('click', function(e){
    var text = $("#post-text").val(),
        token = $("meta[name=token]").attr('content');

    console.log(text);
    if(text.length > 0){
        $.ajax({
            type : 'POST',
            url : '/posts/create',
            data : {
                post_text : text,
                _token : token
            },
            success : function(res){
                if(res.created){
                    $("#post-text").val('');
                }
            }
        });
    }
});
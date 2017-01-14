$('.notification').on('click', function(e){
    e.preventDefault();

    let id = $(this).attr('data-id'),
        token = $('meta[name="token"]').attr('content');

    $.ajax({
        type : 'POST',
        url : '/notification/read',
        data : {
            id : id,
            _token : token
        },
        success : function(res){
            if(res.read){
                window.location = res.redirect;
            }
        }
    })
});
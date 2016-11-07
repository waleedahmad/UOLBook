$('.approve-user').on('click', function(e){
    var id = $(this).attr('data-id');

    $.ajax({
        type : 'POST',
        url : '/admin/approve',
        data : {
            id : id
        },
        success : function(res){

        }
    })
});
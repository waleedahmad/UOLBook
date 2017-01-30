(function($){

    var $project = $('#friends');

    var friends = [];

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
                    handleConverstionRequestr(ui);
                }
            });

            $project.data( "ui-autocomplete" )._renderItem = function( ul, item ) {

                var $li = $('<li>'),
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

                $li.attr('data-value', item.label);
                $li.append($pic).append($name);

                return $li.appendTo(ul);
            };
        }
    });

})(jQuery);

$(".new-message").on('click', function(e){
    $('.messages').find('#friends').show().focus();
    $('.current-user').hide();
});

$('#friends').keydown(function(e){
    if(e.which === 27){
        $(this).hide();
        $('.current-user').show();
    }
});

function handleConverstionRequestr(ui){
    let id = ui.item.id;
    console.log(id);

    $.ajax({
        type : 'GET',
        url : '/messages/conversation',
        data : {
            id : id
        },
        success : function(res){
            window.location = '/messages/'+res.id;
        }
    })
}


let $message_modal = $('#message-modal'),
    $message_modal_title = $('#message-modal-title'),
    $message_modal_body = $('#message-modal-body');


toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

(function() {
    var dropdownMenu;

    $('.posts, .discussion, .replies').on('show.bs.dropdown', function(e) {

        dropdownMenu = $(e.target).find('.dropdown-menu');

        $('body').append(dropdownMenu.detach());

        var eOffset = $(e.target).offset();

        dropdownMenu.css({
            'display': 'block',
            'top': eOffset.top + $(e.target).outerHeight(),
            'left': eOffset.left-150,
            'width' : '100'
        });
    });

    $('.posts, .discussion, .replies').on('hide.bs.dropdown', function(e) {
        $(e.target).append(dropdownMenu.detach());
        dropdownMenu.hide();
    });
})();
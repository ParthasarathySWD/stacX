(function($) {

    $('.date-enrty1').datetextentry({
        field_order : 'MDY',
        separator   : '/',
        errorbox_x    : -100,
        errorbox_y    : 30
    });

     $('.date-enrty2').datetextentry({
        field_order : 'MDY',
        separator   : '/',
        errorbox_x    : -100,
        errorbox_y    : 30
    });
    $('.date-enrty3').datetextentry({
        field_order : 'MDY',
        separator   : '/',
        errorbox_x    : -100,
        errorbox_y    : 30
    });
    $('form').submit(function(e) {
        e.preventDefault();
    });

})(jQuery);
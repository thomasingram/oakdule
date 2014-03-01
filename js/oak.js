$(document).ready(function () {
    $('#list-form > input').hide();
});

$('#list-form').change(function (e) {
    var $target = $(e.target),
        data = $(this).serialize() + '&submit=',
        entry_tokens = $target.attr('name').split('-'),
        url = $(this).attr('action');
    
    $.post(url, data, function () {
        var number_of_entries = +$('#count' + entry_tokens[1]).text(),
            updated_number = $target.is(':checked') ? number_of_entries + 1 :
                    number_of_entries - 1;
        
        if (updated_number.toString().length < 2) {
            updated_number = '0' + updated_number;
        }
        
        $('#count' + entry_tokens[1]).text(updated_number).highlight();
    });
});

jQuery.fn.highlight = function () {
    $(this).each(function () {
        var el = $(this);
        // el.before('<div/>')
        // el.prev()
        
        // Look at book to see method chaining
        
        $('<div />').width('9px').height('9px').css({
                    'background-color': '#af2c32',
                    'opacity': '.7',
                    'position': 'absolute',
                    'top': el.offset().top,
                    'left': el.offset().left,
                    'z-index': '9999',
                    '-moz-border-radius': '9px',
                    '-webkit-border-radius': '9px',
                    'border-radius': '9px'
                }).appendTo('body').fadeOut(1000).queue(function () {
                    $(this).remove();
                });
    });
};
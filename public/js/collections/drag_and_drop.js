$(document).delegate(".add-playlist","click", function(){
    var thisF = $(this);

    var parent = thisF.parent();//.parent().parent().parent().find('section');
    thisF.remove();
    var dragAndDrop = $('#drag-and-drop');

    dragAndDrop.append(parent.html()+'<div class="drag-and-drop"></div>');

    var count = 0;

    $('.drag-and-drop').each(function(){
        count = count + 1;
        $(this).parent().find('.row').addClass('add_playlists_in_collection')
    });


    if(dragAndDrop.height() < count * 45) {

        dragAndDrop.css({
            'overflow-y' : 'scroll'
        });
    } else {
        dragAndDrop.css({
            'overflow-y' : 'none'
        });
    }
});
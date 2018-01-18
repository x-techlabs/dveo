$(document).ready(function () {

//    $('#playlists').droppable({
//        accept: 'div.section-video'
//
//    });
//
//    $(document).delegate(".section-video", "mouseover", function () {
//        $(".section-video").draggable({
//            helper: 'clone',
//            drag: function (event, ui) {
//                var drg = $(this);
//                drg.css({'position': 'absolute'});
//
//            }
//        });
//    });

    $(document).delegate(".add-tvweb-playlist", "click", function () {
        var thisF = $(this);

        var parent = thisF.parent().parent();//.parent().parent().parent().find('section');
        //parent.fadeOut();
        var dragAndDrop = $('#drag-and-drop');

        //console.log(parent.parent().parent().parent().find('section .remove'));
        dragAndDrop.append('<div class="drop-item ui-sortable-handle">' + parent.html() +
            '<button type="button" class="btn btn-danger remove"><span class="fui-trash"></span></button><script>$(".drop-item .remove").click(function() {'+

            'var video_time = $(this).prev().find("p:last").text();'+
            'var video_time_sec = hmsToSecondsOnly(video_time);'+
            'video_time_sec_trt = $("#add_playlist_trt").text();'+
            'video_time_sec_trt = hmsToSecondsOnly(video_time_sec_trt.substring(5));'+
            'var video_time_trt = secondsTimeSpanToHMS(video_time_sec_trt - video_time_sec);'+
            '$("#add_playlist_trt").empty();'+
            '$("#add_playlist_trt").append('+
                "'&nbsp;&nbsp;TRT&nbsp;<img src=/images/time_icon.png style=margin-top: -4px;> ' + video_time_trt);"+

            '$(this).parent().fadeOut().remove();' +
            '$(this).stopImmediatePropagation();' +

        '});</script></div>');



        // take duration from dropped element and add to TRT (Total Running Time) of Add Playlist or Edit Playlist pages
        var video_time = $(this).prev().find('p:last').text();
        var video_time_sec = hmsToSecondsOnly(video_time);
        var video_time_sec_trt;
        if ($('#add_playlist_trt').is(':empty')){
            video_time_sec_trt = 0;
        }else{
            video_time_sec_trt = $('#add_playlist_trt').text();
            video_time_sec_trt = hmsToSecondsOnly(video_time_sec_trt.substring(5));
        }

        var video_time_trt = secondsTimeSpanToHMS(video_time_sec_trt + video_time_sec);

        $('#add_playlist_trt').empty();
        $('#add_playlist_trt').append(
            '&nbsp;&nbsp;TRT&nbsp;<img src="/images/time_icon.png" style="margin-top: -4px;"> ' + video_time_trt);





        var count = 0;

        $('.drag-and-drop').each(function () {
            count = count + 1;
            $(this).parent().find('.row').addClass('add_videos_in_playlist')
        });

        if (count > 3) {
            dragAndDrop.css({
                'overflow-y': 'scroll'
            });
        } else {
            dragAndDrop.css({
                'overflow-y': 'none'
            });
        }
    });
});

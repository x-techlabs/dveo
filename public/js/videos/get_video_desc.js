ace = ace || {};

extend(ace, {
    vjs: null,

    getVideo: function (video_id) {

        $.ajax({
            url: ace.path('get_video_description'),
            type: "GET",
            async: true,

            data: {
                "video_id": video_id
            },

            dataType: "html",

            success: function (data) {
                if (!!ace.vjs){
                	ace.vjs.dispose();
                }

                $('.videoRight').remove();
                $('#videos-col').removeClass('col-md-12').addClass('col-md-6');
                $('.description').remove();
                $('#contnet-wrap').append(data);

                $('.search').animate({
                    'width': "192px"
                }, 200);

                $('.videoTtitle').css('maxWidth', '230px');

				$('.edit_video').addClass('move_edit_icon');
				$('.delete_video').addClass('move_delete_icon');
				$('.snapshot').addClass('move_snapshot');
				$('.playVideoInPopup').addClass('move_play_icon');
                // Changing columns
                $('.list_item').find('.col-md-2').removeClass('col-md-2').addClass('col-md-4');
                $('.list_item').find('.col-md-10').removeClass('col-md-10').addClass('col-md-8');

                ace.vjs = videojs('local');
            }
        });
    }
});

var getVideoById = {
    getVideoById: function (id) {
        $.ajax({
            url: ace.path('get_video_by_id'),
            type: "POST",
            data: {
                'id': id
            },
            dataType: "html",
            success: function (data) {
                $('.videoRight').remove();
                $('#videos-col').removeClass('col-md-12').addClass('col-md-6');
                $('.description').remove();
                $('#contnet-wrap').append(data);

                $('.search').animate({
                    'width': "192px"
                }, 200);

                // $('.videoTtitle').css('maxWidth', '300px');
                $('.videoTtitle').css({
                    'maxWidth': '305px',
                    'width': 'calc(100% - 60px)'
                });

                // Changing columns
                $('.list_item').find('.col-md-2').removeClass('col-md-2').addClass('col-md-4');
                $('.list_item').find('.col-md-10').removeClass('col-md-10').addClass('col-md-8');
				$('.edit_video').addClass('move_edit_icon');
				$('.delete_video').addClass('move_delete_icon');
				$('.snapshot').addClass('move_snapshot');
				$('.playVideoInPopup').addClass('move_play_icon');
            }
        });
    }
}

var deleteVideo = {
    deleteVideo: function (id) {
        $.ajax({
            url: ace.path('delete_video'),
            type: "POST",
            data: {
                'id': id
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    $('*[data-video_id="' + id + '"]').fadeOut();
                }
            }
        });
    }
}


$(document).ready(function () {
    $('.snapshot').click(function () {
        $('.list_item').removeClass('active_item');
        $(this).parent().toggleClass('active_item');
        
    })
    $('.section_video').click(function () {
        ace.getVideo($(this).data('video_id'));
    });

    $(document).on("click",".cancelEdit",function(event) {
        $('#videos-col').addClass('col-md-12').removeClass('col-md-6');
        $('.videoTtitle').attr('style', '');
        $('#upload').remove();
        // Changing columns
        $('.list_item').find('.col-md-4').removeClass('col-md-4').addClass('col-md-2');
        $('.list_item').find('.col-md-8').removeClass('col-md-8').addClass('col-md-10');

		$('.edit_video').removeClass('move_edit_icon');
		$('.delete_video').removeClass('move_delete_icon');
		$('.snapshot').removeClass('move_snapshot');
		$('.playVideoInPopup').removeClass('move_play_icon');
    });

    $('.edit_video').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        $('.section_video').removeClass('active_item');
        $(this).parent().toggleClass('active_item');
        var id = $(this).attr('id');
        getVideoById.getVideoById(id);
        return false;
    });

    $('.delete_video').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        if(confirm('are you sure?')) {
            deleteVideo.deleteVideo(id);
        }
        return false;
    });
    
});

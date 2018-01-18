var TvappAddPlaylist = {
    TvappAddToPlaylist: function(){
        var playlistId;
        var title = $('#title').val();
        var description = $('#description').val();
        var playlist_type = $('#playlist_type').val();
        var level = $('#playlist_level').val();
        var shelf = $('#shelf').val();
        var playlist_layout = $('#playlist_layout').val();
        var stream_url = $('#stream_url').val();
        var viewing = $('#viewing').val();
		var playlist_web_layout = $("#playlist_web_layout").val();
		var active_for_all_playlists = $(".active_for_all_playlists").val();
        if(active_for_all_playlists == 'yes'){
            var active_for_all_playlists_answer = 'yes';
            var playlist_category ="";
        }
        else{
			var data_value = $(".active_input_radio_btn").attr("data-value");
			if(data_value == "yes"){
				var playlist_category = $(".playlist_category_select").val();
			}
            var active_for_all_playlists_answer = 'no';
           // var playlist_category = $(".playlist_category_select").val();

        }
		
		
        console.log(ace.path('tvapp_add_to_playlist'));
        $.ajax({
            url: ace.path('tvapp_add_to_playlist'),
            type: "POST",
            data: {
                'title' : title,
                'description' : description,
                'level' : level,
                'shelf' : shelf,
                'layout' : playlist_layout,
                'stream_url' : stream_url,
                'viewing' : viewing,
                'playlist_category' : playlist_category,
				'active_for_all_playlists' : active_for_all_playlists,
				'web_layout':playlist_web_layout,
                'type':playlist_type

            },
            dataType: "json",
            success: function (data) {
                $('.edited').html('<span class="fui-check"></span> Playlist successfully added!');
                setInterval(function() { window.location.replace("tvapp_playlists"); }, 1000);
            }
        });
    }
}

var TvappEditPlaylist = {
	TvappEditPlaylist: function()
	{
	    var id = $('#parent_playlist_id').val();
	    var title = $('#title').val();
	    var description = $('#description').val();
	    var playlist_type = $('#playlist_type').val();
	    var playlist_level = $('#playlist_level').val();
        var playlist_layout = $('#playlist_layout').val();
        var playlist_web_layout = $('#playlist_web_layout').val();
        var stream_url = $('#stream_url').val();
        var viewing = $('#viewing').val();
        var platforms = $("#tvapp_edit_playlist").find('.js-select2-tags').val();
		var channel_id = $(".channel_id_input").val();
		var featured_image_url = "https://prolivestream.imgix.net/banners/channel_"+channel_id+"_tvapp_playlist_"+ id +".jpg";

	    $.ajax({
	        url: ace.path('tvapp_edit_playlist'),
	        type: "POST",
	        data: {
	            'id' : id,
	            'title' : title,
	            'description' : description,
	            'type' : playlist_type,
                'level': playlist_level,
                'layout' : playlist_layout,
                'web_layout' : playlist_web_layout,
                'stream_url' : stream_url,
                'viewing' : viewing,
				'featured_image_url' : featured_image_url,
                platforms: platforms
	        },
	        dataType: "json",
	        success: function (data)
	        {
	            window.location.replace("tvapp_playlists");
	        }
	    });
	}
}

function GetVideosByCollection(id)
{
    //alert(id);
    $.ajax({
        url: ace.path('get_videos_by_collections'),
        type: 'get',
        data: {
            'id': id
        },
        dataType: "json",
        success: function(data) {
            //alert(data.videos[0]);

            $('#modules').html('');
            $.each(data.videos, function(index, value) {
                htmlCode = '<section class="section-video drag ui-draggable ui-draggable-handle" style="cursor: pointer; border-bottom: 1px solid #ccc; margin-bottom: 10px;">' +
                '<div class="row center-block posRel" data-video_id="' + value.id + '">' +
                '<div class="col-md-4">' +
                '<img src="' + value.thumbnail_name + '" class="thumbnail_video">' +
                '</div>' +
                '<div class="col-md-7">' +
                '<p style="text-align: left; margin: 0; overflow: hidden;">' + value.title + '</p>' +
                '<p style="text-align: left; margin: 0; overflow: hidden;">' +
                '<img src="/images/time_icon.png" style="margin-top: -4px;"> ' + value.time +
                '</p>' +
                '</div>' +
                /* '<button class="btn btn-inverse add-video" title="Add video in playlist">+</button>' + */
                '<button type="0" video_id="' + value.id + '" class="btn btn-inverse add-video-new" title="Add video in playlist" style="width:24px;height:24px;padding:4px 4px;">&plus;</button>' +

                '</div>' +
                '</section>';
                $('#modules').append(htmlCode);
            });
        }
    });
}

$(document).ready(function(){
	 $(document).on("click",".radio_btn_for_show_select_parth",function(){
        var value = $(this).attr("data-value");

        $(this).addClass("active_input_radio_btn");
        if(value == "yes"){
            $("#dont_show_playlist_categories").removeClass("active_input_radio_btn");
        }else if(value =="no"){

            $("#show_playlist_categories").removeClass("active_input_radio_btn");
        }
    })
    $('#tvapp_add_to_playlist').submit(function(event){
        event.preventDefault();
        TvappAddPlaylist.TvappAddToPlaylist()
    });
    $('#tvapp_edit_playlist').submit(function(event){
        event.preventDefault();
        TvappEditPlaylist.TvappEditPlaylist()
    });
    // vinay added this function to handle cancel button
    $('#tvapp_edit_playlist_cancel').click(function(event){
        event.preventDefault();
        $('#videos-all').show();
	    $('#add-playlist').hide();
        $('#addTvappPlaylistHide').remove();
    });

    $(document).delegate('.dropdown-menu li a', 'click', function(e) {
        e.stopPropagation();
        //$('#dropdownMenu1').parent().toggleClass("open");
        $('.dropdown-toggle').click();

        GetVideosByCollection($(this).attr('id'))
/*
        $.ajax({
            url: ace.path('get_videos_by_collections'),
            type: 'get',
            data: {
                'id': $(this).attr('id')
            },
            dataType: "json",
            success: function(data) {
                $('#modules').html('');
                $.each(data.videos, function(index, value) {
                    $('#modules').append('<section class="section-video drag ui-draggable ui-draggable-handle" style="cursor: pointer; border-bottom: 1px solid #ccc; margin-bottom: 10px;">' +
                    '<div class="row center-block posRel" data-video_id="' + value.id + '">' +
                    '<div class="col-md-4">' +
                    '<img src="' + value.thumbnail_name + '" class="thumbnail_video">' +
                    '</div>' +
                    '<div class="col-md-7">' +
                    '<p style="text-align: left; margin: 0; overflow: hidden;">' + value.title + '</p>' +
                    '<p style="text-align: left; margin: 0; overflow: hidden;">' +
                    '<img src="/images/time_icon.png" style="margin-top: -4px;"> ' + value.time +
                    '</p>' +
                    '</div>' +
                    '<button class="btn btn-inverse add-video" title="Add video in playlist">+</button>' +
                    '</div>' +
                    '</section>');
                });
            }
        });
*/
    });
});
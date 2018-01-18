var TvappAddPlaylist = {
	TvappAddToPlaylist: function(){
        var playlistId;
        var title = $('#title').val();
        var description = $('#description').val();
        var playlist_type = $('#playlist_type').val();
        
        $.ajax({
            url: ace.path('tvapp_add_to_playlist'),
            type: "POST",
            data: {
                'title' : title,
                'description' : description
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    var playlist = {};

                    playlist.playlist_id = data.tvapp_playlist_id;
                    playlistId = data.tvapp_playlist_id;
                    playlist.playlists = [];

                    $('.add_videos_in_playlist').each(function(){
                        playlist.playlists.push($(this).data('video_id'));
                    });

                    $.ajax({
                        url: "tvapp_insert_video_in_playlist",
                        type: "POST",
                        data: {
                            "playlist" : playlist
                        },
                        dataType: "json",
                        success: function (data) {
                            if(data.status) {
                                $('.edited').html('<span class="fui-check"></span> Playlist successfully added!');
                                setInterval(function() {
                                    window.location.replace("tvapp_playlists");
                                }, 1500);
                            }
                        }
                    });
                    
                    $.ajax({
                        url: ace.path('tvapp_edit_playlist'),
                        type: "POST",
                        data: {
                            'id' : playlist.playlist_id,
                            'title' : title,
                            'description' : description,
                            'type' : playlist_type
                        },
                        dataType: "json",
                        success: function (data) {
                            window.location.replace("tvapp_playlists");
                        }
                    });
                    
                    
                }
            }
        });

        
        
    }
}

var TvappEditPlaylist = {
		TvappEditPlaylist: function() {
	        var id = $('#id').val();
	        var title = $('#title').val();
	        var description = $('#description').val();
	        var playlist_type = $('#playlist_type').val();
	        
            var playlist = {};

            playlist.playlist_id = id;
            playlist.playlists = [];

            $('.add_videos_in_playlist').each(function(){
                playlist.playlists.push($(this).data('video_id'));
            });
            
            $.ajax({
                url: "tvapp_insert_video_in_playlist",
                type: "POST",
                data: {
                    "playlist" : playlist
                },
                dataType: "json",
                success: function (data) {
                    if(data.status) {
                        $('.edited').html('<span class="fui-check"></span> Playlist successfully edited!');
                        setInterval(function() {
                            
                        }, 1500);
                    }
                }
            });
	        
	        
	        $.ajax({
	            url: ace.path('tvapp_edit_playlist'),
	            type: "POST",
	            data: {
	                'id' : id,
	                'title' : title,
	                'description' : description,
	                'type' : playlist_type
	            },
	            dataType: "json",
	            success: function (data) {
	                window.location.replace("tvapp_playlists");
	            }
	        });
	    }
	}



$(document).ready(function(){
    $('#tvapp_add_to_playlist').submit(function(event){
        event.preventDefault();
        TvappAddPlaylist.TvappAddToPlaylist()
    });
    $('#tvapp_edit_playlist').submit(function(event){
        event.preventDefault();
        TvappEditPlaylist.TvappEditPlaylist()
    });

    $(document).delegate('.dropdown-menu li a', 'click', function(e) {
        e.stopPropagation();
        $('.dropdown-toggle').click();

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
    });
});
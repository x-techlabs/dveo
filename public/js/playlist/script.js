$(document).ready(function(){
    var deletePlaylist = {
        deletePlaylist: function(id) {
            $.ajax({
                url: ace.path('delete_playlist'),
                type: "POST",
                data: {
                    "playlistId": id
                },
                success: function (data) {
                    $('[data-playlist_id="' + id + '"]').fadeOut();
                    $('#' + id).fadeOut();
                }
            });
        }
    }

    $('.play_live').click(function(e) {
        e.stopPropagation();
        e.preventDefault();
        var id = $(this).attr('id');
        if(id !== ''){
            $.ajax({
                url: ace.path('get_playlist_info'),
                type: 'post',
                data: {
                    'id': id
                },
                dataType: "json",
                success: function(data) {
                    jwplayer("stream_container").setup({
                        file: data.playlist.stream_url
                    });
                    $('#modalPlaylist').modal('toggle');
                }
            });
        }
        return false;
    });

    $('.delete_playlist').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        if(confirm('are you sure?')) {
            deletePlaylist.deletePlaylist(id);
        }
        return false;
    });

    $('.master_loop').click(function(event){
        var id = $(this).attr('id');
        event.stopPropagation();
        $.ajax({
            url: ace.path('playlist_master_loop'),
            type: "POST",
            data: {
                "id" : id
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    $('.btn-primary').addClass('btn-warning').removeClass('btn-primary');
                    $('.duration .master_looped').remove();
                    if(data.loopOff) {
                        $('[data-playlist_id="' + id + '"]').find('.master_loop').addClass('btn-primary').removeClass('btn-warning');
                        $('.duration').append('<span class="master_looped">&nbsp;|&nbsp;Master looped</span>');
                    }
                }
            }
        });
    });

    // Get VOD playlist
    $('.getPlaylist').click(function(event) {
        var id = $(this).attr('id');
        event.stopPropagation();
        $.ajax({
            url: ace.path('get_vod_playlist'),
            type: "POST",
            async: true,
            data: {
                "id" : id
            },
            dataType: "html",
            success: function (data) {
                $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
                $('.description').addClass('hide');
                $('#playlists').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-4');

                $('.playlists-for-collection-by-id').remove();
                $('#editPlaylistHide').remove();
                $('#addPlaylistHide').remove();

                $('.plusPtnCol').fadeOut();

                if ( $( ".vod_playlist_content" ).length == 0) {
                    $('#playlists').after(data);
                }else{
                    $( ".vod_playlist_content" ).remove();
                    $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
                    $('.description').addClass('hide');
                    // $('#playlists').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-4');
                    $('#playlists').removeClass(' col-md-4 ').removeClass('col-md-6').addClass('col-md-12');
                }
                
            }
        });


    });
    // End VOD playlist


});
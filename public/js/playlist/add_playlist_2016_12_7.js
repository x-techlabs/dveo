var addPlaylist = {
    addToPlaylist: function(){

        var title = $('#title').val();
        var description = $('#description').val();

        $.ajax({
            url: ace.path('add_to_playlist'),
            type: "POST",
            data: {
                'title' : title,
                'description' : description
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    var playlist = {};

                    playlist.playlist_id = data.playlist_id;
                    playlist.playlists = [];

                    $('.add_videos_in_playlist').each(function(){
                        playlist.playlists.push($(this).data('video_id'));
                    });

                    $.ajax({
                        url: "insert_video_in_playlist",
                        type: "POST",
                        data: {
                            "playlist" : playlist
                        },
                        dataType: "json",
                        success: function (data) {
                            if(data.status) {
                                $('.edited').html('<span class="fui-check"></span> Playlist successfully added!');
                                setInterval(function() {
                                    window.location.replace("playlists");
                                }, 1500);
                            }
                        }
                    });
                }
            }
        });
    }
}
var editPlaylist = {
    editPlaylist: function() {
        var id = $('#id').val();
        var title = $('#title').val();
        var description = $('#description').val();

        $.ajax({
            url: ace.path('edit_playlist'),
            type: "POST",
            data: {
                'id' : id,
                'title' : title,
                'description' : description
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    var playlist = {};

                    playlist.playlist_id = data.playlist_id;
                    playlist.playlists = [];

                    $('.add_videos_in_playlist').each(function(){
                        playlist.playlists.push($(this).data('video_id'));
                    });

                    $.ajax({
                        url: "insert_video_in_playlist",
                        type: "POST",
                        data: {
                            "playlist" : playlist
                        },
                        dataType: "json",
                        success: function (data) {
                            if(data.status) {
                                $('.edited').html('<span class="fui-check"></span> Playlist successfully edited!');
                                setInterval(function() {
                                    window.location.replace("playlists");
                                }, 1500);
                            }
                        }
                    });

                }
            }
        });
    }
}


$(document).ready(function(){
    $('#add_to_playlist').submit(function(event){
        event.preventDefault();
        addPlaylist.addToPlaylist()
    });
    $('#edit_playlist').submit(function(event){
        event.preventDefault();
        editPlaylist.editPlaylist()
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
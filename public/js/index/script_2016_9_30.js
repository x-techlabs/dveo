var columns3 = {

    //vjs: null,
    /**
     * Add the playlists column
     *
     * @param this_in_function
     */
    'addVideosColumn' : function(this_in_function) {

        var playlist_id = $(this_in_function).data('playlist_id');

        if (playlist_id == this.lastElementClickedId) {
             return;
        }

        var data = {
            'playlist_id' : playlist_id
        };
        var thisFunc = this;
        $.ajax({
            url: ace.path('get_videos_by_playlist_id'),
            type: 'get',
            data: data,
            success: function (data) {

                $('#playlists').removeClass('col-md-12').removeClass('col-md-3').removeClass('col-md-4').addClass('col-md-6');
                $('.playlists-for-collection-by-id').remove();
                $('.description').remove();
                $('#contnet-wrap').append(data.playlists);
                //$('#add-playlist').addClass('hide');
                $('#playlists-all').addClass('hide');
                $('#videos-all').remove();

                $('#editPlaylistHide').remove();
                $('#addPlaylistHide').remove();

                $('.playlist_thumb').removeClass('col-md-2').addClass('col-md-4');
                $('.playlist_info').removeClass('col-md-10').addClass('col-md-8');

                $('.searchPlay').animate({
                    'width': "181px"
                }, 150);

                $('#create-playlist').animate({
                    'padding-left': '19px',
                    'padding-right': '19px',
                    'width': "150px"
                }, 100);

                $('#playlists').after(data);
                $('#create-playlist').fadeIn();
            }
        });
    },

    /**
     * Add the description column
     *
     * @param this_in_function
     */
    addDescColumn : function(this_in_function) {
        var video_id = $(this_in_function).data('video_id');

        var string = this.desc_header + this.video_desc_blocks[video_id] + this.desc_footer;

        $('#playlists').removeClass('col-md-6').addClass('col-md-4');
        $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
        $('.description').remove();
        $('#add-playlist').remove();

        $('#contnet-wrap').append(string);

        //if (!!columns3.vjs) this.vjs.dispose();

        //this.vjs = videojs('video-js');
    },

    addPlaylist: function(){
        $.ajax({
            url: ace.path('add_to_playlist'),
            type: "GET",
            async: true,
            data: {
                "form" : true
            },
            dataType: "html",
            success: function (data) {
                $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
                $('.description').addClass('hide');
                $('.plusPtnCol').fadeOut();
                $('#playlists').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-4');

                $('.playlists-for-collection-by-id').remove();
                $('#editPlaylistHide').remove();
                $('#addPlaylistHide').remove();


                $('.searchPlay').animate({
                    'width': "174px"
                }, 150);

                //$('.playlists').addClass('hide');
                $('.description').addClass('hide');

                $.ajax({
                    url: "get_videos_for_playlists",
                    type: "GET",
                    async: true,
                    data: {
                        "form" : true
                    },
                    dataType: "html",
                    success: function (data) {
                        $('#add-playlist').after(data);
                    }
                });
                $('#playlists').after(data);
            }
        });
    },

    editPlaylist: function(id) {
        $.ajax({
            url: ace.path('get_playlist_by_id'),
            type: "POST",
            async: true,
            data: {
                "form" : true,
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
                //$('.playlists').addClass('hide');
                //$('.description').addClass('hide');

                $('.searchPlay').animate({
                    'width': "174px"
                }, 150);

                $.ajax({
                    url: "get_videos_for_playlists",
                    type: "GET",
                    async: true,
                    data: {
                        "form" : true
                    },
                    dataType: "html",
                    success: function (data) {
                        $('#add-playlist').after(data);
                    }
                });
                $('#playlists').after(data);
            }
        });
    }

}


//////



var tvapp_columns3 = {

	//vjs: null,
	/**
	 * Add the playlists column
	 *
	 * @param this_in_function
	 */
	'addVideosColumn' : function(this_in_function) {
	
	    var playlist_id = $(this_in_function).data('playlist_id');
	
	    if (playlist_id == this.lastElementClickedId) {
	         return;
	    }
	
	    var data = {
	        'playlist_id' : playlist_id
	    };
	    var thisFunc = this;
	    $.ajax({
	        url: ace.path('tvapp_get_videos_by_playlist_id'),
	        type: 'get',
	        data: data,
	        success: function (data) {
	
	            $('#playlists').removeClass('col-md-12').removeClass('col-md-3').removeClass('col-md-4').addClass('col-md-6');
	            $('.playlists-for-collection-by-id').remove();
	            $('.description').remove();
	            $('#contnet-wrap').append(data.playlists);
	            //$('#add-playlist').addClass('hide');
	            $('#playlists-all').addClass('hide');
	            $('#videos-all').remove();
	
	            $('#editPlaylistHide').remove();
	            $('#addPlaylistHide').remove();
	
	            $('.playlist_thumb').removeClass('col-md-2').addClass('col-md-4');
	            $('.playlist_info').removeClass('col-md-10').addClass('col-md-8');
	
	            $('.searchPlay').animate({
	                'width': "181px"
	            }, 150);
	
	            $('#create-playlist').animate({
	                'padding-left': '19px',
	                'padding-right': '19px',
	                'width': "150px"
	            }, 100);
	
	            $('#playlists').after(data);
	            $('#create-playlist').fadeIn();
	        }
	    });
	},
	
	/**
	 * Add the description column
	 *
	 * @param this_in_function
	 */
	addDescColumn : function(this_in_function) {
	    var video_id = $(this_in_function).data('video_id');
	
	    var string = this.desc_header + this.video_desc_blocks[video_id] + this.desc_footer;
	
	    $('#playlists').removeClass('col-md-6').addClass('col-md-4');
	    $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
	    $('.description').remove();
	    $('#add-playlist').remove();
	
	    $('#contnet-wrap').append(string);
	
	
	    //if (!!columns3.vjs) this.vjs.dispose();
	
	    //this.vjs = videojs('video-js');
	},
	
	tvappAddPlaylist: function(){
	    $.ajax({
	        url: ace.path('tvapp_add_to_playlist'),
	        type: "GET",
	        async: true,
	        data: {
	            "form" : true
	        },
	        dataType: "html",
	        success: function (data) {
	            $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
	            $('.description').addClass('hide');
	            $('.plusPtnCol').fadeOut();
	            $('#playlists').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-4');
	
	            $('.playlists-for-collection-by-id').remove();
	            $('#editPlaylistHide').remove();
	            $('#addPlaylistHide').remove();
	
	
	            $('.searchPlay').animate({
	                'width': "174px"
	            }, 150);
	
	            //$('.playlists').addClass('hide');
	            $('.description').addClass('hide');
	
	            $.ajax({
	                url: "tvapp_get_videos_for_playlists",
	                type: "GET",
	                async: true,
	                data: {
	                    "form" : true
	                },
	                dataType: "html",
	                success: function (data) {
	                    $('#add-playlist').after(data);
	                }
	            });
	            $('#playlists').after(data);
	        }
	    });
	},
	
	tvappEditPlaylist: function(id) {
	    $.ajax({
	        url: ace.path('tvapp_get_playlist_by_id'),
	        type: "POST",
	        async: true,
	        data: {
	            "form" : true,
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
	            //$('.playlists').addClass('hide');
	            //$('.description').addClass('hide');
	
	            $('.searchPlay').animate({
	                'width': "174px"
	            }, 150);
	
	            $.ajax({
	                url: "tvapp_get_videos_for_playlists",
	                type: "GET",
	                async: true,
	                data: {
	                    "form" : true
	                },
	                dataType: "html",
	                success: function (data) {
	                    $('#add-playlist').after(data);
	                }
	            });
	            $('#playlists').after(data);
	        }
	    });
	 }
}













var tvweb_columns3 = {

	//vjs: null,
	/**
	 * Add the playlists column
	 *
	 * @param this_in_function
	 */
	'addVideosColumn' : function(this_in_function) {
	
	    var playlist_id = $(this_in_function).data('playlist_id');
	
	    if (playlist_id == this.lastElementClickedId) {
	         return;
	    }
	
	    var data = {
	        'playlist_id' : playlist_id
	    };
	    var thisFunc = this;
	    $.ajax({
	        url: ace.path('tvweb_get_videos_by_playlist_id'),
	        type: 'get',
	        data: data,
	        success: function (data) {
	
	            $('#playlists').removeClass('col-md-12').removeClass('col-md-3').removeClass('col-md-4').addClass('col-md-6');
	            $('.playlists-for-collection-by-id').remove();
	            $('.description').remove();
	            $('#contnet-wrap').append(data.playlists);
	            //$('#add-playlist').addClass('hide');
	            $('#playlists-all').addClass('hide');
	            $('#videos-all').remove();
	
	            $('#editPlaylistHide').remove();
	            $('#addPlaylistHide').remove();
	
	            $('.playlist_thumb').removeClass('col-md-2').addClass('col-md-4');
	            $('.playlist_info').removeClass('col-md-10').addClass('col-md-8');
	
	            $('.searchPlay').animate({
	                'width': "181px"
	            }, 150);
	
	            $('#create-playlist').animate({
	                'padding-left': '19px',
	                'padding-right': '19px',
	                'width': "150px"
	            }, 100);
	
	            $('#playlists').after(data);
	            $('#create-playlist').fadeIn();
	        }
	    });
	},
	
	/**
	 * Add the description column
	 *
	 * @param this_in_function
	 */
	addDescColumn : function(this_in_function) {
	    var video_id = $(this_in_function).data('video_id');
	
	    var string = this.desc_header + this.video_desc_blocks[video_id] + this.desc_footer;
	
	    $('#playlists').removeClass('col-md-6').addClass('col-md-4');
	    $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
	    $('.description').remove();
	    $('#add-playlist').remove();
	
	    $('#contnet-wrap').append(string);
	
	
	    //if (!!columns3.vjs) this.vjs.dispose();
	
	    //this.vjs = videojs('video-js');
	},
	
	tvwebAddPlaylist: function(){
	    $.ajax({
	        url: ace.path('tvweb_add_to_playlist'),
	        type: "GET",
	        async: true,
	        data: {
	            "form" : true
	        },
	        dataType: "html",
	        success: function (data) {
	            $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
	            $('.description').addClass('hide');
	            $('.plusPtnCol').fadeOut();
	            $('#playlists').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-4');
	
	            $('.playlists-for-collection-by-id').remove();
	            $('#editPlaylistHide').remove();
	            $('#addPlaylistHide').remove();
	
	
	            $('.searchPlay').animate({
	                'width': "174px"
	            }, 150);
	
	            //$('.playlists').addClass('hide');
	            $('.description').addClass('hide');
	
	            $.ajax({
	                url: "tvweb_get_videos_for_playlists",
	                type: "GET",
	                async: true,
	                data: {
	                    "form" : true
	                },
	                dataType: "html",
	                success: function (data) {
	                    $('#add-playlist').after(data);
	                }
	            });
	            $('#playlists').after(data);
	        }
	    });
	},
	
	tvwebEditPlaylist: function(id) {
	    $.ajax({
	        url: ace.path('tvweb_get_playlist_by_id'),
	        type: "POST",
	        async: true,
	        data: {
	            "form" : true,
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
	            //$('.playlists').addClass('hide');
	            //$('.description').addClass('hide');
	
	            $('.searchPlay').animate({
	                'width': "174px"
	            }, 150);
	
	            $.ajax({
	                url: "tvweb_get_videos_for_playlists",
	                type: "GET",
	                async: true,
	                data: {
	                    "form" : true
	                },
	                dataType: "html",
	                success: function (data) {
	                    $('#add-playlist').after(data);
	                }
	            });
	            $('#playlists').after(data);
	        }
	    });
	 }
}


/////

$(document).ready(function(){

    $(document).delegate(".section_video","click", function(){
        columns3.addDescColumn(this);
    });

//    $('.section_playlist').click(function () {
//        columns3.addVideosColumn(this);
//
//    });

    $('#create-playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        columns3.addPlaylist();
    });

    $('.edit_playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        columns3.editPlaylist(id);
    });
    
    
    /// TVAPP ///
    
    $(document).delegate(".tvapp_section_video","click", function(){
        tvapp_columns3.addDescColumn(this);
    });

    $('.tvapp_section_playlist').click(function () {
    	tvapp_columns3.addVideosColumn(this);

    });

    $('#tvapp_create-playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        tvapp_columns3.tvappAddPlaylist();
    });

    $('.tvapp_edit_playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        tvapp_columns3.tvappEditPlaylist(id);
    });
    
    
    /// TVWEB ///
    
    $(document).delegate(".tvweb_section_video","click", function(){
        tvweb_columns3.addDescColumn(this);
    });

    $('.tvweb_section_playlist').click(function () {
    	tvweb_columns3.addVideosColumn(this);

    });

    $('#tvweb_create-playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        tvweb_columns3.tvwebAddPlaylist();
    });

    $('.tvweb_edit_playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        tvweb_columns3.tvwebEditPlaylist(id);
    });
    
    //////////

    
    $(document).delegate('.stream_status', 'click', function() {
        if($('.stream_status').hasClass('start_stream')) {
            console.log(1);
            $.ajax({
                url: "start",
                type: "GET",
                success: function() {
                    $('.stream_status').addClass('stop_stream').removeClass('start_stream').html('<i class="fa fa-stop"></i> &nbsp;STOP STREAM').attr('title', 'STOP STREAM');
                    $('.on_air').removeClass('on_air_off');
                }
            });
        } else {
            console.log(2);
            $.ajax({
                url: "stop",
                type: "GET",
                success: function() {
                    $('.stream_status').addClass('start_stream').removeClass('stop_stream').html('<i class="fa fa-play"></i> &nbsp;START STREAM').attr('title', 'START STREAM');
                    $('.on_air').addClass('on_air_off');
                }
            });
        }
        return false;
    });
});
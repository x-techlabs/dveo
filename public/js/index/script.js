var columns3 = {

    //vjs: null,
    /**
     * Add the playlists column
     *
     * @param this_in_function
     */

     // function for VOD playlist
    //  'showPlaylist' : function(this_in_function) {

    //     var playlist_id = $(this_in_function).data('playlist_id');

    //     if (playlist_id == this.lastElementClickedId) {
    //          return;
    //     }

    //     var data = {
    //         'playlist_id' : playlist_id
    //     };
    //     var thisFunc = this;
    //     $.ajax({
    //         url: ace.path('get_playlist_videos'),
    //         type: 'get',
    //         data: data,
    //         success: function (data) {

    //             $('#playlists').removeClass('col-md-12').removeClass('col-md-3').removeClass('col-md-4').addClass('col-md-6');
    //             $('.playlists-for-collection-by-id').remove();
    //             $('.description').remove();
    //             $('#contnet-wrap').append(data.playlists);
    //             //$('#add-playlist').addClass('hide');
    //             $('#playlists-all').addClass('hide');
    //             $('#videos-all').remove();

    //             $('#editPlaylistHide').remove();
    //             $('#addPlaylistHide').remove();

    //             $('.playlist_thumb').removeClass('col-md-2').addClass('col-md-4');
    //             $('.playlist_info').removeClass('col-md-10').addClass('col-md-8');

    //             $('.searchPlay').animate({
    //                 'width': "181px"
    //             }, 150);

    //             $('#create-playlist').animate({
    //                 'padding-left': '19px',
    //                 'padding-right': '19px',
    //                 'width': "150px"
    //             }, 100);

    //             $('#playlists').after(data);
    //             $('#create-playlist').fadeIn();
    //         }
    //     });
    // },

    // END Vod Playlist

    'addVideosColumn' : function(this_in_function) {

        var playlist_id = $(this_in_function).data('playlist_id');

        if (playlist_id == this.lastElementClickedId) {
            return;
        }
        // console.log(this_in_function);
        // this_in_function.parent().toggleClass('active_item');
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
                $( ".vod_playlist_content" ).remove();
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

	tvappAddPlaylist: function(row){
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
	            $('#playlists').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-8');
				
				$(".btn_for_create_top_shelf").css({'width':"22%",'font-size':"14px",'float':'left','padding':'8px','text-align':'right','margin-left':'5px'});
				$(".btn_for_create_low_shelf").css({'width':"22%",'font-size':"14px",'float':'left','padding':'8px','text-align':'right'});
				$(".btn_for_refresh_xml").css({'width':"22%",'font-size':"14px",'float':'left','padding':'8px','margin-left':'5px'});
				$(".btn_for_preview_pl").css({'width':"22%",'font-size':"14px",'float':'left','margin-left':'5px'});
				$(".btn_for_preview_pl .live").css({'padding':'3px'});
				$(".btn_for_create_low_shelf").css({'margin-left':'1%'});
				$(".div_for_change_btn_parth").css({'padding-top':"16px"})
				
				$(".btn_for_create_top_shelf").addClass("for_btn_responses");
				$(".btn_for_create_low_shelf").addClass("for_btn_responses");
				$(".btn_for_refresh_xml").addClass("for_btn_responses");
				$(".btn_for_preview_pl").addClass("for_btn_responses");
				

	            $('.playlists-for-collection-by-id').remove();
	            $('#editPlaylistHide').remove();
	            $('#addPlaylistHide').remove();


	            $('.searchPlay').animate({
	                'width': "174px"
	            }, 150);

	            //$('.playlists').addClass('hide');
	            $('.description').addClass('hide');
	            $('#playlists').after(data);
              	$platforms = $(".js-select2-tags");
                $platforms.select2({tags: true});
		        $("#tvapp_add_to_playlist").append('<input type="hidden" id="shelf" class="shelf_row_number" name="shelf" value="'+row+'">');
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
	            $('#playlists').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-8');
				
				$(".btn_for_create_top_shelf").css({'width':"22%",'font-size':"14px",'float':'left','padding':'8px','text-align':'right','margin-left':'5px'});
				$(".btn_for_create_low_shelf").css({'width':"22%",'font-size':"14px",'float':'left','padding':'8px','text-align':'right'});
				$(".btn_for_refresh_xml").css({'width':"22%",'font-size':"14px",'float':'left','padding':'8px','margin-left':'5px'});
				$(".btn_for_preview_pl").css({'width':"22%",'font-size':"14px",'float':'left','margin-left':'5px'});
				$(".btn_for_preview_pl .live").css({'padding':'3px'});
				$(".btn_for_create_low_shelf").css({'margin-left':'1%'});
				$(".div_for_change_btn_parth").css({'padding-top':"16px"})
				
				$(".btn_for_create_top_shelf").addClass("for_btn_responses");
				$(".btn_for_create_low_shelf").addClass("for_btn_responses");
				$(".btn_for_refresh_xml").addClass("for_btn_responses");
				$(".btn_for_preview_pl").addClass("for_btn_responses");

	            $('.playlists-for-collection-by-id').remove();
	            $('#editPlaylistHide').remove();
	            $('#addPlaylistHide').remove();

	            $('.plusPtnCol').fadeOut();
	            //$('.playlists').addClass('hide');
	            //$('.description').addClass('hide');

	            $('.searchPlay').animate({
	                'width': "174px"
	            }, 150);

                $('#videos-all').hide();
	            $('#playlists').after(data);

                $platforms = $(".js-select2-tags");
                $platforms.select2({tags: true});
                console.log($platforms.val().length);
                console.log($platforms.val().length === 4);
                if ($platforms.val().length === 4) {
                	$platforms.val(['0']);
                	$platforms.trigger('change');
				}
	        }
	    });
	 },

	 tvappAddVideoToPlaylist: function(id, level) {

        if ( $("#playlists" ).hasClass('col-md-12'))
        {
            $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
            $('.description').addClass('hide');
            $('#playlists').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-8');

            $('.playlists-for-collection-by-id').remove();
            $('#editPlaylistHide').remove();
            $('#addPlaylistHide').remove();

            $('.plusPtnCol').fadeOut();

            $('.searchPlay').animate({
                'width': "174px"
            }, 150);
        }

	    $.ajax({
	        url: "tvapp_get_videos_for_playlists",
	        type: "GET",
	        async: true,
	        data: {
	            "form" : true,
	            "id" : id,
	            "level" : level
	        },
	        dataType: "html",
	        success: function (data) {
                $('#videos-all').remove();
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

//==============================================================================
//==============================================================================

var DrgStatus = 0;
function DragEventStart( event, ui ) { DrgStatus = 1; }
function DragEventStop( event, ui )  { sid = $(this).attr('data'); window.setTimeout('DragEventStopDelayed(' + sid + ')', 700); }
function DragEventStopDelayed(sid)
{
    if (DrgStatus==1)
    {
        DrgStatus = 0;
        $.ajax({
            url: "tvapp_sort_order_change",
            type: "POST",
            async: true,
            data: {
                "sourceID" : sid,
                "targetID" : sid
            },
            dataType: "html",
            success: function (data) {
                info = data.split('~~');
                $('#childrenOf_' + info[0]).html(info[1]);
                OpenNodes();
            }
        });
    }
}

function OpenNodes()
{
    openNodes = $('#openNodes').val().split(',');
    for ( var i = 0 ; i < openNodes.length ; i++)
    {
        $('#childrenOf_' + openNodes[i]).show();
    }

    $( ".draggablePL" ).draggable({ start: DragEventStart, stop: DragEventStop });
    $( ".droppablePL" ).droppable({ drop: DropHandleEvent });
}

function DropHandleEvent( event, ui )
{
    DrgStatus = 0;

    sourceID = ui.draggable.attr('data');
    targetID = $(this).attr('data');

    $.ajax({
        url: "tvapp_sort_order_change",
        type: "POST",
        async: true,
        data: {
            "sourceID" : sourceID,
            "targetID" : targetID
        },
        dataType: "html",
        success: function (data)
        {
            info = data.split('~~');
            $('#childrenOf_' + info[0]).html(info[1]);
            OpenNodes();
        }
    });
}

//==============================================================================
//==============================================================================
function ToggleView(me)
{
    if (me.getAttribute('view')=='1')
    {
        me.setAttribute('view', '2');
        var src = me.src.replace('tree.png', 'list.png');
        me.src = src;
        //me.innerHTML = 'List View';
        $('#container_content').hide();
        $('#container_tree').show();
    }
    else
    {
        me.setAttribute('view', '1');
        var src = me.src.replace('list.png', 'tree.png');
        me.src = src;
        me.innerHTML = 'Tree Structure';
        $('#container_content').show();
        $('#container_tree').hide();
    }
}

function ShowHelpOf(me)
{
    $.ajax({
        url: "tvapp_help",
        type: "POST",
        async: true,
        data: { "helpid" : me.value },
        dataType: "html",
        success: function (data)
        {
            $('#help_1').html(data);
        }
    });
}

function ShowHelp(x)
{
    var obj = document.getElementById('help');
    if (x==1)
    {
        var length = $('#helpTopics > option').length;
        if (length == 1)
        {
            parts = window.location.href.split('/');
            //alert(parts[parts.length-1]);
            $.ajax({
                url: "help_get_subject_list",
                type: "POST",
                async: true,
                data: { "sectionid" : parts[parts.length-1] },
                success: function (data) {
                    var obj = document.getElementById('helpTopics');
                    // empty current dropdown
                    while(obj.options.length > 0) obj.remove(0);

                    nvArray = data.split(';');
                    for (i = 0 ; i < nvArray.length ; i += 2)
                    {
                        var newOption = new Option(nvArray[i], nvArray[i+1]);
                        obj.options[obj.options.length] = newOption;
                    }
                }
            });
        }

        obj.style.width = '60%';
        obj.style.left = '20%';
        obj.style.height = '70%';
        obj.style.top = '15%';
        obj.style.display = 'block';
        obj.style.backgroundColor = '#ffffff';
        obj.style.border = '1px solid #777';
    }
    if (x==0)
    {
        obj.style.width = '1px';
        obj.style.left = '1px';
        obj.style.height = '1px';
        obj.style.top = '1px';
        obj.style.display = 'none';
        obj.style.backgroundColor = '#ffffff';
        obj.style.border = '1px solid #777';
    }
}

// vinay -> This function opens or closes playlist tree nodes
function ToggleChildrenList(me)
{
    // Get object which contains tree
    cid = me.getAttribute('data');
    level = me.getAttribute('level');
    if (cid==0)
    {
        $('#videos-all').remove();
   	    $('#add-playlist').remove();
        tvapp_columns3.tvappAddVideoToPlaylist(cid,level);
        SetPlayListID(cid, '');
        return;
    }

    // get list of open nodes
    openNodes = $('#openNodes').val().split(',');

    obj = document.getElementById('childrenOf_'+cid);

    imgObj = document.getElementById('folder_'+cid);
    // toggle object's display status
    if (imgObj.src.indexOf('folder_close.png') != -1)
    {
        obj.style.display='block';
        imgObj.src = '/images/folder_open.png';

        // Open video list
        $('#videos-all').remove();
   	    $('#add-playlist').remove();
        tvapp_columns3.tvappAddVideoToPlaylist(cid,level);

        // Add id to openNodex
        if (openNodes.indexOf(cid)==-1) openNodes.push(cid);
    }
    else if (imgObj.src.indexOf('folder_open.png') != -1)
    {
        obj.style.display='none';
        imgObj.src = '/images/folder_close.png';

        // Remove from openNodex
        pos = openNodes.indexOf(cid);
        if (pos != -1) openNodes.splice(pos, 1);
    }
    // save list of open nodes
    $('#openNodes').val( openNodes.join(',') );

    // set current playlist's ID as active playlist_id
    SetPlayListID(cid, '');
}

function SetPlayListID(id, ch)
{
    // reset existing row color
    pid = 'plRow' + ch + '_' + $('#parent_playlist_id').val();
    $('#' + pid).css('background', '');

    $('#parent_playlist_id').val(id);

    // set new row color
    pid = 'plRow' + ch + '_' + $('#parent_playlist_id').val();
    $('#' + pid).css('background', '#ADD8E6');
}

/////

$(document).ready(function(){

    $(document).delegate(".section_video","click", function(){
        columns3.addDescColumn(this);
    });

    $('.section_playlist').click(function () {
        columns3.addVideosColumn(this);

    });

    $('#create-playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        columns3.addPlaylist();
    });

    $('.edit_playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        $('.section_playlist').removeClass('active_item');
        $(this).parent().toggleClass('active_item');
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

    $('.tvapp_create_playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        $('#videos-all').remove();
   	    $('#add-playlist').remove();

        var row = $(this).attr("data-row");
        tvapp_columns3.tvappAddPlaylist(row);
    });
	$(document).on("click","#show_playlist_categories",function(){
		
		
		$(".div_for_playlist_categories .select2-selection").css({"display":"block"})
		$("#dont_create_playlists_from_all_playlists").click();
		$(".li_for_closed").slideUp(200);
		var val = $(".playlist_category_select").find(':selected').attr('data-name');
		$(".input_for_auto_required").val(val);
	})
	$(document).on("click","#dont_show_playlist_categories",function(){
		$(".div_for_playlist_categories .select2-selection").hide();
		$(".li_for_closed").slideDown(200);
		$(".input_for_auto_required").val('');
	})
	$(document).on("click","#create_playlists_from_all_playlists",function(){
		$(this).addClass("active_for_all_playlists");
		$("#dont_create_playlists_from_all_playlists").removeClass('active_for_all_playlists');
	})
	$(document).on("click","#dont_create_playlists_from_all_playlists",function(){
		$(this).addClass("active_for_all_playlists");
		$("#create_playlists_from_all_playlists").removeClass('active_for_all_playlists');
	})
	$(document).on("change",".playlist_category_select",function(){
		var val = $(".playlist_category_select").find(':selected').attr('data-name');
		$(".input_for_auto_required").val(val);
	})

    $(document).on("click", '.b-playlist-treeview__playlist-edit', function(){
        $("#js-playlists-treeview .b-playlist-videolist").hide();
    });
       $("#tvapp_create_playlist_low").click(function(){

        $("#js-playlists-treeview .b-playlist-videolist").hide();

    })
         $("#tvapp_create_playlist_top").click(function(){

        $("#js-playlists-treeview .b-playlist-videolist").hide();

    })
    var w = window.outerWidth;
    var h = window.outerHeight;
    	var z = h - 470
    if(h >= 470 && h < 555 ){
    	var w = z/2 + 175 	
    	$(".modal_parth_overflow").css({"height":w+"px"});
    }
    else{

    	 if(h >= 555 && h < 635){
			var w = z/2 +200;
			$(".modal_parth_overflow").css({"height":w+"px"});    	 	
    	 }
    	 if(h >= 635 && h < 700){
    	 	var w = z/2 + 210
    	 	$(".modal_parth_overflow").css({"height":w+"px"})
    	 }
    	 if(h >= 700  && h < 728){
    	 	var w = z/2 + 243
    	 	$(".modal_parth_overflow").css({"height":w+"px"})
    	 }
    	 if(h >= 728){
    	 	var w = z/2 + 253
    	 	$(".modal_parth_overflow").css({"height":w+"px"})	
    	 }
    	  if(h >= 100 && h < 470){
			var w = z/2 +160;
			$(".modal_parth_overflow").css({"height":w+"px"});    	 	
    	 }
    }
    $(document).on("click", '#tvapp_edit_playlist_cancel', function(){
		
		$(".btn_for_create_top_shelf").css({'width':"165px",'text-align':'center'});
		$(".btn_for_create_low_shelf").css({"margin-left":"22%",'text-align':'center','width':'165px'});
		$(".btn_for_preview_pl").css({'width':"165px",'float':'right'})
		$(".btn_for_preview_pl .live").css({'padding':'5px'})
		$(".btn_for_refresh_xml").css({'width':"165px",'float':'right','margin-right':'10px'});
		
        $("#editPlaylistHide").css({"min-width":0,"width":0});
        $("#js-playlists-treeview .b-playlist-videolist").show();
		
		$(".btn_for_create_top_shelf").removeClass("for_btn_responses");
		$(".btn_for_create_low_shelf").removeClass("for_btn_responses");
		$(".btn_for_refresh_xml").removeClass("for_btn_responses");
		$(".btn_for_preview_pl").removeClass("for_btn_responses");
		
    });

    $('.tvapp_edit_playlist').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        var id = $('#parent_playlist_id').val();  //$(this).attr('id');
        if (id==0) { alert("Please select playlist to edit...");  return; }
        else if (id=='C') { alert("You can not edit channel...");  return; }

        tvapp_columns3.tvappEditPlaylist(id);
    });

    // vinay added to handle video addition to playlist
    $('.tvapp_remove_video_from_playlist').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        contents = [];

        $("input:checkbox[name=ccb]:checked").each(function(){
            contents.push($(this).val());
        });

        if (contents.length==0) { alert('Please select videos / playlists to remove...'); return; }

        if (!confirm('You are about to delete videos / playlists from tree')) return;

        // Get top level playlist id

	    $.ajax({
	        url: "tvapp_remove_from_playlists",
	        type: "POST",
	        async: true,
	        data: {
	            "contents" : contents,
	        },
	        dataType: "html",
	        success: function (data) {
                info = data.split('~~');
                $('#childrenOf_' + info[0]).html(info[1]);
                OpenNodes();
	        }
	    });
    });

    $('.tvapp_generate_feed').click(function(event) {
        event.stopPropagation();
        event.preventDefault();

        var id = $('#parent_playlist_id').val();  //$(this).attr('id');

	    $.ajax({
	        url: "tvapp_generate_feed",
	        type: "POST",
	        async: true,
	        data: {
	            "id" : id,
	        },
	        dataType: "html",
	        success: function (data) {
                if (data == '1') alert('Feed Updated successfully');
                else if (data == '0') alert('Feed Updation failed');
	        }
	    });
    });

    $("#showlevel").change(function(){
        v = $("#showlevel").val();
        for (var lvl = 0 ; lvl <= 5 ; lvl++)
        {
            if (v==9 || v==lvl) $(".level_" + lvl).css({"display":"block"});
            else $(".level_" + lvl).css({"display":"none"});
        }
    });

    $( ".draggablePL" ).draggable({ start: DragEventStart, stop: DragEventStop });
    $( ".droppablePL" ).droppable({ drop: DropHandleEvent });
    //==========================================================================
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
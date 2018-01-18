@if ($calledFrom == 'tvapp')
    {{ HTML::script('js/tvapp/tvapp_add_playlist.js') }}
@endif
@if ($calledFrom == 'tvweb')
    {{ HTML::script('js/tvweb/tvweb_add_playlist.js') }}
@endif
@if ($calledFrom == '')
    {{ HTML::script('js/playlist/add_playlist.js') }}
@endif

<div class="col-md-4 height" id="videos-all">
    <script>
        $(document).ready(function(){
            $('.drag').draggable({
                appendTo: 'body',
                helper: 'clone'
            });
            $('#drag-and-drop').droppable({
                activeClass: 'dragActive',
                accept: ":not(.ui-sortable-helper)", // Reject clones generated by sortable
                drop: function (e, ui) {
                    var $el = $('<div class="drop-item ui-sortable-handle">' + ui.draggable.html() + '</div>');
                    $el.append($('<button type="button" class="btn btn-danger remove" title="Remove video in playlist"><span class="fui-trash"></span></button>').click(function () {
                        var video_time = $(this).prev().find('p:last').text();
                        var video_time_sec = hmsToSecondsOnly(video_time);
                        video_time_sec_trt = $('#add_playlist_trt').text();
                        video_time_sec_trt = hmsToSecondsOnly(video_time_sec_trt.substring(5));
                        var video_time_trt = secondsTimeSpanToHMS(video_time_sec_trt - video_time_sec);
                        $('#add_playlist_trt').empty();
                        $('#add_playlist_trt').append(
                                '&nbsp;&nbsp;TRT&nbsp;<img src="/images/time_icon.png" style="margin-top: -4px;"> ' + video_time_trt);

                        $(this).parent().fadeOut().detach();
                        $(this).stopImmediatePropagation();
                    }));
                    $(this).append($el);

                    // take duration from dropped element and add to TRT (Total Running Time) of Add Playlist or Edit Playlist pages
                    var video_time = $('p:last').text();
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

                    $('.drag-and-drop').each(function () {
                        $(this).parent().find('.row').addClass('add_videos_in_playlist')
                    });
                }
            }).sortable({
                items: '.drop-item',
                zIndex: 999999999,
                activeClass: 'sortableActive',
                sort: function() {
                    // gets added unintentionally by droppable interacting with sortable
                    // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
                    $( this ).removeClass( "dragActive" );
                }
            })

            $('.playVideoInPopup').click(function (event) {
                event.stopPropagation();
                event.preventDefault();

                var vid = $(this).attr('video_id');
                PlayVideoFromID(vid);
                return false;
            });

        });
    </script>
    <div class="height video content">
        <!-- <div class="title-name overflowInherit"> -->
        <div>
<!--            <i class="fa fa-video-camera"></i>  -->
            <div class="title-name">
                @if($parent_playlist_id >= 0)
                    {{ Form::select('cType', ['video' => 'Videos', 'plist' => 'PlayList'], 'video', ['id' => 'cType', 'onchange' => 'OnMediaChanged(this)']) }} 
                @else
                    Videos
                @endif
                <script>
                    function OnMediaChanged(me)
                    {
                        if (me.value == 'video') { $('#modules').show(); $('#modulesp').hide(); }
                        else { $('#modules').hide(); $('#modulesp').show(); }
                    }
                </script>
            </div>

            <div class="dropdown">
                <button class="btn btn-inverse dropdown-toggle" type="button" id="dropdownMenu1" aria-expanded="true">Folders <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-inverse" role="menu" aria-labelledby="dropdownMenu1">
                    <li role="presentation"><a id="00" class="uncategorized" id="0" role="menuitem" tabindex="-1" href="#">All videos</a></li>
                    <li role="presentation"><a id="01" class="uncategorized" id="0" role="menuitem" tabindex="-1" href="#">Uncategorized videos</a></li>
                    <li class="divider"></li>
                    @foreach($collections as $collection)
                        <li role="presentation"><a id="{{ $collection->id }}" role="menuitem" tabindex="-1" href="#">{{ $collection->title }}</a></li>
                    @endforeach
                </ul>
            </div>

            <script>
                $('.dropdown-toggle').on('click', function(e) {
                    $(this).parent().toggleClass("open");
                });
            </script>

            <div class="input-group searchVideo">
                <input type="text" class="form-control" placeholder="Search" id="search-query-3">
                    <span class="input-group-btn">
                        <button type="submit" class="btn"><span class="fui-search"></span></button>
                    </span>
            </div>
        </div>

        <div class="row center-block list dragVideoHeight content_list" id="container_content">
            <div id="modules" class="col-md-12" style="text-align: center; height: 100%; position: static;" id="video_blocks">

                @foreach($videos as $key => $video)

                <section class="section-video drag" style='cursor: pointer; border-bottom: 1px solid #ccc; margin-bottom: 10px;'>

                    <div class="row center-block posRel" data-video_id="{{$video->id}}" >
                        <!-- vinay -->
                        @if($parent_playlist_id >= 0)
                            <div class="col-md-1">
                                {{ Form::checkbox('mcb', $video->id) }} 
                            </div>
                            <div class="col-md-2">
                                <img src="{{ (!empty($video->custom_poster)) ? 'https://s3.amazonaws.com/aceplayout/banners/'.$video->custom_poster : $video->thumbnail_name}}" class="thumbnail_video" style='width:52px;'>
                                <!-- Onclick event for class playVideoInPopup is defined in template.blade.php and it looks for attribute video_id --> 
                                <img class="playVideoInPopup" src='{{ URL::to('/') }}/images/play.jpg' style='float:left;width:24px;height:24px;margin-top:5px;' video_id='{{$video->id}}'>
                            </div>
                            <div class="col-md-7">
                                <p style="text-align: left; margin: 0; overflow: hidden;">
                                    {{$video->title}}
                                </p>
                            </div>

                        @else
                            <div class="col-md-4">
                                <img src="{{ (!empty($video->custom_poster)) ? 'https://s3.amazonaws.com/aceplayout/banners/'.$video->custom_poster : $video->thumbnail_name}}" class="thumbnail_video" style='width:82px;'>
                                <!-- Onclick event for class playVideoInPopup is defined in template.blade.php and it looks for attribute video_id --> 
                                <img class="playVideoInPopup" src='{{ URL::to('/') }}/images/play.jpg' style='margin-right:50px;width:34px;height:34px;margin-top:5px;' video_id='{{$video->id}}'>
                            </div>
                            <div class="col-md-7">
                                <p style="text-align: left; margin: 0; overflow: hidden;">
                                    {{$video->title}}
                                </p>
                                <p style="text-align: left; margin: 0; overflow: hidden;">
                                    <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$video->time}}
                                </p>
                            </div>
                        @endif
                        <!-- vinay added type and parent attributes -->
                        @if($parent_playlist_id >= 0)
                            <button type='0' video_id="{{$video->id}}" class="btn btn-inverse add-video-new" title="Add video in playlist" style='width:24px;height:24px;padding:4px 4px;'>&plus;</button>
                        @else
                           <button class="btn btn-inverse add-video" title="Add video in playlist">&plus;</button>
                        @endif
                    </div>

                </section>
                @endforeach
                @if($parent_playlist_id >= 0)
                    <button type='0' video_id="0" class="btn btn-inverse add-video-new" title="Add Selected video in playlist">Add Selected Videos</button>
                @endif
            </div>

            @if($parent_playlist_id >= 0)
                <div id="modulesp" class="col-md-12" style="text-align: center; height: 100%; position: static; display: none;" id="playlist_blocks">
                    <?php if (count($playlists) == 0) print "There are no playlists with level=".($parent_playlist_level+1); ?>
                    @foreach($playlists as $playlist)

                        <section class="section-video drag" style='cursor: pointer; border-bottom: 1px solid #ccc; margin-bottom: 10px;'>

                        <div class="row center-block posRel" data-video_id="{{$playlist->id}}" >
                            <div class="col-md-1">
                                {{ Form::checkbox('mcb', $playlist->id) }} 
                            </div>
                            <div class="col-md-2">
                                <img src="{{$playlist->thumbnail_name}}" class="thumbnail_video" style='width:52px;'>
                            </div>
                            <div class="col-md-7">
                                <p style="text-align: left; margin: 0; overflow: hidden;">
                                    {{$playlist->title}}
                                </p>
                            </div>
                            <button type='1' video_id="{{$playlist->id}}" class="btn btn-inverse add-video-new" title="Add video in playlist" style='width:24px;height:24px;padding:4px 4px;'>&plus;</button>
                        </div>
                        </section>
                    @endforeach
                    <button type='1' video_id="0" class="btn btn-inverse add-video-new" title="Add Selected video in playlist">Add Selected Videos</button>
                </div>
            @endif
        </div>
    </div>
</div></div></div></div>
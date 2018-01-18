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
        });
    </script>
    <div class="height video content">
        <div class="title-name overflowInherit">
            <i class="fa fa-video-camera"></i>
            <div class="title">Videos</div>

            <div class="dropdown">
                <button class="btn btn-inverse dropdown-toggle" type="button" id="dropdownMenu1" aria-expanded="true">Collections <span class="caret"></span>
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

                @foreach($videos as $video)

                <section class="section-video drag" style='cursor: pointer; border-bottom: 1px solid #ccc; margin-bottom: 10px;'>

                    <div class="row center-block posRel" data-video_id="{{$video->id}}" >
                        <div class="col-md-4">
                            <img src="{{$video->thumbnail_name}}" class="thumbnail_video">
                        </div>
                        <div class="col-md-7">
                            <p style="text-align: left; margin: 0; overflow: hidden;">
                                {{$video->title}}
                            </p>
                            <p style="text-align: left; margin: 0; overflow: hidden;">
                                <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$video->time}}
                            </p>
                        </div>

                        <button class="btn btn-inverse add-video" title="Add video in playlist">&plus;</button>
                    </div>

                </section>
                @endforeach

            </div>
        </div>
    </div>
</div></div></div></div>
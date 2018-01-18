<div id="editPlaylistHide">
    <div class="col-md-4 height" id="add-playlist">

        <div class="addPlaylist height content">
            <div class="title-name">
                <i class="fa fa-play-circle"></i>
                <div class="title">Edit Slide</div><p id="add_playlist_trt" style="text-align: left; margin: 0; overflow: hidden;">&nbsp;&nbsp;TRT&nbsp;<img src="/images/time_icon.png" style="margin-top: -4px;"> {{$playlist->time}}</p>
            </div>
            <div class="clear"></div>

            <div class="videoHeightedit content_list">
                {{ Form::open(array(
                'url' => 'channel_'.BaseController::get_channel_id().'/edit_playlist',
                'class' => 'form-horizontal height-inherit add-playlist-form',
                'enctype' => 'multipart/form-data',
                'id' => 'edit_playlist1'
                )) }}

                <div class="control-group">
                    {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                    <div class="controls">
                        {{ Form::text('title', $playlist->title, array('class' => 'form-control z-index-1', 'id' => 'title')) }}
                    </div>
                </div>
                <div class="control-group">

                    {{ Form::label('Description', 'Description', array('class' => 'control-label')) }}

                    <div class="controls">
                        {{ Form::text('description', $playlist->description, array('class' => 'form-control z-index-1', 'id' => 'description')) }}
                    </div>
                </div>

                {{ Form::hidden('id', $playlist->id, array('class' => 'form-control z-index-1', 'id' => 'id')) }}

                <div class="drag-and-drop">
                    <div class="col-md-12" id="drag-and-drop">

                        <script>
                            $(".drop-item .remove").click(function() {
                                        var video_time = $(this).prev().find('p:last').text();
                                        var video_time_sec = hmsToSecondsOnly(video_time);
                                        video_time_sec_trt = $('#add_playlist_trt').text();
                                        video_time_sec_trt = hmsToSecondsOnly(video_time_sec_trt.substring(5));
                                        var video_time_trt = secondsTimeSpanToHMS(video_time_sec_trt - video_time_sec);
                                        $('#add_playlist_trt').empty();
                                        $('#add_playlist_trt').append(
                                                '&nbsp;&nbsp;TRT&nbsp;<img src="/images/time_icon.png" style="margin-top: -4px;"> ' + video_time_trt);
                                        $(this).parent().fadeOut().remove();
                                        $(this).stopImmediatePropagation(); }
                            );
                        </script>

                        @foreach($videos as $video)
                            <div class="drop-item">

                                <div class="row center-block posRel add_videos_in_playlist" data-video_id="{{$video['id']}}">
                                    <div class="col-md-4">
                                        <img src="https://s3.amazonaws.com/1stud-images/{{$video['file_name']}}" class="thumbnail_video">
                                    </div>
                                    <div class="col-md-7">
                                        <p style="text-align: left; margin: 0; overflow: hidden;">
                                            {{$video['title']}}
                                        </p>
                                        <p style="text-align: left; margin: 0; overflow: hidden;">
                                            <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$video['time']}}
                                        </p>
                                    </div>
                                    <button class="btn btn-inverse add-video" title="Add video in playlist">&plus;</button>
                                </div>
                                <button type="button" class="btn btn-danger remove" title="Remove video in playlist"><span class="fui-trash"></span></button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="edited"></div>

                <div class="input-group saveBtn">
                    <div class="controls">
                        {{ Form::button('Save', array('class' => 'btn btn-inverse', 'id' => 'editSlideBtn')) }}
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>

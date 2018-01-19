<div class="collectionRight editFolderWrapper col-md-4">
    <div class="row center-block height" id="contnet-wrap">
        <div class="height" id="upload" style="padding-right: 0px!important;">

            {{ Form::open(array('url' => 'channel_' . BaseController::get_channel_id() . '/edit_folder_post', 'class' => 'form-horizontal', 'id' => 'edit_folder')) }}
            <div class="height addVideo content">
                <p class="title-name"><i class="fa fa-tags"></i>Edit folder
                </p>
                <div class="coll_actions">
                    {{ Form::submit('Save', array('class' => 'btn btn-inverse', 'style' => 'float:right;')) }}
                    <div class="cancelBtn"><a href = "javascript:void(0)" class="btn btn-inverse cancel_collection">Cancel</a></div>
                </div>
                <div id="videoForm" class="content_list">
                    <div class="control-group text-left">
                        {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                        <div class="controls">
                            {{ Form::text('title', $collection->title, array('class' => 'form-control')) }}
                        </div>
                    </div>

                    {{Form::hidden('id', $collection->id, array('id' => 'video-format'))}}

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
                                            <img src="https://s3.amazonaws.com/dveo-images/{{$video['file_name']}}" class="thumbnail_video" style='width:82px;'>
                                        </div>
                                        <div class="col-md-7">
                                            <p style="text-align: left; margin: 0; overflow: hidden;">
                                                {{$video['title']}}
                                            </p>
                                            <p style="text-align: left; margin: 0; overflow: hidden;">
                                                <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$video['created_at']}}
                                            </p>
                                        </div>
                                        <button class="btn btn-inverse add-video" title="Add image in folders">&plus;</button>
                                    </div>
                                    <button type="button" class="btn btn-danger remove" title="Remove image in folder"><span class="fui-trash"></span></button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="edited"></div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

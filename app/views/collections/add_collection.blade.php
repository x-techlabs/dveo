<div class="row height collection-form col-md-4" >
    <div class="height" style="width:100%;padding-right: 0px !important;">
        <div class="height collectionBox content">
            <p class="title-name"><i class="fa fa-tags"></i>Add folder</p>

            <div class="height cloHeight content_list" id="container_content">
                {{ Form::open(array(
                'url' => 'add_to_collection',
                'class' => 'form-horizontal, height',
                'enctype' => 'multipart/form-data',
                'id' => 'add-to-collection'
                )) }}
                <!-- Name -->
                <div class="control-group">
                    {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                    <div class="controls">
                        {{ Form::text('title', '', array('class' => 'form-control z-index', 'id' => 'title')) }}
                    </div>
                </div>

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
                    </div>
                </div>

                <div class="edited"></div>

                <!-- Login button -->
                <div class="input-group saveBtn">
                    <div class="controls">
                        {{ Form::submit('Save', array('class' => 'btn btn-inverse add-collection')) }}
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>
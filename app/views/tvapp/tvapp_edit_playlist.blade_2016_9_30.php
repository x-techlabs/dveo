<div id="editPlaylistHide">
    {{ HTML::style('css/upload.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/styles.css') }}
    {{ HTML::script('js/tvapp/script.js') }}
    {{ HTML::script('js/tvapp/tvapp_add_playlist.js') }}
    
    <div class="col-md-4 height" id="add-playlist">

        <div class="addPlaylist height content">
            <div class="title-name">
                <i class="fa fa-play-circle"></i>
                <div class="title">Edit playlist</div><p id="add_playlist_trt" style="text-align: left; margin: 0; overflow: hidden;">&nbsp;&nbsp;TRT&nbsp;<img src="/images/time_icon.png" style="margin-top: -4px;"> {{$playlist->time}}</p>
            </div>
            <div class="clear"></div>


            <div class="videoHeightedit content_list">
            
                <div class="image_control">
                    <img id="real_file_url" onerror="$('.image_control img').attr('src', '{{asset('images/noLogo.png')}}')" src="http://prolivestream.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}_tvapp_playlist_{{ $playlist['id'] }}?{{ md5($channel['updated_at']) }}" class="logo1 col-md-4">
                    <div>
                        <div class="logoLoader">
                            <span class="logoLoading"></span>
                        </div>
                        <div class="col-md-8">
                        {{ Form::open(array(
                            'url' => 'https://prolivestream.s3-us-west-2.amazonaws.com/',
                            'class' => 'form-horizontal amazon_playlist_logo',
                            'enctype' => 'multipart/form-data'
                            )) }}
                        {{ Form::file('file', array('id' => 'fileupload', 'data-url' => 'server/php/')) }}
                        {{ Form::hidden('key', 'uploads', array('id' => 'key')) }}
                        {{ Form::hidden('acl', 'public-read') }}
                        {{ Form::hidden('AWSAccessKeyId', 'AKIAIDGRDUJ7ZG5DNJEA') }}
                        {{ Form::hidden('Policy', 'policy', array('id' => 'policy')) }}
                        {{ Form::hidden('Signature', 'signature', array('id' => 'signature')) }}
                        {{ Form::close() }}
                        
                        {{ Form::hidden('tvapp_playlist_id', $playlist->id, array('id' => 'tvapp_playlist_id')) }}
                        </div>
                    </div>
                </div>




                {{ Form::open(array(
                'url' => 'tvapp_edit_playlist',
                'class' => 'form-horizontal height-inherit add-playlist-form',
                'enctype' => 'multipart/form-data',
                'id' => 'tvapp_edit_playlist'
                )) }}
                
                <div class=clear2></div>
                
                <div class="input-group saveBtn2">
                    <div class="controls">
                        <div class="edited"></div>
                        Type:&nbsp;
                        {{ Form::select('type', [
                           '0' => 'General Playlist',
                           '1' => 'Main Videos',
                           '2' => 'Featured Videos',
						   '3' => 'Latest Videos',
						   '4' => 'Most Viewed Videos',
						   '5' => 'Most Popular Videos'],
						   $playlist->type,
						   array('id' => 'playlist_type')
						) }}
						&nbsp;&nbsp;

                        {{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
                        &nbsp;
                    </div>
                </div>
                
                <div class=clear></div>

                <!-- Name -->
                <div class="control-group">
                    {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                    <div class="controls">
                        {{ Form::text('title', $playlist->title, array('class' => 'form-control z-index-1', 'id' => 'title')) }}
                    </div>
                </div>

                <!-- Password -->
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
                                        <img src="{{$video['thumbnail_name']}}" class="thumbnail_video">
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

                <!-- Login button -->
                <div class="input-group saveBtn">
                    <div class="controls">
                        {{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
                        &nbsp;
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>


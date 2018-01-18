<div class="videoRight">
    {{ HTML::script('js/videos/script.js') }}
    <div class="row center-block height" id="contnet-wrap">
        <div class="col-md-6 height" id="upload" style="padding-right: 0px!important;">

            <div class="height addVideo content">
                <p class="title-name"><i class="fa fa-video-camera"></i>Edit video</p>
                <div id="videoForm" class="content_list">
                    {{ Form::open(array('url' => 'channel_' . BaseController::get_channel_id() . '/edit_video', 'class' => 'form-horizontal', 'id' => 'edit_video')) }}
                    <!-- Name -->
                    <div class="control-group">
                        {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                        <div class="controls">
                            {{ Form::text('title', $video->title, array('class' => 'form-control')) }}
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="control-group">
                        {{ Form::label('Description', 'Description', array('class' => 'control-label')) }}

                        <div class="controls">
                            {{ Form::text('description', $video->description, array('class' => 'form-control')) }}
                        </div>
                    </div>

                    <div class="control-group">
                        {{ Form::label('collections', 'Collections', array('class' => 'control-label')) }}

                        <select multiple class="form-control" id="collections" name="collections">
                            @foreach($collections as $collection)
                                <option value="{{ $collection->id }}">{{ $collection->title }}</option>
                            @endforeach
                        </select>

                        @foreach($video_in_collections as $video_in_collection)
                            <script>
                                $('#collections option[value="{{ $video_in_collection->collection_id }}"]').attr('selected', 'selected');
                            </script>
                        @endforeach
                    </div>

                    <div class="hide-inputs">
                        {{Form::hidden('file_name','',array('id' => 'filename'))}}
                        {{Form::hidden('video_format','', array('id' => 'video-format'))}}
                        {{Form::hidden('id', $video->id, array('id' => 'video-format'))}}
                    </div>

                    <div class="edited"></div>

                    <!-- Login button -->
                    <div class="input-group saveBtn">
                        <div class="controls">
                            {{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
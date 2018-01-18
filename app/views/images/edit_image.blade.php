<div class="videoRight">
    <div class="center-block height" id="content-wrap">
        <div class="col-md-6 height" id="upload" style="padding-right: 0px!important;">

            {{ Form::open(array('url' => 'channel_' . BaseController::get_channel_id() . '/edit_image', 'class' => 'form-horizontal', 'id' => 'edit_image_form')) }}
            <div class="height addVideo content">
                <table width='100%'><tr>
                    <td><p class="title-name"><i class="fa fa-picture-o"></i>Edit Image</p></td>
                    <td align='right'>
                        <div class="saveBtn">{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}</div>
                        <div class="cancelBtn"><a href = "javascript:void(0)" class="btn btn-inverse cancelEdit">Cancel</a></div>
                    </td>
                </tr></table>

                <div id="videoForm" class="content_list">
                    <div class="control-group">
                        {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                        <div class="controls">
                            {{ Form::text('title', $video->title, array('class' => 'form-control')) }}
                        </div>
                    </div>

                    <div class="control-group">
                        {{ Form::label('Folders', 'Folders', array('class' => 'control-label')) }}

                        <select multiple class="form-control" id="collections" name="collections" style='height:200px;'>
                            @foreach($collections as $collection)
                                <option value="{{ $collection->id }}">{{ $collection->title }}</option>
                            @endforeach
                        </select>

                        @foreach($video_in_collections as $video_in_collection)
                            <script>
                                $('#collections option[value="{{ $video_in_collection->folder_id }}"]').attr('selected', 'selected');
                            </script>
                        @endforeach
                    </div>

                    <div class="hide-inputs">
                        {{Form::hidden('file_name','',array('id' => 'filename'))}}
                        {{Form::hidden('video_format','', array('id' => 'video-format'))}}
                        {{Form::hidden('id', $video->id, array('id' => 'video-format'))}}
                    </div>

                    <div class="edited"></div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

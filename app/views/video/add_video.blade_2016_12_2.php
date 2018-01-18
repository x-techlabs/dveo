<div id="videoForm" class="col-md-6 VideoFormData">

    {{ Form::open(array('id' => 'addvideoformid', 'name' => 'addvideoformname', 'url' => 'channel_' . BaseController::get_channel_id() . '/addVideo', 'class' => 'form-horizontal saveVideo')) }}
    <!-- Name -->
    <div class="control-group">
        {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
        <div class="controls">
            {{ Form::text('title', $title, array('class' => 'form-control', 'id' => 'videoname')) }}
        </div>
    </div>
    <!-- Password -->
    <div class="control-group">
        {{ Form::label('description', 'Description', array('class' => 'control-label')) }}

        <div class="controls">
            {{ Form::text('description', '', array('class' => 'form-control')) }}
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('collections', 'Collections', array('class' => 'control-label')) }}

        <select multiple class="form-control" id="collections" name="collections">
            @foreach($collections as $collection)
                <option value="{{ $collection->id }}">{{ $collection->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="hide-inputs">
        {{ Form::hidden('encoded_video_id', '', array('id' => 'encoded-video-id')) }}
        {{ Form::hidden('file_name', '', array('id' => 'filename')) }}
        {{ Form::hidden('video_format', '', array('id' => 'video-format')) }}
    </div>
    <!-- Login button -->
    <div class="input-group saveBtn">
        <div class="controls">
            {{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
        </div>
    </div>

    {{ Form::close() }}
</div>
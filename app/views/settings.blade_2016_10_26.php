@extends('template.template')

@section('content')

<div class="settings height content">
    <div class="title-name">
        <i class="fa fa-cog"></i>
        <div class="title">Settings</div>
    </div>
    <div class="clear"></div>

    <div class="settings_wrapper content_list">
        <div class="col-md-4">
            <ul class="tabs">
                <li class="active"><a href="#tab1">General</a></li>
                <li><a href="#tab2">Watch Live</a></li>
                <li><a href="#tab3">Tab 3</a></li>
            </ul>
        </div>
        <div id="content" class="col-md-8 tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="image_control">
                    <img onerror="$('.image_control img').attr('src', '{{asset('images/noLogo.png')}}')" src="http://prolivestream.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}?{{ md5($channel['updated_at']) }}" class="logo1 col-md-4">
                    <div class="col-md-8">
                        <div class="logoLoader">
                            <span class="logoLoading"></span>
                        </div>
                        {{ Form::open(array(
                            'url' => 'https://prolivestream.s3-us-west-2.amazonaws.com/',
                            'class' => 'form-horizontal amazon_form_logo',
                            'enctype' => 'multipart/form-data'
                            )) }}
                        {{ Form::file('file', array('id' => 'fileupload', 'data-url' => 'server/php/')) }}
                        {{ Form::hidden('key', 'uploads', array('id' => 'key')) }}
                        {{ Form::hidden('acl', 'public-read') }}
                        {{ Form::hidden('AWSAccessKeyId', 'AKIAIDGRDUJ7ZG5DNJEA') }}
                        {{ Form::hidden('Policy', 'policy', array('id' => 'policy')) }}
                        {{ Form::hidden('Signature', 'signature', array('id' => 'signature')) }}
                        {{ Form::close() }}
                    </div>
                    <div class="clear"></div>
                </div>

                {{ Form::open(array(
                'url' => 'channel_' . BaseController::get_channel_id() . '/edit_settings',
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'id' => 'settings'
                )) }}

                <div class="control-group">
                    {{ Form::label('title', 'Title', array('class' => 'control-label')) }}
                    <div class="controls">
                        {{ Form::text('title', $channel['title'], array('class' => 'form-control z-index-1', 'id' => 'title')) }}
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('formats', 'Video Formats', array('class' => 'control-label format')) }}
                    <label class="radio fl">
                        {{ Form::radio('format', 'hd', $format['hd'], array('class' => 'custom-radio formatInput', 'id' => 'optionsRadios1', 'data-toggle' => 'radio')) }}
                        <span class="icons">
                        <span class="icon-unchecked"></span>
                        <span class="icon-checked"></span>
                    </span>
                        HD Video Format
                    </label>
                    <label class="radio fl">
                        {{ Form::radio('format', 'sd', $format['sd'], array('class' => 'custom-radio formatInput', 'id' => 'optionsRadios1', 'data-toggle' => 'radio')) }}
                        <span class="icons">
                        <span class="icon-unchecked"></span>
                        <span class="icon-checked"></span>
                    </span>
                        SD Video Format
                    </label>
                    <div class="clear"></div>
                </div>

                <div class="control-group">
                    {{ Form::label('timezone', 'Timezones', array('class' => 'control-label')) }}
                    <div class="controls">
                        <select name="timezone" class="form-control select select-inverse select-block mbl timezone" id="timezone">
                            @foreach($timezones as $key => $timezone)
                                @if($key == $selectedTimezone)
                                    <option value="{{ $key }}" selected>{{ $timezone }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $timezone }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="edited"></div>
                <div class="input-group saveBtn">
                    <div class="controls">
                        {{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
                    </div>
                </div>

                {{ Form::close() }}
            </div>


            <div class="tab-pane" id="tab2">

                {{ Form::open(array(
               'url' => 'channel_' . BaseController::get_channel_id() . '/set_stream_url',
               'class' => 'form-horizontal',
               'enctype' => 'multipart/form-data',
               'id' => 'set_stream'
               )) }}

                <div class="control-group">
                    {{ Form::label('streamUrl', 'Currect Stream URL (Watch Now!):', array('class' => 'control-label')) }}
                 </div>

                <div class="control-group">
                    {{ Form::label('streamUrl', BaseController::get_channel()['stream_url'], array('class' => 'control-label', 'id' => 'stream_url_label')) }}
                </div>

                <div class="control-group">
                    {{ Form::label('streamUrl', 'New Stream URL', array('class' => 'control-label')) }}
                    <div class="controls">
                        {{ Form::text('stream_url', '', array('class' => 'form-control z-index-1', 'id' => 'stream_url')) }}
                    </div>
                </div>


                <div class="streamSet"></div>
                <div class="input-group saveBtn">
                    <div class="controls">
                        {{ Form::submit('Set a New Stream URL', array('class' => 'btn btn-inverse')) }}
                    </div>
                </div>

                {{ Form::close() }}

            </div>
            <div class="tab-pane" id="tab3">
                Tab 3
            </div>
        </div>
    </div>
</div>

@stop
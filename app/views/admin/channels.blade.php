<div class="overflow">
    <div class="folder">
        <i class="fa fa-folder-open-o fa-lg"></i>
        <span>Channels</span>
        <button type="button" class="btn btn-default fr add_channel" data-toggle="modal" data-target="#myModal" title="Create new channel">
            <i class="fa fa-lg fa-plus"></i>
        </button>
        <div class="form-group fr">
            {{ Form::text('search', '', array('class' => 'form-control', 'id' => 'search', 'placeholder' => 'Search channel')) }}
          </div>
        <div class="clear"></div>
    </div>
</div>

<div class="overflow">
    <div class="divTable">
        <div class="divTableRow divTableHead">
            <div class="divTableCell">#</div>
            <div class="divTableCell">Channel</div>
            <div class="divTableCell">DVEO</div>
            <div class="divTableCell">Stream</div>
            <div class="divTableCell">Company</div>
            <div class="divTableCell">Video format</div>
            <div class="divTableCell">Timezone</div>
            <div class="divTableCell">Storage</div>
            <div class="divTableCell">Actions</div>
        </div>
        @foreach($channels as $channel)
            <div class="divTableRow" id="tableData" data-row_id="{{ $channel['id'] }}">
                <div class="divTableCell" id="channel_id">{{ $channel['id'] }}</div>
                <div class="divTableCell" id="channel_title">{{ $channel['title'] }}</div>
                <div class="divTableCell" id="channel_dveo_id">{{ $channel['dveo_id'] }}</div>
                <div class="divTableCell" id="channel_stream">{{ $channel['stream'] }}</div>
                <div class="divTableCell" id="channel_company_id">{{ $channel['company_id'] }}</div>
                <div class="divTableCell" id="channel_format">{{ $channel['format'] }}</div>
                <div class="divTableCell" id="channel_timezone">{{ $channel['timezone'] }}</div>
                <div class="divTableCell" id="channel_storage">{{ $channel['storageSize'] }}</div>
                <!-- <div class="divTableCell" id="channel_storage">{{ $channel['storage_human'] }}</div> -->
                <div class="divTableCell">
                    <button type="button" class="btn btn-default edit_channel" data-toggle="modal" data-target="#myModal" data-channel_id="{{ $channel['id'] }}" title="Edit channel"><i class="fa fa-lg fa-pencil"></i></button>
                    <button type="button" class="btn btn-default delete_channel" data-channel_id="{{ $channel['id'] }}" title="Delete channel"><i class="fa fa-lg fa-trash-o"></i></button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => 'editChannel', 'role' => 'form', 'id' => 'channel')) }}
                    <div class="form-group">
                        {{ Form::label('title', 'Channel') }}
                        {{ Form::text('title', '', array('class' => 'form-control', 'id' => 'title', 'placeholder' => 'Channel name', 'autocomplete' => 'off')) }}
                    </div>
                 <!--    <div class="form-group">
                        {{ Form::label('dveo_id', 'DVEO') }}
                        {{ Form::select('dveo_id', $dveos, '', array('class' => 'form-control', 'id' => 'dveo_id')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('stream', 'Stream') }}
                        <select name="stream" class="form-control" id="stream"></select>
                    </div> -->
                    <div class="form-group">
                        {{ Form::label('company_id', 'Companies') }}
                        {{ Form::select('company_id', $companies, '', array('class' => 'form-control', 'id' => 'company_id')) }}
                    </div>
                    <div class="form-group">
                        <label class="radio-inline">
                            {{ Form::radio('format', 'hd', false, array('id' => 'hd')) }} HD video format
                        </label>
                        <label class="radio-inline">
                            {{ Form::radio('format', 'sd', false, array('id' => 'sd')) }} SD video format
                        </label>
                        <p class="radio-inline noFormat"></p>
                    </div>
                    <div class="form-group">
                        {{ Form::label('pl_access', 'Playout Access') }}
                        {{ Form::checkbox('pl_access', 1, null) }}
                    </div>

                	<div class="form-group">
                        {{ Form::label('timezone', 'Timezones') }}
                        <select name="timezone" class="form-control" id="timezone"></select>
                    </div>
                    {{ Form::hidden('id', '', array('id' => 'channel_id')) }}
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close_channel" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save_channel">Save changes</button>
            </div>
        </div>
    </div>
</div>
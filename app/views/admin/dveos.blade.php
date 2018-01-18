<div class="overflow">
    <div class="folder">
        <i class="fa fa-folder-open-o fa-lg"></i>
        <span>DVEO</span>
        <button type="button" class="btn btn-default fr add_dveo" data-toggle="modal" data-target="#myModal" title="Create new dveo">
            <i class="fa fa-lg fa-plus"></i>
        </button>
        <div class="form-group fr">
            {{ Form::text('search', '', array('class' => 'form-control', 'id' => 'search', 'placeholder' => 'Search company')) }}
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="overflow">
    <div class="divTable">
        <div class="divTableRow divTableHead">
            <div class="divTableCell">#</div>
            <div class="divTableCell">Ip</div>
            <div class="divTableCell">Actions</div>
        </div>
        @foreach($dveos as $dveo)
            <div class="divTableRow" id="tableData" data-row_id="{{ $dveo['id'] }}">
                <div class="divTableCell" id="dveo_id">{{ $dveo['id'] }}</div>
                <div class="divTableCell" id="dveo_ip">{{ $dveo['ip'] }}</div>
                <div class="divTableCell">
                    <button type="button" class="btn btn-default edit_dveo" data-toggle="modal" data-target="#myModal" data-dveo_id="{{ $dveo['id'] }}" title="Edit dveo"><i class="fa fa-lg fa-pencil"></i></button>
                    <button type="button" class="btn btn-default delete_dveo" data-dveo_id="{{ $dveo['id'] }}" title="Delete dveo"><i class="fa fa-lg fa-trash-o"></i></button>
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
                {{ Form::open(array('url' => 'editDveo', 'role' => 'form', 'id' => 'dveo')) }}
                <div class="form-group">
                    {{ Form::label('ip', 'DVEO ip') }}
                    <p class="radio-inline error" id="ip_error"></p>
                    {{ Form::text('ip', '', array('class' => 'form-control', 'id' => 'ip', 'placeholder' => 'Ip', 'autocomplete' => 'off')) }}
                </div>
                {{ Form::hidden('id', '', array('id' => 'dveo_id')) }}
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close_dveo" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save_dveo">Save changes</button>
            </div>
        </div>
    </div>
</div>
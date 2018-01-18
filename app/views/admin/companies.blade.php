<div class="overflow">
    <div class="folder">
        <i class="fa fa-folder-open-o fa-lg"></i>
        <span>Companies</span>
        <button type="button" class="btn btn-default fr add_company" data-toggle="modal" data-target="#myModal" title="Create new company">
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
            <div class="divTableCell">Name</div>
            <div class="divTableCell">Actions</div>
        </div>
        @foreach($companies as $company)
        <div class="divTableRow" id="tableData" data-row_id="{{ $company['id'] }}">
            <div class="divTableCell" id="company_id">{{ $company['id'] }}</div>
            <div class="divTableCell" id="company_name">{{ $company['name'] }}</div>
            <div class="divTableCell">
                <button type="button" class="btn btn-default edit_company" data-toggle="modal" data-target="#myModal" data-company_id="{{ $company['id'] }}" title="Edit company"><i class="fa fa-lg fa-pencil"></i></button>
                <button type="button" class="btn btn-default delete_company" data-company_id="{{ $company['id'] }}" title="Delete company"><i class="fa fa-lg fa-trash-o"></i></button>
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
                {{ Form::open(array('url' => 'editCompany', 'role' => 'form', 'id' => 'company')) }}
                    <div class="form-group">
                        {{ Form::label('name', 'Company name') }}
                        {{ Form::text('name', '', array('class' => 'form-control', 'id' => 'name', 'placeholder' => 'Company name', 'autocomplete' => 'off')) }}
                    </div>
                    {{ Form::hidden('id', '', array('id' => 'company_id')) }}
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close_company" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save_company">Save changes</button>
            </div>
        </div>
    </div>
</div>
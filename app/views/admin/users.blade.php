<div class="overflow">
    <div class="folder">
        <i class="fa fa-folder-open-o fa-lg"></i>
        <span>Users</span>
        <button type="button" class="btn btn-default fr add_user" data-toggle="modal" data-target="#myModal" title="Create new user">
            <i class="fa fa-lg fa-plus"></i>
        </button>
        <div class="form-group fr">
            {{ Form::text('search', '', array('class' => 'form-control', 'id' => 'search', 'placeholder' => 'Search user')) }}
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="overflow">
    <div class="divTable">
        <div class="divTableRow divTableHead">
            <div class="divTableCell">#</div>
            <div class="divTableCell">Username</div>
            <div class="divTableCell">Email</div>
            <div class="divTableCell">Company</div>
            <div class="divTableCell">Channels</div>
            <div class="divTableCell">Permissions</div>
            <div class="divTableCell">Actions</div>
        </div>
        @foreach($users as $user)
        <div class="divTableRow" id="tableData" data-row_id="{{ $user['id'] }}">
            <div class="divTableCell" id="user_id">{{ $user['id'] }}</div>
            <div class="divTableCell" id="user_username">{{ $user['username'] }}</div>
            <div class="divTableCell" id="user_email">{{ $user['email'] }}</div>
            <div class="divTableCell" id="user_company_id">{{ $user['company_id'] }}</div>
            <div class="divTableCell" id="user_channel_id">{{ $user['channels'] }}</div>
            <div class="divTableCell" id="user_type">{{ $user['type'] }}</div>
            <div class="divTableCell">
                @if(!empty($user->token))
                <span class="label label-warning">Inactive</span>
                @else
                <span class="label label-success">Active</span>
                @endif
                <button type="button" disabled class="btn btn-default restore_password" data-user_id="{{ $user['id'] }}" title="Restore password"><i class="fa fa-envelope"></i></button>
                <button type="button" class="btn btn-default edit_user" data-toggle="modal" data-target="#myModal" data-user_id="{{ $user['id'] }}" title="Edit user"><i class="fa fa-lg fa-pencil"></i></button>
                <button type="button" class="btn btn-default delete_user" data-user_id="{{ $user['id'] }}" title="Delete user"><i class="fa fa-lg fa-trash-o"></i></button>
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
                {{ Form::open(array('url' => 'editUser', 'role' => 'form', 'id' => 'user')) }}
                    <div class="form-group">
                        {{ Form::label('username', 'Username') }}
                        <p class="radio-inline error" id="username_error"></p>
                        {{ Form::text('username', '', array('class' => 'form-control', 'id' => 'username', 'placeholder' => 'Username', 'autocomplete' => 'off')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('password', 'Password') }}
                        {{ Form::password('password', array('class' => 'form-control', 'id' => 'password', 'placeholder' => 'Password')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('email', 'Email') }}
                        <p class="radio-inline error" id="email_error"></p>
                        {{ Form::email('email', '', array('class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email', 'autocomplete' => 'off')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('company_id', 'Companies') }}
                        {{ Form::select('company_id', $companies, '', array('class' => 'form-control', 'id' => 'company_id')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('channel_id', 'Channel') }}
                        {{ Form::select('channel_id[]', array(), '', array('class' => 'select_channel', 'id' => 'channel_id', 'multiple' => 'multiple')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('access', 'Playout Access') }}
                        {{ Form::checkbox('access', 1, null) }}
                    </div>

                <div class="form-group">
                        {{ Form::label('permissions', 'Permissions') }}
                        <select name="permissions" class="form-control" id="permissions" multiple>
                            <optgroup label="Administrator">
                                <option value="2">Global Admin</option>
                            </optgroup>
                            <optgroup label="User">
                                <option value="4">Company</option>
                                <option value="8">Channel</option>
                                <option value="16">Media</option>
                                <option value="32">Payment</option>
                            </optgroup>
                        </select>
                    </div>
                    {{ Form::hidden('id', '', array('id' => 'user_id')) }}
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close_user" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save_user">Save changes</button>
            </div>
        </div>
    </div>
</div>
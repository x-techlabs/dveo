<div id="addPlaylistHide">
    {{ HTML::script('js/playlist/add_playlist.js') }}

    <div class="col-md-4 height-inherit" id="add-playlist">

        <div class="height-inherit addPlaylist content">

            <div class="title-name">
                <i class="fa fa-plus"></i>
                <div class="title">Add playlist</div><p id="add_playlist_trt" style="text-align: left; margin: 0; overflow: hidden;"></p>
                <div class="input-group saveBtnTop">
                    <div class="controls">
                        <button class="btn btn-inverse" onclick="add_playlist_button_clicked()" type="button">Save</button>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="videoHeight content_list">
                {{ Form::open(array(
                    'url' => 'channel_'.BaseController::get_channel_id().'/add_to_playlist',
                    'class' => 'form-horizontal height-inherit add-playlist-form',
                    'enctype' => 'multipart/form-data',
                    'id' => 'add_to_playlist1'
                    )) }}
                <!-- Name -->
                <div class="control-group">
                    {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                    <div class="controls">
                        {{ Form::text('title', '', array('class' => 'form-control z-index-1', 'id' => 'title')) }}
                    </div>
                </div>

                <!-- Password -->
                <div class="control-group">
                    {{ Form::label('Description', 'Description', array('class' => 'control-label')) }}

                    <div class="controls">
                        {{ Form::text('description', '', array('class' => 'form-control z-index-1', 'id' => 'description')) }}
                    </div>
                </div>
                <div class="drag-and-drop" >
                    <div class="col-md-12" id="drag-and-drop"></div>
                    <div class="clear"></div>
                </div>

                <div class="edited"></div>

                <!-- Login button -->
                <div class="input-group saveBtn">
                    <div class="controls">
                        {{ Form::button('Save', array('class' => 'btn btn-inverse', 'onclick' => 'add_playlist_button_clicked()')) }}
<!--                        {{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}  -->

                        <p id="success" style="color:#90111a;"></p>
                    </div>
                </div>

                {{ Form::close() }}

                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
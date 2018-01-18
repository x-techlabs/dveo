@extends('template.template')

@section('content')

<div class="row center-block height" id="contnet-wrap">

    <div class="col-md-12 list-wrap height-inherit" id="playlists">

        <div class="height-inherit playlists content">
            <div class="title-name">
                <i class="fa fa-play-circle"></i>
                <div class="title">TV App categories:</div>
                <div class="input-group searchPlay">
                    <input type="text" class="form-control" placeholder="Search" id="search-query-3">
                    <span class="input-group-btn">
                        <button type="submit" class="btn"><span class="fui-search"></span></button>
                    </span>
                </div>
                
                <div> 
                <a  style="margin-left:20px;" href="/channel_{{ $channel['id'] }}/tvweb_settings" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvweb_settings" title="Settings">Settings</a>
                </div>
             
                <div>
                <a style="margin-left:20px;" href="/channel_{{ $channel['id'] }}/tvweb_playlists" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvweb_manage_videos" title="Manage Content">Manage Content</a>
                </div>
               
                
                <div class="clear"></div>
            </div>

            <div class="row center-block list content_list" id="container_content">
            
            
	            <div style="align" class="tab-pane">
	
					{{ Form::open(array( 'url' => 'channel_' .
					BaseController::get_channel_id() . '/tvweb_settings_update', 'class' =>
					'form-horizontal', 'enctype' => 'multipart/form-data', 'id' =>
					'set_about_us' )) }}

					
					<div class="control-group">
						{{ Form::label('streamUrl', 'Setting 1', array('class' => 'control-label')) }}
						<div class="control-group">{{ Form::textarea('setting1', $tvweb_setting1, 
						['size' => '107x5'], array('class' =>'form-control900', 'id' => 'setting1_id')) }}</div>
					</div>
									
					<br>
					
					<div class="control-group">
						<div class="controls">{{ Form::submit('Save Setting1',
							array('class' => 'btn btn-inverse')) }}</div>
					</div>
				
					{{ Form::close() }}
	
				</div>
           

            </div>
        </div>
    </div>
</div>


@stop
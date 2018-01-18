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
                <a  style="margin-left:20px;" href="/channel_{{ $channel['id'] }}/tvapp_about_us" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvapp_about_us" title="About US">About US</a>
                </div>
             
                <div>
                <a style="margin-left:20px;" href="/channel_{{ $channel['id'] }}/tvapp_playlists" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvapp_playlists" title="Manage Playlists">Manage Playlists</a>
                </div>
                 
                <div>
                <a href="/channel_{{ $channel['id'] }}/tvapp_live" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvapp_live" title="Live">Live</a>
                </div>
                
                <div class="clear"></div>
            </div>

            <div class="row center-block list content_list" id="container_content">
            
            
	            <div style="align" class="tab-pane">
	
					{{ Form::open(array( 'url' => 'channel_' .
					BaseController::get_channel_id() . '/tvapp_live_update', 'class' =>
					'form-horizontal', 'enctype' => 'multipart/form-data', 'id' =>
					'tvapp_live' )) }}
			
					<div class="control-group">
						{{ Form::label('title', 'Title', array('class' =>
						'control-label')) }}
						<div class="control-group">{{ Form::text('tvapp_title', $tvapp_title, array('class' =>
							'form-control900', 'id' => 'tvapp_title_id')) }} </div>
					</div>
					
					<div class="control-group">
						{{ Form::label('description', 'Description', array('class' =>
						'control-label')) }}
						<div class="control-group">{{ Form::textarea('tvapp_description', $tvapp_description, 
						['size' => '107x2'], array('class' =>'form-control900', 'id' => 'tvapp_description_id')) }}</div>
					</div>
									
					<div class="control-group">
						{{ Form::label('liveStreamURL', 'Live Stream URL', array('class' => 'control-label')) }}
						<div class="control-group">{{ Form::text('tvapp_live_stream_url', $tvapp_live_stream_url, array('class' =>'form-control900', 
						'id' => 'tvapp_live_stream_url_id')) }}</div>
					</div>
					
					
					<br>
					
					<div class="control-group">
						<div class="controls">{{ Form::submit('Save Live Stream Settings',
							array('class' => 'btn btn-inverse')) }}</div>
					</div>
				
					{{ Form::close() }}
	
				</div>
           

            </div>
        </div>
    </div>

</div>


@stop



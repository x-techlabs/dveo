<div id="editPlaylistHide">
  {{--{{ HTML::style('css/upload.css') }}--}}
  {{--{{ HTML::style('css/style.css') }}--}}
  {{--{{ HTML::style('css/styles.css') }}--}}
  {{ HTML::script('js/tvapp/script.js') }}
  {{ HTML::script('js/tvapp/tvapp_add_playlist.js') }}

  <script>
      function TogglestreamUrl(me)
      {
          $('#su_group').hide();
          if (me.value=='2' || me.value=='4' || me.value=='5') $('#su_group').show();
      }
  </script>

  <div class="" id="add-playlist">

    <div class="addPlaylist height content">
      <div class="title-name for_left_edit_part">
	  
        <!--                <i class="fa fa-play-circle"></i>  -->
        <div class="title">Edit playlist</div>
		{{ Form::button('Cancel', array('class' => 'btn btn-inverse', 'id' => 'tvapp_edit_playlist_cancel')) }}
          {{ Form::open([
            'url' => '/channel_'.$playlist->channel_id.'/tvapp_delete_playlist',
            'class' => 'form-inline',
            'style' => 'display: inline-block; float: left; margin-left:5px',
            'method' => 'POST'
          ])}}
          {{ Form::hidden('tvapp_playlistId', $playlist->id) }}
          {{ Form::submit('Delete', array('class' => 'btn btn-danger tvapp_edit_playlist_delete')) }}
          {{ Form::close() }}

          {{ Form::open([
            'url' => '/channel_'.$playlist->channel_id.'/tvapp_duplicate_playlist',
            'class' => 'form-inline',
            'style' => 'display: inline-block; float: left;margin-left:5px',
            'method' => 'POST'
          ])}}
          {{ Form::hidden('tvapp_playlistId', $playlist->id) }}
          {{ Form::submit('Duplicate', array('class' => 'btn btn-inverse tvapp_edit_playlist_dublicate')) }}
          {{ Form::close() }}
      </div>
      <div class="clear"></div>


      <div class="content_list"> <!-- vinay videoHeightedit removed from class to clear top margin -->
		
		<div class='controls poster_control div_for_poster_part_img'>
			<h4 class='color_green_part'>Poster</h4>
			<img id="real_file_url" onerror="$('.div_for_poster_part_img img').attr('src', '{{asset('images/noLogo.png')}}')" src="https://s3.amazonaws.com/aceplayout/logos-poster/channel_{{ $playlist->channel_id }}_tvapp_playlist_{{ $playlist->id }}.jpg" class="logo1 col-md-4">
			<div class="logoLoader">
			  <span class="logoLoading"></span>
			</div>
		   {{ Form::open(array(
			  'url' => 'https://aceplayout.s3.amazonaws.com/',
			  'class' => 'form-horizontal amazon_playlist_logo',
			  'enctype' => 'multipart/form-data'
			  )) }}
			  {{ Form::file('file', array('id' => 'fileupload', 'data-url' => 'server/php/')) }}
			  {{ Form::hidden('key', 'uploads', array('id' => 'key')) }}
			  {{ Form::hidden('acl', 'public-read') }}
			  {{ Form::hidden('AWSAccessKeyId', 'AKIAJWU4DYR6OMHE2YPQ') }}
			  {{ Form::hidden('Policy', 'policy', array('id' => 'policy')) }}
			  {{ Form::hidden('Signature', 'signature', array('id' => 'signature')) }}
			  {{ Form::close() }}

			  {{ Form::hidden('tvapp_playlist_id', $playlist->id, array('id' => 'tvapp_playlist_id')) }}
			
		</div>
			
		<div class="control-group">
          {{ Form::label('Name', 'Name', array('class' => 'control-label color_green_part')) }}
          <div class="controls">
            {{ Form::text('title', $playlist->title, array('class' => 'form-control', 'id' => 'title')) }}
          </div>
        </div>
		
		<div class="control-group textarea_description_part">
          {{ Form::label('Description', 'Description', array('class' => 'control-label color_green_part')) }}
          <div class="controls">
            {{ Form::textarea('description', $playlist->description, array('class' => 'form-control textarea_edit_part', 'id' => 'description', 'size' => '40x2')) }}
          </div>
        </div>
		
		
		<div class="control-group">
          <p id="add_playlist_trt" style="text-align: left; margin: 0; overflow: hidden;">&nbsp;&nbsp;TRT&nbsp;<img src="{{ URL::to('/') }}/images/time_icon.png" style="margin-top: -4px;"> {{$playlist->time}}</p>
        </div>
	  
	  
	   {{-- Tako menu --}}
		<nav id = "tako_nav"> <a href="#" id="showmenu">Menu <i class="icon-reorder"></i></a>
			<ul class="nav left_nav_part">
				<li class="featured_item">
					<a href="javascript:void(0)">
						Featured Image
					</a>
					<ul class="second-level second_level_edit_page">
						
						<br>
						<div id = "tvapp_image_wrapper" class='controls div_for_feature_image_part'>
							<h4>TV Apps</h4>
							 <img id="real_file_url1" onerror="$('.div_for_feature_image_part img').attr('src', '{{asset('images/noLogo.png')}}')" src="https://s3.amazonaws.com/aceplayout/banners/channel_{{ $playlist->channel_id }}_tvapp_playlist_{{ $playlist->id }}.jpg" class="banner1 col-md-4">
							{{ Form::open(array(
							  'url' => 'https://aceplayout.s3.amazonaws.com/',
							  'class' => 'form-horizontal amazon_playlist_banner',
							  'enctype' => 'multipart/form-data'
							  )) }}
							  {{ Form::file('file', array('id' => 'fileupload1', 'data-url' => 'server/php/')) }}
							  {{ Form::hidden('key', 'uploads', array('id' => 'key1')) }}
							  {{ Form::hidden('acl', 'public-read') }}
							  {{ Form::hidden('AWSAccessKeyId', 'AKIAJWU4DYR6OMHE2YPQ') }}
							  {{ Form::hidden('Policy', 'policy', array('id' => 'policy1')) }}
							  {{ Form::hidden('Signature', 'signature', array('id' => 'signature1')) }}
							  {{ Form::close() }}
						</div>						
						<br>
						<div id = "mobileweb_img_wrapper" class='controls div_for_feature_image_part'>
													
							<h4>Mobile-Web TV</h4>
							<img id="mobileweb_image" onerror="$('#mobileweb_img_wrapper img').attr('src', '{{asset('images/noLogo.png')}}')" src="https://s3.amazonaws.com/aceplayout/banners/channel_{{ BaseController::get_channel_id() }}_mobileweb_playlist_{{ $playlist->id }}.jpg" class="mob_banner1 col-md-4">
						
							 {{ Form::open(array(
								'url' => 'https://aceplayout.s3.amazonaws.com/',
								'class' => 'form-horizontal send_amazon_mobileweb_image_for_playlist',
								'enctype' => 'multipart/form-data'
								)) }}
								{{ Form::file('file', array('id' => 'fileupload4', 'data-url' => 'server/php/')) }}
								{{ Form::hidden('key', 'uploads', array('id' => 'key4')) }}
								{{ Form::hidden('acl', 'public-read') }}
								{{ Form::hidden('AWSAccessKeyId', 'AKIAJWU4DYR6OMHE2YPQ') }}
								{{ Form::hidden('Policy', 'policy', array('id' => 'policy4')) }}
								{{ Form::hidden('Signature', 'signature', array('id' => 'signature4')) }}
								{{ Form::close() }}
							
						</div>
					</ul>
				</li>
				
					<li class="tako_item">
						<a href="javascript:void(0)">Type</a>
						<ul class="second-level second_level_edit_page">
							<div class="control-group padding_space">
								<div class="controls">
									<input type='hidden' id='api_url_exist' value='{{ $api_url_exist }}'>
									{{ Form::select('type', [
									'0' => 'General Playlist',
									'1' => 'Main Videos',
									'2' => 'Featured Videos',
									'3' => 'Latest Videos',
									'4' => 'Most Viewed Videos',
									'5' => 'Most Popular Videos',
									'6' => 'Live Stream Link',
									'7' => 'Text Page'
									],
									$playlist->type,
									array('id' => 'playlist_type', 'class' => 'form-control')
									) }}
								</div>
								<div class="controls">
									<div style='margin-top:5px;' id='viewingComment'></div>
								</div>
							</div>
						</ul>
					</li>
					<li class="tako_item">
						<a href="javascript:void(0)">Level</a>
						<ul class="second-level second_level_edit_page">
							<div class="control-group padding_space">
								<div class="controls">
									<input type='hidden' id='api_url_exist' value='{{ $api_url_exist }}'>
									 {{ Form::select('level', [
									'0' => 'Level 0 (Top level)',
									'1' => 'Level 1',
									'2' => 'Level 2',
									'3' => 'Level 3',
									'4' => 'Level 4',
									'5' => 'Level 5'],
									$playlist->level,
									array('id' => 'playlist_level', 'class' => 'form-control')
									) }}
								</div>
								<div class="controls">
									<div style='margin-top:5px;' id='viewingComment'></div>
								</div>
							</div>
						</ul>
					</li>
					
					
					<li class="featured_item">
						<a href="javascript:void(0)">
							Playlist Layout
						</a>
						<ul class="second-level second_level_edit_page">
							<div id = "tvapp_image_wrapper" class='controls div_for_feature_image_part'>
								<h4>App</h4>
								{{ Form::select('layout', [
								'0' => 'Collection - Linear',
								'1' => 'Collection - Grid',
								'2' => 'MRSS - Live Stream',
								'3' => 'MRSS Collection - Grid',
								],
								$playlist->layout,
								array('id' => 'playlist_layout', 'onchange' => "TogglestreamUrl(this)",'class' =>'form-control' )
								) }}
							</div>

							<div id = "mobileweb_img_wrapper" class='controls div_for_feature_image_part'>
								<h4>Web</h4>
								{{ Form::select('web_layout', [
								'0' => 'Carousel',
								'1' => 'Category Poster',
								],
								$playlist->web_layout,
								array('id' => 'playlist_web_layout','class'=>'form-control')
								) }}
							</div>
						</ul>
					</li>
					
					
					
					
					<li class="tako_item">
						<a href="javascript:void(0)">Viewing</a>
						<ul class="second-level second_level_edit_page">
							<div class="control-group padding_space">
								<div class="controls">
									<input type='hidden' id='api_url_exist' value='{{ $api_url_exist }}'>
									 {{ Form::select('viewing', ['inherit' => 'Same as parent Category', 'free' => 'Free', 'paid' => 'Paid'], $playlist->viewing, array('id' => 'viewing', 'style' => 'width:200px;margin:0 5px 0 5px;height:32px;', 'onchange' => 'OnViewingChanged()','class'=>'form-control')) }} &nbsp;&nbsp;
								</div>
								<div class="controls">
									<div style='margin-top:5px;' id='viewingComment'></div>
								</div>
							</div>
						</ul>
					</li>
					
					<li class="tako_item">
						<a href="javascript:void(0)">Platforms</a>
						<ul class="second-level second_level_edit_page">
							<div class="control-group padding_space">
								<div class="controls">
									<input type='hidden' id='api_url_exist' value='{{ $api_url_exist }}'>
									 {{ Form::select('platforms',
										  array_merge(['0' => 'All'], $platforms->lists('title', 'id')),
										  $playlist->platforms->lists('id'),
										  [
											'id' => 'platforms',
											'class' => 'js-select2-tags',
											'multiple'=>'multiple',
											'style' => 'width: 100%'
										  ]
										) }}
								</div>
								<div class="controls">
									<div style='margin-top:5px;' id='viewingComment'></div>
								</div>
							</div>
						</ul>
					</li>
					<li class="tako_item">
						<a href="javascript:void(0)">MRSS Feed / Stream URL</a>
						<ul class="second-level second_level_edit_page mrss_feed_for_p">
							<div class="control-group padding_space">
								<div class="controls">
									<input type='hidden' id='api_url_exist' value='{{ $api_url_exist }}'>
									{{ Form::text('stream_url', $playlist->stream_url, array('class' => 'form-control', 'id' => 'stream_url')) }}
									<p>If this feed is available, actual child nodes in the tree are ignored.</p>
								</div>
								<div class="controls">
									<div style='margin-top:5px;' id='viewingComment'></div>
								</div>
							</div>
						</ul>
					</li>
				</ul>
			</nav>
			{{-- End tako --}}       
		{{ Form::open(array(
		'url' => 'tvapp_edit_playlist',
		'class' => 'form-horizontal height-inherit add-playlist-form',
		'enctype' => 'multipart/form-data',
		'id' => 'tvapp_edit_playlist',
		'method' => 'post'
		)) }}
        <div class="control-group for_line_btn" style='margin-top:43px;'>
          {{ Form::submit('Save', array('class' => 'btn btn-inverse btn_for_save_edit_form')) }}
        </div>
        {{ Form::close() }}
        <div class="control-group">
          
        </div>
								

      </div>
    </div>
  </div>
</div>

<script language='javascript'>
  function OnViewingChanged()
  {
      v = $('#viewing').val();
      if (v == 'inherit') $('#viewingComment').html('End User will be able to see the playlist as "Free" or "Paid" as defined by the rules of the category'); 
      else if (v == 'free') $('#viewingComment').html('End User will be able to see the playlist without having to subscribe to the service.'); 
      else if (v == 'paid') 
      {
          if ($('#api_url_exist').val()=='0') 
          {
              alert('API URL is undefined. Please specify API URL in settings -> general -> API URL');
              $('#viewing').val('inherit');
              OnViewingChanged();
              return;
          }
          $('#viewingComment').html('End User will need to subscribe to your service to be able to see the playlist.'); 
      }
  }
  OnViewingChanged();
</script>
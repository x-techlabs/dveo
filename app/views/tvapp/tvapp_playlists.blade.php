<div id="addTvappPlaylistHide">
    {{ HTML::script('js/tvapp/tvapp_add_playlist.js') }}

    <div class="" id="add-playlist">

        <div class="height-inherit addPlaylist content">

            <div class="title-name">
                <i class="fa fa-plus"></i>
                <!-- change -->
                <div class="title title_parth_for_add_low">
                  <!-- Add tvapp playlist -->
                </div>
                <p id="add_playlist_trt" style="text-align: left; margin: 0; overflow: hidden;"></p>
            </div>

            <div class="clear"></div>

            <div class="videoHeight content_list video_div_parth">
                {{ Form::open(array(
                    'url' => 'tvapp_add_to_playlist',
                    'class' => 'form-horizontal height-inherit add-playlist-form',
                    'enctype' => 'multipart/form-data',
                    'id' => 'tvapp_add_to_playlist'
                    )) }}

                  <div class="coll_actions control-group" style='margin-top:10px;'>
                      <div class="controls">
                          {{ Form::submit('Save', array('class' => 'btn btn-inverse savePlaylist')) }}
                          &nbsp;
                          <div class="cancelBtn">
                              {{ Form::button('Cancel', array('class' => 'btn btn-inverse', 'id' => 'tvapp_edit_playlist_cancel')) }}
                          </div>
                          <p id="success" style="color:#90111a;"></p>
                      </div>
                  </div>
                <!-- Name -->
                <div class="control-group">
                    {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                    <div class="controls">
                        {{ Form::text('title', '', array('class' => 'form-control z-index-1 input_for_auto_required', 'id' => 'title')) }}
                    </div>
                </div>

                <!-- vinay description changed from text to textarea -->
                <div class="control-group">
                    {{ Form::label('Description', 'Description', array('class' => 'control-label')) }}
                    <div class="controls">
                        {{ Form::textarea('description', '', array('class' => 'form-control z-index-1', 'id' => 'description', 'size' => '40x2')) }}
                    </div>
                </div>
				
				
				
				
				
				
				
				
				{{-- Tako menu --}}
		<nav id = "tako_nav"> <a href="#" id="showmenu">Menu <i class="icon-reorder"></i></a>
			<ul class="nav left_nav_part_add_playlist">
				
				
					<li class="tako_item">
						<a href="javascript:void(0)">MRSS Feed / Stream URL</a>
						<ul class="second-level second_level_edit_page_add_pl" style='height:140px'>
							<div class="control-group padding_space">
								<div class="controls">
									
									 {{ Form::text('stream_url', '', array('class' => 'form-control', 'id' => 'stream_url')) }}
									 <p>If this feed is available, actual child nodes in the tree are ignored</p>
								</div>
								<div class="controls">
									<div style='margin-top:5px;' id='viewingComment'></div>
								</div>
							</div>
						</ul>
					</li>
					<li class="tako_item">
						<a href="javascript:void(0)">Type</a>
						<ul class="second-level second_level_edit_page_add_pl">
							<div class="control-group padding_space">
								<div class="controls">
									{{ Form::select('type', [
								   '0' => 'General Playlist',
								   '1' => 'Top Slider Videos',
								   '2' => 'Featured Videos',
								   '3' => 'Latest Videos',
								   '4' => 'Most Viewed Videos',
								   '5' => 'Most Popular Videos',
								   '6' => 'Live Stream Link',
								   '7' => 'Text Page'
								   ],
								   0,
								   array('id' => 'playlist_type','class'=>'form-control')
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
						<ul class="second-level second_level_edit_page_add_pl">
							<div class='controls'>
								<h4>Web</h4>
								{{ Form::select('web_layout', [
								'0' => 'Carousel',
								'1' => 'Category Poster',
								],
								'',
								array('id' => 'playlist_web_layout','class'=>'form-control')
								) }}
							</div>
						</ul>
					</li>
					
					
					
					
					<li class="tako_item">
						<a href="javascript:void(0)">Viewing</a>
						<ul class="second-level second_level_edit_page_add_pl">
							<div class="control-group padding_space">
								<div class="controls">
									
									{{ Form::select('viewing', ['inherit' => 'Same as parent Category', 'free' => 'Free', 'paid' => 'Paid'], '', array('id' => 'viewing','class' => 'form-control' , 'style' => 'width:200px;margin:0 5px 0 5px;height:32px;', 'onchange' => 'OnViewingChanged()')) }} &nbsp;&nbsp;
								</div>
								<div class="controls">
									<div style='margin-top:5px;' id='viewingComment'></div>
								</div>
							</div>
						</ul>
					</li>
					
					<li class="tako_item">
						<a href="javascript:void(0)">Platforms</a>
						<ul class="second-level second_level_edit_page_add_pl">
							<div class="control-group padding_space">
								<div class="controls">
									
									{{ Form::select('platforms',
									  array_merge(['0' => 'All'], $platforms->lists('title', 'id')),
									  '',
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
					<li class="tako_item for_select">
						
						<a href="javascript:void(0)" style='font-size:12px'>Create Playlist from Folders</a>
						<ul class="second-level second_level_edit_page_add_pl mrss_feed_for_p">
							<div class="control-group padding_space">
								<div class="controls div_for_playlist_categories">
									
									Yes
										  <input type='radio' id='show_playlist_categories' name='playlist_category' >
									   No
										<input type='radio' name='playlist_category' checked id='dont_show_playlist_categories'> 
										<br>
										
										 <select class='js-select2-tags playlist_category_select form-control'>
										  @foreach($collections as $pc)
											<option value={{$pc->id}} data-name="{{$pc->title}}">{{$pc->title}}</option>
										  @endforeach
										</select> 
								</div>
								<div class="controls">
									<div style='margin-top:5px;' id='viewingComment'></div>
								</div>
							</div>
						</ul>
						
					</li>
					<li class="tako_item li_for_closed">
						
						<a href="javascript:void(0)" style='font-size:12px'>Create All Playlists from All Folders</a>
						<ul class="second-level second_level_edit_page_add_pl mrss_feed_for_p">
							<div class="control-group padding_space">
								<div class="controls create_all_playlist_from_all_folders_div_part">
									
									Yes
									  <input type='radio' id='create_playlists_from_all_playlists' value='yes' name='create_playlist_from_all_folder' >
								   No
									<input type='radio' name='create_playlist_from_all_folder' value='no' class='active_for_all_playlists' checked id='dont_create_playlists_from_all_playlists'> 
									<br>
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
				
				
				
				
				
				
				
				
				
				
				
				
				

				

				
				
				


            
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    setTimeout(function(){
      var shelf_row = $(".shelf_row_number").val();
      if(shelf_row == 1){

        $(".title_parth_for_add_low").append("New Top Shelf Playlist");
      }
      else{
        $(".title_parth_for_add_low").append("New Low Shelf Playlist");

      }
    },100)
  })
</script>
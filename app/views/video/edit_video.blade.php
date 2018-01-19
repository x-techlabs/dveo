<div class="videoRight">
    {{ HTML::script('js/videos/script.js') }}
    <div class="row center-block height" id="contnet-wrap">
        <div class="col-md-6 height" id="upload" style="padding-right: 0px!important;">

            {{-- TV app image --}}
            <div class="col-md-8">
                {{ Form::open(array(
				'url' => 'https://dveo.s3.amazonaws.com/',
				'class' => 'form-horizontal amazon_playlist_tvapp_image',
				'enctype' => 'multipart/form-data'
				)) }}
                @if($channel['display_show'] == 1)
                    {{ Form::file('file', array('id' => 'fileupload2','class' =>'top_space2', 'data-url' => 'server/php/')) }}
                @else
                    {{ Form::file('file', array('id' => 'fileupload2', 'data-url' => 'server/php/')) }}
                @endif
                {{ Form::hidden('key', 'uploads', array('id' => 'key2')) }}
                {{ Form::hidden('acl', 'public-read') }}
                {{ Form::hidden('AWSAccessKeyId', 'AKIAJWU4DYR6OMHE2YPQ') }}
                {{ Form::hidden('Policy', 'policy', array('id' => 'policy2')) }}
                {{ Form::hidden('Signature', 'signature', array('id' => 'signature2')) }}
                {{ Form::close() }}
            </div>

			{{-- Mobile-Web image --}}
			<div class="col-md-8">
				{{ Form::open(array(
				'url' => 'https://dveo.s3.amazonaws.com/',
				'class' => 'form-horizontal amazon_playlist_mobileweb_image',
				'enctype' => 'multipart/form-data'
				)) }}
                @if($channel['display_show'] == 1)
                    {{ Form::file('file', array('id' => 'fileupload3','class' =>'top_space3', 'data-url' => 'server/php/')) }}
                @else
                    {{ Form::file('file', array('id' => 'fileupload3', 'data-url' => 'server/php/')) }}
                @endif
				{{ Form::hidden('key', 'uploads', array('id' => 'key3')) }}
				{{ Form::hidden('acl', 'public-read') }}
				{{ Form::hidden('AWSAccessKeyId', 'AKIAJWU4DYR6OMHE2YPQ') }}
				{{ Form::hidden('Policy', 'policy', array('id' => 'policy3')) }}
				{{ Form::hidden('Signature', 'signature', array('id' => 'signature3')) }}
				{{ Form::close() }}
			</div>

            {{-- Custom Poster --}}
            <div class="col-md-4">
                {{ Form::open(array(
				'url' => 'https://dveo.s3.amazonaws.com/',
				'class' => 'form-horizontal amazon_playlist_poster_image',
				'enctype' => 'multipart/form-data'
				)) }}
                {{ Form::file('file', array('id' => 'fileupload5', 'data-url' => 'server/php/')) }}
                {{ Form::hidden('key', 'uploads', array('id' => 'key5')) }}
                {{ Form::hidden('acl', 'public-read') }}
                {{ Form::hidden('AWSAccessKeyId', 'AKIAJWU4DYR6OMHE2YPQ') }}
                {{ Form::hidden('Policy', 'policy', array('id' => 'policy5')) }}
                {{ Form::hidden('Signature', 'signature', array('id' => 'signature5')) }}
                {{ Form::close() }}
            </div>

            {{ Form::open(array('url' => 'channel_' . BaseController::get_channel_id() . '/edit_video', 'class' => 'form-horizontal', 'id' => 'edit_video')) }}
            <div class="height addVideo content">
                <table width='100%'><tr>
                    <td><p class="title-name"><i class="fa fa-video-camera"></i>Edit video</p></td>
                    <td align='right'>
                        <div class="saveBtn">{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}</div>
                        <div class="cancelBtn"><a href = "javascript:void(0)" class="btn btn-inverse cancelEdit">Cancel</a></div>
                    </td>
                </tr></table>
                <div id = "poster_image_wrapper" class='controls div_for_poster_image_part'>
                    <h4 class="control-label">Poster</h4>
                    <img id="poster_image" onerror="$('#poster_image_wrapper img').attr('src', '{{asset('images/noLogo.png')}}')" src="https://s3.amazonaws.com/dveo/banners/channel_{{ BaseController::get_channel_id() }}_poster_video_{{ $video->id }}.jpg" class="poster_image">
                </div>
                <div id="videoForm" class="content_list">
                    <!-- Name -->
                    <div class="control-group">
                        {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}
                        <div class="controls">
                            {{ Form::text('title', $video->title, array('class' => 'form-control')) }}
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="control-group">
                        {{ Form::label('Description', 'Description', array('class' => 'control-label')) }}

                        <div class="controls">
                            {{ Form::textarea('description', $video->description, array('class' => 'form-control','rows' => 2)) }}
                        </div>
                    </div>

                    <div class="control-group">
                        {{ Form::button('Advanced Settings', array('class' => 'btn btn-inverse', 'style' => 'margin:5px 0 5px 5px;', 'onclick' => "ToggleAdvancedSettings()" )) }}
                    </div>

                    <div style='border:1px solid #777;width:95%;margin-left:3%;display:none;' id='advancedSettings'>
                        <div style='background:#aaa;text-align:center;font-size:22px;'>Advanced Settings</div>
                        <div style='float:right;font-size:22px;margin-top:-30px;cursor:pointer;' onclick='ToggleAdvancedSettings()'>[X]</div>
                        {{-- Tako menu --}}
                        <nav id = "tako_nav"> <a href="#" id="showmenu">Menu <i class="icon-reorder"></i></a>
                            <ul class="nav">
                                <li class="tako_item">
                                    <a href="javascript:void(0)">Viewing</a>
                                    <ul class="second-level">
                                        <div class="control-group padding_space">
                                            <div class="controls">
                                                <input type='hidden' id='api_url_exist' value='{{ $api_url_exist }}'>
                                                {{ Form::select('viewing', ['inherit' => 'Same as parent Category', 'free' => 'Free', 'paid' => 'Paid'], $video->viewing, array('id' => 'viewing', 'style' => 'width:200px;margin:0 5px 0 5px;height:32px;font-size: 14px;', 'onchange' => 'OnViewingChanged()')) }} &nbsp;&nbsp;
                                            </div>
                                            <div class="controls">
                                                <div style='margin-top:5px;' id='viewingComment'></div>
                                            </div>
                                        </div>
                                    </ul>
                                </li>
								<li>
									<a href="javascript:void(0)">Tags</a>
									<ul class="second-level">
										<div class="control-group padding_space">
											<div class="controls">
												<select multiple="multiple" name="tags[]" id="tags" class="select_tag">
													@if(!empty($tags))
														@foreach($tags as $tag)
															<option {{ (!empty($tag_ids) && in_array($tag->id,$tag_ids,true)) ? 'selected' : '' }} value="{{ $tag->id }}">{{ $tag->name }}</option>
														@endforeach
													@endif
												</select>
											</div>
										</div>
									</ul>
								</li>
                                @if($channel['display_show'] == 1)
                                <li>
                                    <a href="javascript:void(0)">Show</a>
                                    <ul class="second-level">
                                        <div class="control-group padding_space">
                                            <div class="controls">
                                                <select multiple="multiple" name="show[]" id="show" class="select_show">
                                                   @if(!empty($shows))
                                                       @foreach($shows as $show)
                                                           <option {{ (!empty($show_ids) && in_array($show->id,$show_ids,true)) ? 'selected' : '' }} value="{{ $show->id }}">{{ $show->name }}</option>
                                                       @endforeach
                                                   @endif
                                                </select>
                                            </div>
                                        </div>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">Seasons</a>
                                    <ul class="second-level">
                                        <div class="control-group padding_space">
                                            <div class="controls">
                                                <select name="season" id = "season" class="form-control">
                                                    <option value="1" {{ ($video->season == 1) ? 'selected' : '' }}>Season 1</option>
                                                    <option value="2" {{ ($video->season == 2) ? 'selected' : '' }}>Season 2</option>
                                                    <option value="3" {{ ($video->season == 3) ? 'selected' : '' }}>Season 3</option>
                                                    <option value="4" {{ ($video->season == 4) ? 'selected' : '' }}>Season 4</option>
                                                </select>
                                            </div>
                                        </div>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">Episodes</a>
                                    <ul class="second-level">
                                        <div class="control-group padding_space">
                                            <div class="controls">
                                                <select name="episod" id = "episod" class="form-control">
                                                    <option value="1" {{ ($video->episode == 1) ? 'selected' : '' }}>Episode 1</option>
                                                    <option value="2" {{ ($video->episode == 2) ? 'selected' : '' }}>Episode 2</option>
                                                    <option value="3" {{ ($video->episode == 3) ? 'selected' : '' }}>Episode 3</option>
                                                    <option value="4" {{ ($video->episode == 4) ? 'selected' : '' }}>Episode 4</option>
                                                </select>
                                            </div>
                                        </div>
                                    </ul>
                                </li>
                                @endif
                                <li class="featured_item">
                                    <a href="javascript:void(0)">
                                        Featured Image
                                    </a>
                                    <ul class="second-level">
                                        <div id = "tvapp_image_wrapper" class='controls div_for_feature_image_part'>
                                            <h4>TV apps</h4>
                                            <img id="tvapp_image" onerror="$('#tvapp_image_wrapper img').attr('src', '{{asset('images/noLogo.png')}}')" src="https://prolivestream.imgix.net/banners/channel_{{ BaseController::get_channel_id() }}_TVapps_vod_poster_{{ $video->id }}.jpg" class="banner1 col-md-4">
                                        </div>

                                        <div id = "mobileweb_img_wrapper" class='controls div_for_feature_image_part'>
                                            <h4>Mobile-Web TV</h4>
                                            <img id="mobileweb_image" onerror="$('#mobileweb_img_wrapper img').attr('src', '{{asset('images/noLogo.png')}}')" src="https://s3.amazonaws.com/dveo/banners/channel_{{ BaseController::get_channel_id() }}_mobileweb_video_{{ $video->id }}.jpg" class="banner1 col-md-4">
                                        </div>
                                    </ul>
                                </li>
                                <li class="tako_item">
                                    <a href="javascript:void(0)">Sharing</a>
                                    <ul class="second-level">
                                        <div class="control-group">
                                            <div class="controls padding_space">
                                                {{ Form::text('sharing', $video->duration, array('id' => 'sharing', 'readonly' => 'readonly', 'style' => 'width:70px;margin:0 5px 0 5px;height:32px;')) }} Seconds&nbsp;&nbsp;
                                            </div>
                                        </div>
                                    </ul>
                                </li>
                                <li class="tako_item">
                                    <a href="javascript:void(0)">Thumbnail Source</a>
                                    <ul class="second-level">
                                        <div class="control-group">
                                            <div class="controls padding_space">
                                                {{ Form::radio('thumbnail_source', '0', ($video->thumbnail_source==0) ? true : false, array('id' => 'thumbnail_source_0', 'style' => 'margin:0 5px 0 5px;', 'title' => 'Image grabbed from 1stud.io')) }} Custom Image
                                                {{ Form::radio('thumbnail_source', '1', ($video->thumbnail_source==1) ? true : false, array('id' => 'thumbnail_source_1', 'style' => 'margin:0 5px 0 5px;', 'title' => 'link one which comes with data when fetched from third party sites')) }} Native Image
                                            </div>
                                        </div>
                                    </ul>
                                </li>
                                <li class="tako_item">
                                    <a href="javascript:void(0)">Download</a>
                                    <ul class="second-level">
                                        <div class="control-group">
                                            <a href="/channel_{{ BaseController::get_channel_id() }}/downloadVideo/{{ $video->id }}" class="downloadVideoBtn">Download original video file</a>
                                        </div>
                                    </ul>
                                </li>
                                <li class="tako_item">
                                    <a href="javascript:void(0)">Duration</a>
                                    <ul class="second-level">
                                        <div class="control-group">
                                            <div class="controls padding_space">
                                                {{ Form::text('duration', $video->duration, array('id' => 'duration', 'readonly' => 'readonly', 'style' => 'width:70px;margin:0 5px 0 5px;height:32px;')) }} Seconds&nbsp;&nbsp;
                                                {{ Form::button('Recalculate Duration', array('class' => 'btn btn-inverse', 'onclick' => "RecalculateDuration('$video->id')" )) }}
                                                <img id='loader' style='width:32px;display:none;' src="{{ URL::to('/') }}/images/admin_loader.gif">
                                            </div>
                                        </div>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                        {{-- End tako --}}

                    </div>

                    <div class="control-group">
                        {{ Form::label('Categories', 'Folders/Categories', array('class' => 'control-label')) }}

                        <select multiple class="form-control" id="collections" name="collections" style='height:200px;'>
                            @foreach($collections as $collection)
                                <option value="{{ $collection->id }}">{{ $collection->title }}</option>
                            @endforeach
                        </select>

                        @foreach($video_in_collections as $video_in_collection)
                            <script>
                                $('#collections option[value="{{ $video_in_collection->collection_id }}"]').attr('selected', 'selected');
                            </script>
                        @endforeach
                    </div>

                    <div class="hide-inputs">
                        {{Form::hidden('file_name','',array('id' => 'filename'))}}
                        {{Form::hidden('video_format','', array('id' => 'video-format'))}}
                        {{Form::hidden('id', $video->id, array('id' => 'video-format'))}}
                        {{Form::hidden('video_id', $video->id, array('id' => 'video_id'))}}
                    </div>

                    <div class="edited"></div>
                    <!-- Login button -- >
                    <div class="input-group saveBtn">
                        <div class="controls">
                            {{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
                        </div>
                    </div>
                    -->
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function() {
		var showmenu = $('#showmenu');
		menu = $('nav ul.nav');
		menuHeight = menu.height();

		$(showmenu).on('click', function(e) {
			e.preventDefault();
			menu.slideToggle();
		});
//		$('#fileupload2,#fileupload3').mouseleave(function(){})
		$('#fileupload2,#fileupload3').mouseover(function(){
			$('.featured_item ul').show();
		});
		$('.featured_item').mouseover(function(){
			$('.featured_item ul').show();
			$('#fileupload2,#fileupload3').show();
		});
		$( ".tako_item" ).mouseover(function() {
			$('#fileupload2,#fileupload3').hide();
			$('.featured_item ul').hide();
		})
	});

	$(window).resize(function() {
		var w = $(window).width();
		if (w > 320 && menu.is(':hidden')) {
			menu.removeAttr('style');
		}
	});
</script>
<script language='javascript'>

function RecalculateDuration(vid)
{
    $('#loader').show();
    $.ajax({
        url: ace.path('ajax_get_video_duration'),
        type: "GET",
        data: { vid : vid },
        success: function(data) {
            $('#loader').hide();
            parts = data.split(':');

            if (parts[0]=='success') $('#duration').val(parts[1]);
            else alert(parts[1]);
        }
    });
}

function OnViewingChanged()
{
    v = $('#viewing').val();
    if (v == 'inherit') $('#viewingComment').html('End User will be able to see the video as "Free" or "Paid" as defined by the rules of the category'); 
    else if (v == 'free') $('#viewingComment').html('End User will be able to see the video without having to subscribe to the service.'); 
    else if (v == 'paid') 
    {
        if ($('#api_url_exist').val()=='0') 
        {
            alert('API URL is undefined. Please specify API URL in settings -> general -> API URL');
            $('#viewing').val('inherit');
            OnViewingChanged();
            return;
        }
        $('#viewingComment').html('End User will need to subscribe to your service to be able to see the video.'); 
    }
}

function ToggleAdvancedSettings()
{
    var d = document.getElementById('advancedSettings').style.display;
    if (d=='none'){
		$('#advancedSettings').show();
//		$('#fileupload2,#fileupload3').show();
	}
	else{
		$('#advancedSettings').hide();
//		$('#fileupload2,#fileupload3').hide();
	}

}

OnViewingChanged();

</script>
@extends('template.template')

@section('content')

@if(isset($tvweb_playlists))

<div class="row center-block height" id="contnet-wrap">

    <div class="col-md-12 list-wrap height-inherit" id="playlists">

        <div class="height-inherit playlists content">
            <div class="title-name">
                <i class="fa fa-play-circle"></i>
                <div class="title">Mobile/Web Sections</div>
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
                
                <!-- Uncomment when we need to add new section/playlist -->
                <a href="#" class="btn btn-block btn-lg btn-inverse plusPtnCol" style="width: 180px;" id="tvweb_create-playlist" title="New TVWeb Section">&plus; New TVWeb Section</a>
                <div class="clear"></div>
                <!--  -->
                
			    <script>
				  $(function() {
				    $( "#sortable" ).sortable();
				    $( "#sortable" ).disableSelection();
				  });
			    </script>
                
                                
                <div class="col-md-12 height appendPlaylist" >
                    
                    <ul id="tvweb_playlists_sortable" style="width: 100%; height: 100%; overflow: auto">
                    
                    <!-- Watch Live -->
                    
                    
                               
                    @foreach($tvweb_playlists as $playlist)
                    
				      <li  id="item-{{$playlist->id}}" style="list-style-type: none">
				         @if($playlist->type == 1)
				         
				          <div class="lists">
                            <section data-playlist_id="{{$playlist->id}}" class="list_item section_playlist">
                                
                                <button id="{{$playlist->id}}" class="tvweb_edit_playlist editDelete fr btn btn-block btn-lg btn-inverse" title="Edit playlist">
                                    <span class="fui-new"></span>
                                </button>
                                
                                <div class="clear"></div>
                                <div class="row center-block">
                                    <!--div class="col-md-2 playlist_thumb">
                                        <img onerror="$('.image_control img').attr('src', '{{asset('images/noLogo.png')}}')" src="http://prolivestream.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}_tvweb_playlist_{{ $playlist['id'] }}?{{ md5($channel['updated_at']) }}" style="width:100%;">
                                    </div-->
                                    <div class="col-md-10 playlist_thumb">
                                        <h1 class="videoTtitle">{{$playlist->title}}</h1>
                                        <p class="duration">
                                            <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$playlist->time}}
                                            @if($playlist->type == 2)
                                                <span class="master_looped">&nbsp;|&nbsp;Master looped</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                              </section>
                           </div>
				         
				         @else
				         
				          <div class="lists">
                            <section data-playlist_id="{{$playlist->id}}" class="list_item section_playlist">
                                
                                <button id="{{$playlist->id}}" class="tvweb_edit_playlist editDelete fr btn btn-block btn-lg btn-inverse" title="Edit playlist">
                                    <span class="fui-new"></span>
                                </button>
                                <button id="{{$playlist->id}}" class="tvweb_delete_playlist editDelete fr btn btn-block btn-lg btn-danger" title="Delete playlist">
                                    <span class="fui-trash"></span>
                                </button>
                                <div class="clear"></div>
                                <div class="row center-block">
                                    <!--div class="col-md-2 playlist_thumb">
                                        <img onerror="$('.image_control img').attr('src', '{{asset('images/noLogo.png')}}')" src="http://prolivestream.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}_tvweb_playlist_{{ $playlist['id'] }}?{{ md5($channel['updated_at']) }}" style="width:100%;">
                                    </div-->
                                    <div class="col-md-10 playlist_thumb">
                                        <h1 class="videoTtitle">{{$playlist->title}}</h1>
                                        <p class="duration">
                                            <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$playlist->time}}
                                            @if($playlist->type == 2)
                                                <span class="master_looped">&nbsp;|&nbsp;Master looped</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                              </section>
                           </div>
                          
                          @endif  
                          
                      </li>
                    @endforeach
                    </ul>
                        
                </div>
                
	            <script>    
	                $('#tvweb_playlists_sortable').sortable({
	                   axis: 'y',
	                   update: function (event, ui) {
	                      var data = $(this).sortable('serialize');
	                      //alert(data);
	                      // POST to server using $.post or $.ajax
	                      $.ajax({
	                    	 data: data,
	                         type: 'POST',
	                         url: ace.path('tvweb_order_playlist')
	                      });
	                    }
	                });
	            </script>      
          

            </div>
        </div>
    </div>
</div>


@endif
@stop
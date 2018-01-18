@extends('template.template')

@section('content')


@if(isset($tvapp_playlists))
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" />
<input type='hidden' id='parent_playlist_id' value='0'>
<input type='hidden' id='openNodes' value=''>
<style>
#box {
  display: flex;
  align-items: center;
}

#box > svg {
  height: 24px;
  width: 24px;
/*   transform: scale(5); */
}

#box > svg path#on-air-out {
  animation: on-air-out 2s infinite;
}

#box > svg path#on-air-in {
  animation: on-air-in 2s infinite;
}

#box > svg ellipse {
  transform-origin: 50% 50%;
  animation: on-air-circle 2s infinite;
}

@keyframes on-air-circle {
  0% {
    opacity: .1;
    transform: scale(1);
  }
  25%  { opacity: 1;
  transform: scale(2.4);}
  50%  { opacity: 1;}
  75%  { opacity: 1;
  transform: scale(1)}
  100% { opacity: .3; }
}

@keyframes on-air-in {
  0%   { opacity: .3; }
  25%  { opacity: .3; }
  50%  { opacity: 1; }
  75%  { opacity: 1; }
  100% { opacity: .3; }
}

@keyframes on-air-out {
  0%   { opacity: .3; }
  50%  { opacity: .3; }
  75%  { opacity: 1; }
  100% { opacity: .3; }
}
  #modal_for_snapshot_video{
    z-index:9999999999999999999999!important;
  }
  .modal_for_snapshot .col-md-6{
    width:100%!important;
  }
    @media screen and (min-width:360px) and (max-width:767px) and (orientation : portrait){
        .btn_for_create_top_shelf{
           width: 108px!important;
           font-size: 10px!important;
           padding-left: 2px;
        }
        .btn_for_create_low_shelf{
           width: 108px!important;
           font-size: 10px!important;
           padding-left: 2px;
           margin-left:2px!important;
        }
        .btn_for_preview_pl{
           width: 95px!important;
           font-size: 11px!important;
           margin-top:4px;
        }
		.btn_for_refresh_xml{
          width: 95px!important;
           font-size: 11px!important;
           margin-top:4px;
        }
  }
}

@media screen and (min-width:200px) and (max-width:359px) and (orientation : portrait){
         .btn_for_create_top_shelf{
           width: 102px!important;
           font-size: 9px!important;
           padding-left: 2px;
        }
        .btn_for_create_low_shelf{
           width: 102px!important;
           font-size: 9px!important;
           padding-left: 2px;
           margin-left:2px!important;
        }
        .btn_for_preview_pl{
           width: 55px!important;
           font-size: 10px!important;
           margin-top:6px;
        }
		  .btn_for_refresh_xml{
           width: 55px!important;
           font-size: 10px!important;
           margin-top:6px;
        }
      
}

@media screen and (min-width:560px) and (max-width:1020px) and (orientation : landscape){
     .for_btn_responses{
		font-size:8px!important;
	}
}
@media screen and (min-width:768px) and (max-width:1000px) and (orientation : portrait){
        .btn_for_create_low_shelf{
           margin-left:50px!important;
        }
}
.div_for_black_parth{
  margin-bottom:10px;
}
</style>
<div class="b-playlist-editor-wrapper parth_for_margin_bottom">
    <div class="list-wrap height-inherit" id="playlists">
        <div class="height-inherit playlists content">
            <div class="div_for_change_btn_parth title-name">




                <!-- change -->
                <!-- <i class="fa fa-play-circle"></i> -->
                <!-- <a href="/channel_{{ $channel['id'] }}/tvapp_playlists"><div class="title">Manage Playlists</div></a>&nbsp;&nbsp; -->
              <?php /*
<!--              <img src='{{ URL::to('/') }}/images/help.png' style='width:42px;cursor:pointer;' title="Show Help" onclick="ShowHelp(1)">  -->
<!--                <button class="tvapp_generate_feed" title="Generate Feed"><img src='{{ URL::to('/') }}/images/feed.png' style='width:42px;'></button>-->
                <!-- <button class="btn btn-danger tvapp_remove_video_from_playlist" title="Remove video / playlist"><span class="fui-trash"></span></button> -->
<!--                 <button class="btn tvapp_edit_playlist" title="Edit playlist"><span class="fui-new"></span></button>-->
<!--                 <img src='{{ URL::to('/') }}/images/tree.png' style='width:42px;cursor:pointer;border:1px solid #777;' title="Show Tree Structure" view='1' onclick="ToggleView(this)"> -->
              */ ?>

                <button class="btn greenActionBtn tvapp_create_playlist btn_for_create_top_shelf" style="width: 165px" id="tvapp_create_playlist_top" data-row="1" title="New Top Shelf Playlist">&plus; Top Shelf Playlist</button>
                <button class="btn greenActionBtn tvapp_create_playlist btn_for_create_low_shelf" style="width: 165px" id="tvapp_create_playlist_low" data-row="0" title="New Low Shelf Playlist">&plus; Low Shelf Playlist</button>
				<button class="btn greenActionBtn btn_for_refresh_xml" style="width: 165px;float:right;margin-right:10px" >Refresh Feed</button>
                <a href="../channel_{{ $channel['id'] }}/tvapp_playlists_preview" target='_blank'><button class="btn greenActionBtn btn_for_preview_pl" style="width: 165px;float: right;" id="tvapp_preview" data-channel="{{ $channel['id'] }}" title="Preview">
                 <div id="on-air">
                    <i class="fa fa-circle"></i>
                    <span class="live">Live</span>
                </div>
                <span class="text-title">Preview</span>
                </button></a>
                <input type='hidden' class='channel_id_input' value="<?=BaseController::get_channel_id()?>">

			  <div class="clear"></div>

                <div class="input-group searchPlay">
                    <input type="text" class="form-control" placeholder="Search" id="search-query-3">
                    <span class="input-group-btn">
                        <button type="submit" class="btn"><span class="fui-search"></span></button>
                    </span>
                </div>

                <div class="clear"></div>

                <!-- vinay added /1studio/public in following three links -->
            </div>


                <div class="container container_for_modal_edit_video ">
                  <div class="modal fade " id="modal_for_edit_video" role="dialog">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Edit Video</h4>
                        </div>
                        <div class="modal-body">
                            



                              {{ Form::open(array('url' => 'channel_' . BaseController::get_channel_id() . '/edit_video', 'class' => 'form-horizontal', 'id' => 'edit_video')) }}
                              <div class="height addVideo content">
                                  <table width='100%'><tr>
                                      <td><p class="title-name"><i class="fa fa-video-camera"></i>Edit video</p></td>
                                      <td align='right'>
                                          <div class="saveBtn">{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}</div>
                                          <div class="cancelBtn"><a href = "javascript:void(0)" class="btn btn-inverse cancelEdit">Cancel</a></div>
                                      </td>
                                  </tr></table>

                                  <div id="videoForm" class="content_list">
                                      <!-- Name -->
                                      <div class="control-group">
                                          {{ Form::label('Name', 'Name', array('class' => 'control-label')) }}

                                          <div class="controls div_for_input_name_parth">

                                          </div>
                                      </div>

                                      <!-- Password -->
                                      <div class="control-group">
                                          {{ Form::label('Description', 'Description', array('class' => 'control-label')) }}

                                          <div class="controls div_for_description_parth">

                                          </div>
                                      </div>

                                      <div class="control-group">
                                          {{ Form::button('Advanced Settings', array('class' => 'btn btn-inverse', 'style' => 'margin:5px 0 5px 5px;', 'onclick' => "ToggleAdvancedSettings()" )) }}
                                      </div>

                                      <div style='border:1px solid #777;width:90%;margin-left:5%;display:none;' id='advancedSettings'>  
                                          <div style='background:#aaa;text-align:center;font-size:22px;'>Advanced Settings</div>
                                          <div style='float:right;font-size:22px;margin-top:-30px;cursor:pointer;' onclick='ToggleAdvancedSettings()'>[X]</div>

                                          <div style='width:90%;margin-left:2%;margin-bottom:5px;'>  
                                              <div class="control-group">
                                                  {{ Form::label('Duration', 'Duration ', array('class' => 'control-label')) }}
                                                  <div class="controls div_for_duration_parth">

                                                      <img id='loader' style='width:32px;display:none;' src="{{ URL::to('/') }}/images/admin_loader.gif">
                                                  </div>
                                              </div>

                                              <div class="control-group">
                                              {{ Form::label('Viewing', 'Viewing ', array('class' => 'control-label')) }}
                                                  <div class="controls div_for_viewing_parth1">
                                                  <!-- inp -->

                                                  </div>
                                                  <div class="controls div_for_viewing_parth2">
                                                      <div style='margin-top:5px;' id='viewingComment'>End User will be able to see the video as "Free" or "Paid" as defined by the rules of the category</div>
                                                  </div>
                                              </div>

                                              <div class="control-group">
                                                  {{ Form::label('Thumbnail Source', 'Thumbnail Source ', array('class' => 'control-label')) }}
                                                  <div class="controls div_for_thumbnail_parth">


                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="control-group">
                                          {{ Form::label('Categories', 'Categories', array('class' => 'control-label')) }}

                                          <select multiple class="form-control select_for_collections" id="collections" name="collections" style='height:200px;'>


                                          </select>
                                          <div class="div_for_video_incollections_parth">
                                            
                                          </div>

                                      </div>

                                      <div class="hide-inputs hide_input_parth">
                                          {{Form::hidden('file_name','',array('id' => 'filename'))}}
                                          {{Form::hidden('video_format','', array('id' => 'video-format'))}}

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
                        <div class="modal-footer">
                          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  
                </div>


              



                 <div class="container">
                  <div class="modal fade " id="modal_for_snapshot_video" role="dialog">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title"></h4>
                        </div>
                          <div class="modal-body modal_for_snapshot">

                         
                          </div>
                        <div class="modal-footer">
                          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  
                </div>




            <!--  =================================================================================  -->

            <div class="row center-block list content_list" id="container_tree" >
            <!-- change -->
                <div class="title-name chanel_name_parth_div">
                    <!-- {{$channelInfo}} -->
                </div>
                <div id="js-playlists-treeview" data-id="{{$channelId}}" data-name="{{$channelInfo}}"></div>
            </div>

            <div id="preview_modal" class="modal fade modal_parth" role="dialog" >
                  <div class="modal-dialog modal-lg " id="preview_content_parent">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Preview</h4>
                      </div>
                      <div class="modal-body modal_parth_overflow" id="preview_content" data-id="{{$channelId}}" data-name="{{$channelInfo}}">
                            Loading.../
                      </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>

  window.NickelledMissionDockSettings = {
    missionDockId: 129,
    userId: {{ $channel['id'] }}
  };

  (function(){var NickelledMissionDock=window.NickelledMissionDock=NickelledMissionDock||{};NickelledMissionDock.preloadEvents=[];NickelledMissionDock.show=function(){NickelledMissionDock.preloadEvents.push('show')};NickelledMissionDock.hide=function(){NickelledMissionDock.preloadEvents.push('hide')};var loadMD=function(){var s,f;s=document.createElement("script");s.async=true;s.src="https://cdn.nickelled.com/mission-dock.min.js";f=document.getElementsByTagName("script")[0];f.parentNode.insertBefore(s,f);};loadMD();NickelledMissionDock.show();})();
</script>
@endif
@stop
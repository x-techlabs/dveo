@extends('template.template')

@section('content')


@if(isset($tvapp_playlists))
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" />
<style>
.container{
  background:black;
}
.count_part_top_shelf{
  color:white;
}
header{
  display:none;
}

.modal_preview_page{
  border:0px;
}
.div_for_black_parth{
  margin-bottom:0px;
  height:120px;
}
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


  main{
    max-width:100%;
  }


@media screen and (min-width: 746px) and (max-width: 787px){
  .modal-dialog{
    margin:0;
  }
}
@media screen and (min-width: 500px) and (max-width : 746px){
  .modal-dialog{
    margin:0!important;

  }
}
@media screen and (min-width: 250px) and (max-width : 502px){
  .modal-dialog{
    margin:0!important;

  }
}
</style>

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
<div id="preview_page" >
   <div class="modal-dialog modal-lg " id="preview_content_parent">
        <div class="modal-content modal_preview_page">
          <div class="modal-body modal_parth_overflow" id="preview_content" data-id="{{$channelId}}" data-name="{{$channelInfo}}">
                Loading.../
          </div>
        </div>
    </div>
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
				
				
                <script type="text/javascript">
                    function update_poster_preview(){
                      var filenameSize = [];
                          console.log($(".amazon_playlist_logo"));
                      $(".amazon_playlist_logo").each(function(){
                          var form = $(this);
                          form.fileupload({
                              url: form.attr('action'),
                              type: 'POST',
                              autoUpload: true,
                              async: true,
                              dataType: 'xml',
                              add: function(event, data) {
                                  var filename = data.files[0].name.replace(/[^a-zA-Z0-9.]/g,'_')
                                  var filesiez = data.files[0].size;
                                  if(filenameSize.indexOf(filename[0] + filesiez) == -1) {
                                      var exts = ['jpg', 'jpeg', 'png'];
                                      // first check if file field has any value
                                      if(filename) {
                                          // split file name at dot
                                          var get_ext = filename.split('.');
                                          // reverse name to check extension
                                          get_ext = get_ext.reverse();
                                          // check file type is valid as given in 'exts' array
                                          if($.inArray(get_ext[0].toLowerCase(), exts) > -1) {
                                              form.find('#fileupload').addClass('logoUploadAfter');
                                              $('.logoLoader').height(100);

                                              // Get the video format string from filename string
                                              var pos = filename.lastIndexOf('.');
                                              var videoFormat = filename.slice(pos + 1);
                                              var dataSubmit = data;

                                              // Get the policy  and signature
                                              $.ajax({
                                                  url: "send_amazon_playlist_logo",
                                                  type: "POST",
                                                  async: true,
                                                  data: {
                                                      "ext": get_ext[0],
                                                      "tvapp_playlist_id": $("#tvapp_playlist_id").val()
                                                  },
                                                  dataType: "json",
                                                  success: function (data) {
                                                      
                                                      form.find("#key").val(data.filename);
                                                      form.find("#policy").val(data.policy_encoded);
                                                      form.find("#signature").val(data.signature);
                                                      form.find('input[name=_token]').remove();
                                                      var amazon_filename = data.filename;
                                                      var pos = amazon_filename.lastIndexOf('/')
                                                      amazon_filename = amazon_filename.slice(pos + 1);
                                                      pos = amazon_filename.lastIndexOf('.');
                                                      amazon_filename = amazon_filename.slice(0, pos);

                                                      amazonS3Upload.files[filename + filesiez] = {};
                                                      amazonS3Upload.files[filename + filesiez].fileName = amazon_filename;
                                                      amazonS3Upload.files[filename + filesiez].videoFormat = videoFormat;

                                                      dataSubmit.submit();

                                                  }
                                              });
                                          } else {
                                              console.log('no');
                                          }
                                      }
                                      filenameSize.push(filename[0] + filesiez);
                                  }
                              },
                              send: function(e, data) {

                              },
                              progress: function(e, data){

                              },
                              fail: function(e, data) {
                                  console.log(e);
                              },
                              success: function(data) {
                                  //var url = $(data).find('Location').text();
                                  //$('#real_file_url').val(url);// Update the real input in the other form
                                  //$('#real_file_url').attr("src", url);
                                  //window.location.replace("tvapp_playlists");
                              },
                              done: function (event, data) {
                                  location.reload(true);
                                      
                                      // $(".image_for_pl_" + data_play_id).attr('src', 'http://prolivestream.s3.amazonaws.com/logos/channel_' + ace.channel_id+'_tvapp_playlist_'+$("#tvapp_playlist_id").val() + get_ext[0]);
                                      

                                      //$('.logo1').attr('src', $('.logo1').attr('src') + Math.random());

                                  
                                  //$('.logo1').attr('src', 'http://prolivestream.s3.amazonaws.com/logos/channel_' + ace.channel_id+'_tvapp_playlist_'+$("#tvapp_playlist_id").val());

                                  
                                  $('.amazon_playlist_logo #fileupload').removeClass('logoUploadAfter');
                                  $('.logoLoader').height(0);

                              }
                          });
                      })
                    }
                </script>

@endif
@stop
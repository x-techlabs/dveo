@extends('template.template')
@section('content')
    <script language='javascript'>

    function vod_add_video(parts)
    {
        var dt = new Date();
        var path = ace.path("vod_add_video");
        progressBar.value = 300;

        var db_json = {
                        "title"             : "",
                        "description"       : "",
                        "thumbnail_name"    : parts[3],
                        "start_time"        : "0",
                        "duration"          : parts[0][1],
                        "file_name"         : parts[0][0],
                        "video_format"      : "mp4",
                        "job_id"            : "0",
                        "encode_status"     : "2",
                        "type"              : "0",
                        "hd_width"          : parts[0][2],
                        "hd_height"         : parts[0][3],
                        "hd_file_size"      : parts[0][4],
                        "hd_video_bitrate"  : parts[0][5],
                        "hd_audio_codec"    : parts[0][6],
                        "hd_video_codec"    : parts[0][7],
                        "hd_mime_type"      : parts[0][8],
                        "sd_file_name"      : parts[1][0],
                        "sd_duration"       : parts[1][1],
                        "sd_width"          : parts[1][2],
                        "sd_height"         : parts[1][3],
                        "sd_file_size"      : parts[1][4],
                        "sd_video_bitrate"  : parts[1][5],
                        "sd_audio_codec"    : parts[1][6],
                        "sd_video_codec"    : parts[1][7],
                        "sd_mime_type"      : parts[1][8],
                        "mb_file_name"      : parts[2][0],
                        "mb_duration"       : parts[2][1],
                        "mb_width"          : parts[2][2],
                        "mb_height"         : parts[2][3],
                        "mb_file_size"      : parts[2][4],
                        "mb_video_bitrate"  : parts[2][5],
                        "mb_audio_codec"    : parts[2][6],
                        "mb_video_codec"    : parts[2][7],
                        "mb_mime_type"      : parts[2][8],
                        "created_at"        : dt.toISOString(),
                        "updated_at"        : dt.toISOString(),
                        "storage"           : ""
                    };
                
        //console.log(JSON.stringify(db_json));
        document.getElementById('status').innerHTML = 'Adding video Record';
        $.ajax({
                type: 'POST',
                url:  ace.path("video_add_to_table"),
                data: { videoData : JSON.stringify(db_json) },
                success: function(data) {
                    progressBar.value = 300;
                    alert("Video Added successfully");
                    window.location = ace.path("videos");
                }
        });
        //send_s3();
    }

    function UploadFilesToAWSServer(files)
    {
        document.getElementById('status').innerHTML = 'Uploading Files To AWS Server';
        progressBar.value = 200;
        // filename2Upload|AWSObjectKeyName|UploadId|parts|status
        $.ajax({
                type: 'POST',
                url: ace.path("uploadfilestoawsserver"),
                data: { pids: files },
                success: function(msg)
                {
                    parts = msg.split('~');
                    if (parts[0]==0) 
                    {
                        window.setTimeout("UploadFilesToAWSServer('" + msg + "')", 1000);
                        return;
                    }

                    threeFiles = parts[1].split('|');
                    threeFiles[0] = threeFiles[0].split('^');
                    threeFiles[1] = threeFiles[1].split('^');
                    threeFiles[2] = threeFiles[2].split('^');
                     
                    vod_add_video(threeFiles);
                }
        }); 
    }

    function IsTranscodeProcessComplete(pidList)
    {
        $.ajax({
                type: 'POST',
                url: ace.path("istranscodeprocesscomplete"),
                data: { pids: pidList },
                success: function(msg)
                {
                    parts = msg.split('^');
                    if (parts[0]==0) 
                    {
                        percent = 100 + parseInt(parts[1]);
                        progressBar.value = percent;
                        window.setTimeout("IsTranscodeProcessComplete('" + pidList + "')", 10000);
                        return;
                    }
                    UploadFilesToAWSServer(parts[1]);
                    //resultsingle(data);
                }
        }); 
    }

    function performAll2()
    {
        var form_data = new FormData();                  
        form_data.append('uploadedFile', $('#fileInput')[0].files[0]);
        document.getElementById('status').innerHTML = 'Uploading file to server';

        $.ajax({
                url: ace.path("pushmediaobject"), // point to server-side PHP script 
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'POST',
                xhr: function(){
                     //upload Progress
                     var xhr = $.ajaxSettings.xhr();
                     if (xhr.upload) {
                         xhr.upload.addEventListener('progress', function(event) {
                             var percent = 0;
                             var position = event.loaded || event.position;
                             var total = event.total;
                             if (event.lengthComputable) {
                                 percent = Math.ceil(position / total * 100);
                             }
                             //update progressbar
                             $('#progressBarA').val(percent);
                         }, true);
                     }
                     return xhr;
                 },

                 success: function(php_script_response){
                    parts = php_script_response.split('|');
                    //alert(php_script_response); // display response from the PHP script, if any
                    if (parts[0] == 'file uploaded')
                    {
                        parts.shift();
                        document.getElementById('status').innerHTML = 'Transcoding Files';
                        $.ajax({
                                type: 'POST',
                                url: ace.path("start_transcode"),
                                data: { name: parts.join('|') },
                                success: function(msg){
                                        // msg = part1 (pids) ^ part2 (output filenames) ^ part3 (progress file names)
                                        IsTranscodeProcessComplete(msg);
                                }
                        }); 
                    }
                }
         });
    }
    </script>


    <div class="row center-block height" id="contnet-wrap">
        <div class="col-md-12 height" id="upload" style="text-align: center">
            <div id="upload-body" class="height content" style='overflow:hidden;'>
                <p class="title-name"><i class="fa fa-plus"></i>Upload video</p>
                <div id = "upload_container">
                    <div id="fine-uploader" style="background-color:transparent;height:400px;width: 50%;border-radius:20px;margin: 25%;margin-top: 25px;"></div>
                </div>

    <!-- Fine Uploader -->
    
    <script type="text/template" id="qq-template">
        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button" style="width:100%;border-radius:20px;background-color:#428bca;height:100px;line-height:100px;">
                <div>Click to Upload (or) Drag & Drop</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Processing dropped files...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <div class="qq-progress-bar-container-selector">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
                    <span class="qq-upload-file-selector qq-upload-file"></span>
                    <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                    <span class="qq-upload-size-selector qq-upload-size"></span>
                    <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
                    <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Close</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Yes</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancel</button>
                    <button type="button" class="qq-ok-button-selector">Ok</button>
                </div>
                </dialog>
                <input type="checkbox" checked id="check_hybrik"> Transcode </input>
        </div>
    </script>

    <script>
        var uploader = new qq.s3.FineUploader({
            debug: true,
            element: document.getElementById('fine-uploader'),
            request: {
                endpoint: 'https://dveo.s3.amazonaws.com',
                accessKey: 'AKIAJWU4DYR6OMHE2YPQ'
            },
            signature: {
                endpoint: 'https://1stud.io/endpoint.php'
            },
             cors: {
                  //all requests are expected to be cross-domain requests
                 expected: true,
                 // //if you want cookies to be sent along with the request
                 //  sendCredentials: true
            },
            uploadSuccess: {
                endpoint: 'save_s3_file',
                params: {
                    hybrik:  function() { return  $('#check_hybrik').is(":checked"); }
                }
            },
            iframeSupport: {
                localBlankPagePath: '/success.html'
            },
            retry: {
               enableAuto: true // defaults to false
            },
            deleteFile: {
                enabled: false,
                endpoint: '/s3handler'
            }
        });
    </script>


                <!--table width='1000px'><tr> 
                <td style='width:200px;'><input type="file" id="fileInput" name="uploadedFile"></td>
                <td style='width:100px;'><input type='button' value='Process' onclick="performAll2();"></td>
                <td style='width:300px;'><progress id="progressBarA" max="300" value="0"/></progress></td> 
                <td><div id='status'></div></td>
                </tr></table>


                <script type="text/javascript">
                    var progressBar = document.getElementById("progressBarA");
                </script!-->


            </div>
        </div>
    </div>

@stop

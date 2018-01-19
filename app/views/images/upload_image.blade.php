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
        document.getElementById('status').innerHTML = 'Adding image Record';
        $.ajax({
            type: 'POST',
            url:  ace.path("image_add_to_table"),
            data: { videoData : JSON.stringify(db_json) },
            success: function(data) {
                progressBar.value = 300;
                alert("Image Added successfully");
                window.location = ace.path("image_manager");
            }
        });
    }

    function UploadFilesToAWSServer(files)
    {
        document.getElementById('status').innerHTML = 'Uploading Files To AWS Server';
        progressBar.value = 200;
        $.ajax({
            type: 'POST',
            url: ace.path("uploadimagestoawsserver"),
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
            url: ace.path("istranscodeprocesscomplete_img"),
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
            }
        }); 
    }

    function performAll2()
    {
        var form_data = new FormData();                  
        form_data.append('uploadedFile', $('#fileInput')[0].files[0]);
        document.getElementById('status').innerHTML = 'Uploading file to server';

        $.ajax({
            url: ace.path("pushmediaobject_img"),
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
                if (parts[0] == 'file uploaded')
                {
                    parts.shift();
                    document.getElementById('status').innerHTML = 'Transcoding Files';
                    $.ajax({
                        type: 'POST',
                        url: ace.path("start_transcode_image"),
                        data: { name: parts.join('|') },
                        success: function(msg){
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
                <p class="title-name"><i class="fa fa-plus"></i>Upload Image</p>
                <div id="fine-uploader" style="background-color:transparent;height:100px;width: 50%;border-radius:20px;margin: 25%;margin-top: 25px;">
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
                    </div>
                </script>

                <script>
                    var uploader = new qq.s3.FineUploader({
                        debug: true,
                        element: document.getElementById('fine-uploader'),
                        request: {
                            endpoint: 'dveo-images.s3.amazonaws.com',
                            accessKey: 'AKIAJWU4DYR6OMHE2YPQ'
                        },
                        signature: {
                            endpoint: 'http://1stud.io/endpoint_image.php'
                        },
                        uploadSuccess: {
                            endpoint: 'save_s3_image'
                        },
                        iframeSupport: {
                            localBlankPagePath: '/success.html'
                        },
                        retry: {
                           enableAuto: true
                        },
                        deleteFile: {
                            enabled: true,
                            endpoint: '/s3handler'
                        }
                    });
                </script>
            </div>
        </div>
    </div>

@stop

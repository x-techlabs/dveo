@extends('template.template')
@section('content')

    <div class="row center-block height" id="contnet-wrap">
        <div class="col-md-12 height" id="upload" style="text-align: center">
            <div id="upload-body" class="height content">
                <p class="title-name"><i class="fa fa-plus"></i>Upload video (multiple files select)</p>
                <div id="videoFormAppend"></div>


                <div class="downloader" id="downloader">
                    <div class="downloading-progress">
                        <div id="progressBar" class="downloading-progress-bar" data-value="0" data-max="100"></div>
                    </div>
                    <div id="percentageBar" class="percentage">0%</div>
                    <div class="clear"></div>
                </div>

                <div id='pandabrowsefile'><span><div id='fileupload'>Upload Video</div></span></div>
                <div id="downloadedVideosAppend"><p class="title-name"><i class="fa fa-plus"></i>Uploaded Videos</p></div>
                <p class="col-md-12" id="resultvup"></p>

                <script type="text/javascript">
                    //$('#new_video').unbind('submit');
                    $('#downloader').children().hide();
                    $('#downloadedVideosAppend').hide();
                    $(document).ready(function () {
                        var upl = panda.uploader.init({
                            'progressBarId': 'progressBar',
                            'buttonId': 'pandabrowsefile',
                            'allowSelectMultipleFiles': true,
                            'authorizeUrl': window.location.protocol + '//' + window.location.host + '/channel_{{ $channel['id'] }}/authorize_upload',
                            'onProgress': function (file, percent) {
                                $('#downloader').children().show();
                                document.getElementById('progressBar').value = percent;
                                document.getElementById('percentageBar').innerText = percent + '%';
                            },
                            'onStart': function(file){
                                $('#pandabrowsefile').children().hide();
                            },
                            'onSuccess': function (file, data) {
                                $('#downloader').children().hide();

                                ////////////data = JSON.stringify(data);

//                                $.each(JSON.parse(data),function(key,value){
//                                      $.each(value,function(k,v) {
//                                          console.log(k + ":" + v);
//                                      });
//                                });



                                var file_name = data.original_filename;
                                var title = file_name.replace(/[^a-zA-Z0-9.]/g, '_');
                                var video_format = data.extname.slice(data.extname.lastIndexOf('.') + 1);
                                var file_size = data.file_size;
                                var encoded_video_id = data.id;

                                var thisObj = this;
                                $.ajax({
                                    url: "add_video",
                                    type: "GET",
                                    async: true,
                                    data: {
                                        "form": true,
                                        "title": title,
                                        "file_name": file_name,
                                        "video_format": video_format,
                                        "encoded_video_id": encoded_video_id
                                    },
                                    dataType: "html",
                                    success: function (data) {
                                        //alert(JSON.stringify(data));
                                        var d = $(data);

                                        d.find('#addvideoformid').attr('name', file_name + encoded_video_id);
                                        d.find('#addvideoformid').attr('id', file_name + encoded_video_id);

                                        d.find('#encoded-video-id').val(encoded_video_id);
                                        d.find('#filename').val(file_name);
                                        d.find('#video-format').val(video_format);

                                        var videoName = title.split(".");
                                        $('#' + videoName).removeAttr('class').html(d);
                                        $('#downloadedVideosAppend').append(d);

                                    }
                                });

                                $('#downloadedVideosAppend').show();
                                $('#pandabrowsefile').children().show();

                            },

                            'onCancel': function (file, data) {
                                upl.setProgress(0);
                            },

                            'onError': function (file, message) {
                                console.log("error", message)
                            }
                        });
                    });
                </script>

            </div>
        </div>
    </div>

@stop

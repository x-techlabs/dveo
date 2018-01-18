
<link href="//vjs.zencdn.net/5.8/video-js.min.css" rel="stylesheet">
<script src="//vjs.zencdn.net/5.8/video.min.js"></script>

<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script-->
<script src="http://1stud.io/js/jquery-2.1.1.min.js"></script>
    
<link href="http://1stud.io/js/videojs_snapshot_580/demo/html/css/snapshot.min.css" rel="stylesheet" type="text/css">
<style>
.snapshot.preview {
        float: right;
        width: 100px;
    }
</style>
<script src="http://1stud.io/js/videojs_snapshot_580/demo/javascript/snapshot-5.8.0.js"></script>
    
    
<div class="videoRight">
    <div class="row center-block contnet-wrap description">
        <div class="col-md-6 height" style="padding-right: 0px!important;">
            <div class="description-block content height">

                <p class="title-name"><i class="fa fa-video-camera"></i>{{$desc->title}}</p>
                
                <section class="videoSection content_list" style='cursor: pointer;'>
                    <div class="row center-block" style="padding-right: 5px;">
                        <div class="col-md-12" style="text-align: center;">
                        
                                    <video id="local" class="video-js vjs-default-skin" controls preload="none" width="640" height="264"
                                        data-setup="{}">
									    <source type="video/mp4" src="https://s3.amazonaws.com/aceplayout/1323dc72870f9b87a1a2243ca0d74bb0.mp4"/>
								        <source type="video/webm" src="//videos.electroteque.org/bitrate/big_buck_bunny_600k.webm"/>
								        <source type="video/ogg"   src="//videos.electroteque.org/bitrate/big_buck_bunny_600k.ogv"/>
								    </video>
								
						            <div class="snapshot preview2"></div>
								
			                    <script>
								$(document).ready(function() {
								    var player1 = videojs('local');
								    player1.snapshot({
								        snapshotname: "test-[time]",
								        //previewContainer: ".preview",
								        debuglog: true,
								        serverurl: "https://flowplayer.electroteque.org/snapshot/index/save",
								        tokenurl: "https://flowplayer.electroteque.org/snapshot/index/token",
								        success: "Capture Complete",
								        error: "Capture Failed Try Again",
								        type: "png",
								        savelocal: true,
								        quality: 1,
								        originbaseurl: "http://crossorigin.electroteque.org/video/",
								        notification: {
								            showDelay: 1000,
								            hideDelay: 2000
								        }
								    });
								
								    player1.on("capturecomplete", function(e, data) {
								        console.log("Capture Complete: ", data);
								
								        //var img = $("<img/>").attr("src","data:image/png;base64," + player.captureData.image);
								
								        $(".preview2").empty().append(data.canvas);
								    });
								});
								
								</script>

                        
                        </div>
                        <p>{{$desc->description}}</p>
                    </div>
                </section>

            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>



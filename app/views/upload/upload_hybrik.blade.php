<?php
?>
@extends('template.template')
@section('content')

<!-- https://www.youtube.com/watch?v=eRqsZqLyYaU -->
<script data-main="upload.blade.php" src="/js/hybrik/require.js"></script>
<!-- <!-- <script src="http://requirejs.org/docs/release/2.2.0/minified/require.js"></script> --> -->
<!-- <script src="/js/hybrik/require.js"></script> -->
<script src="/js/hybrik/submit_job.js"></script>
<script type="text/javascript">
//alert('d');
//read the JSON job file and parse into object
//var jsonName = 'sample_job.json';

//var jobPayload = JSON.parse(fs.readFileSync(jsonName, 'utf8'));

// submit the job
//submitJob(jobPayload);

</script>
    <div class="row center-block height" id="contnet-wrap">
        <div class="col-md-12 height" id="upload" style="text-align: center">
            <div id="upload-body" class="height content">
                <p class="title-name"><i class="fa fa-plus"></i>Upload video (multiple files select)</p>
                <div id="videoFormAppend"></div>


				<!-- REF: http://www.tothenew.com/blog/aws-s3-file-upload-with-progress-bar-using-javascript-sdk/ -->
				
				<!-- <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.24.min.js"></script> -->
				<script type="text/javascript">
				
				    //alert( 's3_backet: {{$s3_backet}}, aws_access_key_id: {{$aws_access_key_id;}}, aws_secret_key: {{$aws_secret_key;}}');
				
				    AWS.config.update({
				        accessKeyId : '{{$aws_access_key_id;}}',
				        secretAccessKey : '{{$aws_secret_key;}}'
				    });
				    //AWS.config.region = 'AWS_REGION';
				    
				</script>
				
				<form id="fileUploadForm" method="post" enctype="multipart/form-data">
				<input type="file" name="file" id="file" value="dataFile" required="">
				<input type="submit" name="submit" value="Upload" />
				</form>
				<style>
				    
				    #myProgress {
					    position: relative;
					    width: 100%;
					    height: 30px;
					    background-color: grey;
					}
					#myBar {
					    position: absolute;
					    width: 1%;
					    height: 100%;
					    background-color: green;
					}
				    
				    .snapshot.preview {
				        float: right;
				        width: 110px;
				    }
				    
				    
				</style>
				<div id="myProgress">
				<div id="myBar"></div>
				</div>
				<!-- see more at: http://www.tothenew.com/blog/aws-s3-file-upload-with-progress-bar-using-javascript-sdk/#sthash.mmMRVATa.dpuf -->



                <script type="text/javascript">
                    //$('#new_video').unbind('submit');
                    $('#downloader').children().hide();
                    $('#downloadedVideosAppend').hide();
                    $(document).ready(function () {

                    	$("#fileUploadForm").submit(function() {
                    		
                    		var bucket = new AWS.S3({params: {Bucket: '{{$s3_backet}}'}});
                    		var fileChooser = document.getElementById('file');
                    		var file = fileChooser.files[0];
                    		if (file) {
                        		video_id = '000_'+randomString(9)+'_x_'+file.name;
                        		//alert(fname);
                    			var params = {Key: video_id, ContentType: file.type, Body: file};

                    			bucket.upload(params).on('httpUploadProgress', function(evt) {

                    			//console.log("Uploaded :: " + parseInt((evt.loaded * 100) / evt.total)+'%');


	    						var elem = document.getElementById("myBar");

	    						var progress = (evt.loaded * 100) / evt.total;
	    						  
	 	    					elem.style.width = parseInt(progress)+'%';

	 	    					if(progress >= 100){

	                                $.ajax({
	                                    url: "add_video",
	                                    type: "GET",
	                                    async: true,
	                                    data: {
	                                        "form": true,
	                                        "title": '',
	                                        "file_name": file.name,
	                                        "video_format": '',
	                                        "encoded_video_id": video_id
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
	 	    							 //location.reload();
	 	    						     //window.location.replace("videos");
	 	    					}
                    			
                    		}).send(function(err, data) {
                    			alert("File uploaded successfully.");
                    		});

                    		}
                    		
                    		return false;
                    		});
                        
                    });


                    function randomString(len) {
                        charSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                        var randomString = '';
                        for (var i = 0; i < len; i++) {
                            var randomPoz = Math.floor(Math.random() * charSet.length);
                            randomString += charSet.substring(randomPoz,randomPoz+1);
                        }
                        return randomString;
                    }
                </script>

            </div>
        </div>
    </div>

@stop

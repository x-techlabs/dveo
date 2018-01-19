       
<div class="videoRight">
    <div class="row center-block contnet-wrap description">
        <div class="col-md-6 height" style="padding-right: 0px!important;">
            <div class="description-block content height">

                <p class="title-name"><i class="fa fa-video-camera"></i>{{$desc->title}}</p>
                
                <section class="videoSection content_list" style='cursor: pointer;'>
                    <div class="row center-block" style="padding-right: 5px;">
                        <div class="col-md-12" style="text-align: center;">
                        
	                            <link href="http://1stud.io/js/videojs_snapshot_580/demo/html/css/snapshot.min.css" rel="stylesheet" type="text/css">
								<style>
								    .snapshot.preview {
								        float: right;
								        width: 510px;
								    }
								</style>
	                            <script src="http://1stud.io/js/videojs_snapshot_580/demo/javascript/snapshot-5.8.0.js"></script>
	                            
	                            <script src="http://1stud.io/js/videojs_snapshot_580/demo/javascript/plupload.full.min.js"></script>

                                 <div width="510px" id="localcontainer">
                        
                                    <video id="local" class="video-js vjs-default-skin" controls preload="none" width="510" height="264"
                                        data-setup="{}">
                                        <source type="video/mp4" src="https://s3.amazonaws.com/dveo/{{$desc->file_name}}.mp4"/>
									    <!--source type="video/mp4" src="http://videos.electroteque.org/bitrate/big_buck_bunny_600k.mp4"/-->
								        <source type="video/webm" src="//videos.electroteque.org/bitrate/big_buck_bunny_600k.webm"/>
								        <source type="video/ogg"   src="//videos.electroteque.org/bitrate/big_buck_bunny_600k.ogv"/>
								    </video>
								<button id="save" style="display:none;">Save</button>
						            <div class="snapshot preview2"></div>
								</div>
                        
			                        <script>
			                        
								$(document).ready(function() {
								    var player1 = videojs('local');
								    player1.snapshot({
								        snapshotname: "test-[time]",
								        previewContainer: ".preview",
								        debuglog: true,
								        //serverurl: "https://flowplayer.electroteque.org/snapshot/index/save",
								        tokenurl: "https://flowplayer.electroteque.org/snapshot/index/token",
								        success: "Capture Complete",
								        error: "Capture Failed Try Again",
								        type: "jpg",
								        savelocal: false,
								        quality: 1,
								        originbaseurl: "http://crossorigin.electroteque.org/video/",
								        notification: {
								            showDelay: 1000,
								            hideDelay: 2000
								        }
								    });

								    
								    
		    				    	// Get the policy  and signature
		                            $.ajax({
		                                url: "send_amazon_video_logo",
		                                type: "POST",
		                                async: true,
		                                data: {
		                                    "ext": "jpg",
		                                    "filename": '{{$desc->file_name}}_11.jpg'
		                                },
		                                dataType: "json",
		                                success: function (data) {
		                                	alert(data.filename);
		                                	alert(data.policy_encoded);
		                                	alert(data.signature);
		                                     
		                                     var uploader = new plupload.Uploader({
		                                        browse_button: "hiddenButton",
		                                        runtimes : 'html5,flash',
		                                        //S3 bucket location
		                                        url : 'https://prolivestream.s3-us-west-2.amazonaws.com/',
		                                        multipart: true,
		                                        //S3 policy and signature details
		                                        multipart_params: {
		                                            'key': '{{$desc->file_name}}_11.jpg', // use filename as a key
		                                            'Filename': '{{$desc->file_name}}_11.jpg', // adding this to keep consistency across the runtimes
		                                            'acl': 'public-read',
		                                            'Content-Type': 'image/jpeg',
		                                            'AWSAccessKeyId' : 'AKIAIDGRDUJ7ZG5DNJEA',
		                                            'policy': data.policy_encoded,
		                                            'signature': data.signature
		                                        },

		                                        //file filters
		                                        filters : {
		                                            //minimum image width
		                                            min_img_width: 500,
		                                            max_file_size : '10mb',
		                                            mime_types: [
		                                                {title : "Image files", extensions : "jpg,jpeg,png"}
		                                            ]
		                                        },
		                                         chunks : {
		                                         size: '1mb',
		                                         send_chunk_number: false // set this to true, to send chunk and total chunk numbers instead of offset and total bytes
		                                         },
		                                        dragdrop: false,
		                                        flash_swf_url : 'js/Moxie.swf'
		                                    });





		                                     /**
		                                      * Minimum width file filter
		                                      */
		                                     plupload.addFileFilter('min_img_width', function(minRes, file, cb) {
		                                         var self = this, img = new o.Image(), width;


		                                         if (file.thumb)  {
		                                             cb(true);
		                                             return;
		                                         }


		                                         function finalize(result) {
		                                             img.destroy();
		                                             img = null;
		                                             if (!result) {
		                                                 self.trigger('Error', {
		                                                     code : plupload.IMAGE_DIMENSIONS_ERROR,
		                                                     message : " Minimimum image width of " + minRes  + " pixels is required. Width is " + width,
		                                                     file : file
		                                                 });

		                                             }
		                                             cb(result);
		                                         }

		                                         img.onload = function() {
		                                             width = img.width;
		                                             finalize(img.width >= minRes);
		                                         };

		                                         img.onerror = function() {
		                                             finalize(false);
		                                         };

		                                         img.load(file.getSource());
		                                     });


		                                     uploader.init();

		                                     uploader.bind( "FilesAdded", function( uploader, files ) {

		                                         for ( var i = 0 ; i < files.length ; i++ ) {
		                                             console.log( "File added:", files[ i ].name );
		                                         }

		                                         $("#save").show();

		                                     });

		                                     uploader.bind('BeforeUpload', function(up, file) {
		                                         //set a different S3 path for the thumbnail using the same filename.
		                                         if('thumb' in file){
		                                             file.loaded = 0;
		                                             file.percent = 0;
		                                             file.status = plupload.QUEUED;
		                                             up.settings.multipart_params.key = 'thumbnails/'+file.name;
		                                         } else {
		                                             up.settings.multipart_params.key = 'stills/'+file.name;
		                                         }


		                                     });

		                                     uploader.bind('UploadFile', function(up, file) {
		                                         console.log("Upload started");
		                                     });


		                                     uploader.bind('UploadProgress', function(up, file) {
		                                         console.log("Uploading");
		                                     });

		                                     uploader.bind('UploadComplete', function(up, file) {
		                                         console.log("File Uploads Complete");
		                                         uploading = false;
		                                     });

		                                     uploader.bind('Error', function(up, error) {
		                                         console.log("Error", error);
		                                     });



		                                     uploader.bind('FileUploaded', function(up, file) {
		                                         console.log("File Upload Complete", file);

		                                         if (file.customData) {
		                                             console.log("Update backend with id " + file.customData.id);
		                                         }
		                                     });


			 	    				        $("#save").on("click", function() {
				 	    				        uploader.start();
			 	    				        });


		 	    						    function postVideoToAmazon(e, image) {
					    				    	//alert(fdata.image);
					    				    	//alert('{{$desc->file_name}}_1.jpg'); 
					    				    	
			    		    	                var files = [];

								                //setup the still image
								                alert(image.image);
								                var file = new plupload.File(
								                    new o.File(null, {
								                        name: '{{$desc->file_name}}_11.jpg',
								                        data: image.image
								                    })
								                );
								
								                //setup a custom entry id for this image to update to a backend.
								                file.customData = {
								                    id: 234
								                };
								
								                files.push(file);
								
								                //setup the thumbnail image
								                var file = new plupload.File(
								                    new o.File(null, {
								                        name: '{{$desc->file_name}}_11.jpg',
								                        data: image.thumbnail
								                    })
								                );
								
								                //this is required to be able to differentiate between the two to chang the S3 path above.
								                file.thumb = true;
								
								                files.push(file);
								
								                uploader.addFile(files);
					                         }


		 	    						    
		                                     
										    player1.on("capturecomplete", function(e, data) {
										        console.log("Capture Complete: ", data);

										        
										        postVideoToAmazon(e, data);
										        //return false; 
										        

										
										        //var img = $("<img/>").attr("src","data:image/png;base64," + player1.captureData.image);
										        
										        $(".preview2").empty().append(data.canvas);
										    });							     

			                                    
		                                	
		                                	
		                                }
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





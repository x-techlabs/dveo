


    
<div class="videoRight">
    <div class="row center-block contnet-wrap description">
        <div class="col-md-6 height" style="padding-right: 0px!important;">
            <div class="description-block content height">

                <p class="title-name"><i class="fa fa-video-camera"></i>{{$desc->title}}</p>
                
                <section class="videoSection content_list" style='cursor: pointer;'>
                    <div class="row center-block" style="padding-right: 5px;">
                        <div class="col-md-12" style="text-align: center;">
                        

    
   <script src="http://1stud.io/js/flowplayer6snapshot604/demo/javascript/snapshot-6.0.4.js"></script>

								<script>
								flowplayer.conf.notification = {
								        showDelay: 1000,
								            hideDelay: 2000
								        };

								        flowplayer.conf = {
								            splash: false
								        };

								        flowplayer.conf.snapshot = {
								            debuglog: true,
								            success: "Capture Complete",
								            error: "Capture Failed Try Again",
								            type: "jpg",
								            quality: 1,
								            //set the maximum width of the capture
								            maxwidth: 600,
								            thumbnails: true,
								            //set the thumbnails width
								            thumbnailwidth: 120,
								            originbaseurl: "http://crossorigin.electroteque.org/video/"
								        };
								
								</script>

								<div width="600px" id="playercontainer">
								    <!--div id="s3" class="flowplayer" data-usesnapshot="true" data-embed="false" data-snapshotname="test"-->
								    <div class="flowplayer" data-swf="http://1stud.io/js/flowplayer605/flowplayer.swf" data-key="$676546012598233" data-ratio="0.4167">
								        <video preload='none'>
								            <source type="video/mp4" src="https://s3.amazonaws.com/aceplayout/{{$desc->file_name}}.mp4"/>
								            <source type="video/webm" src="http://videos.electroteque.org/big_buck_bunny_480p_h264.webm"/>
								            <source type="video/mp4" src="http://videos.electroteque.org/big_buck_bunny_480p_h264.mp4"/>
								            <source type="video/ogg"   src="http://videos.electroteque.org/big_buck_bunny_480p_h264.ogv"/>
								
								        </video>
								    </div>
								
								
								    <div class="snapshot thumbpreview"></div>
								    <div class="snapshot preview"></div>
								</div>
								
								
								<!-- This is needed or else plupload will complain -->
								<button id="hiddenButton" style="display:none;"></button>
								
								<button id="save" style="display:none;">Save</button>


                        
   	                           <script>

                               $(document).ready(function() {

                                   var uploading = false;
                                   //plupload setup
                                   var uploader = new plupload.Uploader({
                                       browse_button: "hiddenButton",
                                       runtimes : 'html5,flash',
                                       //S3 bucket location
		                               url : 'https://prolivestream.s3-us-west-2.amazonaws.com/',
		                               multipart: true,
		                               //S3 policy and signature details
		                               multipart_params: {
		                                   'key': '{{$desc->file_name}}_1.jpg', // use filename as a key
		                                   'Filename': '{{$desc->file_name}}_1.jpg', // adding this to keep consistency across the runtimes
		                                   'acl': 'public-read',
		                                   'Content-Type': 'image/jpeg',
		                                   'AWSAccessKeyId' : 'AKIAIDGRDUJ7ZG5DNJEA',
		                                   'policy': '{{$policy_encoded}}',
		                                   'signature': '{{$signature}}'
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

                                   //flowplayer setup
                                   var api = flowplayer($("#s3"));

                                   //capture complete event. Here we start the S3 upload of the image and thumbnail
                                   api.bind("onCaptureComplete", function(e, api, image) {
                                       console.log("Capture Complete");
                                       //console.log("Image : ", image.image);
                                       //console.log("Thumbnail : ", image.thumbnail);

                                       if (uploading) return;

                                       uploading = true;

                                       var files = [];


                                       //setup the still image
                                       var file = new plupload.File(
                                           new o.File(null, {
                                               name: image.filename,
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
                                               name: image.filename,
                                               data: image.thumbnail
                                           })
                                       );

                                       //this is required to be able to differentiate between the two to chang the S3 path above.
                                       file.thumb = true;

                                       files.push(file);

                                       uploader.addFile(files);

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





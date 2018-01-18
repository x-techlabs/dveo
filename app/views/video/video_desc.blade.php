  <?php
  //https://videojs.electroteque.org/snapshot
 
/**
 * Snapshot Example with S3 client side upload support
 *
 * @author Daniel Rossi Electroteque Media <danielr@electroteque.org>
 * @copyright 2015
 */

/**
 * S3 Policy and Signature Generation Details
 */
$bucket = 'aceplayout';

// these can be found on your Account page, under Security Credentials > Access Keys
$accessKeyId = $aws_access_key_id;
$secret = $aws_secret_key;

// prepare policy
$policy = base64_encode(json_encode(array(
    // ISO 8601 - date('c'); generates uncompatible date, so better do it manually
    'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),
    'conditions' => array(
        array('bucket' => $bucket),
        array('acl' => 'public-read'),
        array('starts-with', '$key', ''),
        // for demo purposes we are accepting only images
        array('starts-with', '$Content-Type', ''),
        // Plupload internally adds name field, so we need to mention it here
        array('starts-with', '$name', ''),
        // One more field to take into account: Filename - gets silently sent by FileReference.upload() in Flash
        // http://docs.amazonwebservices.com/AmazonS3/latest/dev/HTTPPOSTFlash.html
        array('starts-with', '$Filename', ''),
    )
)));

// sign policy
$signature = base64_encode(hash_hmac('sha1', $policy, $secret, true));
?>      
<div class="videoRight">
    <div class="row center-block contnet-wrap description">
        <div class="col-md-6 height" style="padding-right: 0px!important;">
            <div class="description-block content height">

                <p class="title-name"><i class="fa fa-video-camera"></i>{{$desc->title}}</p>
                
                <section class="videoSection content_list" style='cursor: pointer;'>
                    <div class="row center-block" style="padding-right: 5px;">
                        <div class="col-md-12" style="text-align: center;">
	                                <script type="text/javascript">
							          // See the Configuring section to configure credentials in the SDK
							          AWS.config.credentials = new AWS.CognitoIdentityCredentials({
							        	  IdentityPoolId: 'us-east-1:1699ebc0-7900-4099-b910-2df94f52a030',
							        	  Logins: { // optional tokens, used for authenticated login
							        	    'graph.facebook.com': 'FBTOKEN',
							        	    'www.amazon.com': 'AMAZONTOKEN',
							        	    'accounts.google.com': 'GOOGLETOKEN'
							        	  }
							        	});
						
							          // Configure your region
							          AWS.config.region = 'us-west-2';
							        </script>
	                            <link href="/js/videojs_snapshot_580/demo/html/css/snapshot.min.css" rel="stylesheet" type="text/css">
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
	                            <script src="/js/videojs_snapshot_580/demo/javascript/snapshot-5.8.0.js"></script>
<!-- 	                            <script src="http://1stud.io/js/videojs_snapshot_580/dist/js/snapshot-5.8.0.min.js"></script> -->
	                            
	                            <script src="/js/videojs_snapshot_580/demo/javascript/plupload.full.min.js"></script>

                                 <div width="510px" id="localcontainer">
                        
                                    <video id="local" data-id = "{{ $video_id }}" class="video-js vjs-default-skin" controls preload="none" width="510" height="264"
                                        data-setup="{}">
                                        <source type="video/mp4" src="{{$video_file}}"/>
								        <source type="video/webm" src="//videos.electroteque.org/bitrate/big_buck_bunny_600k.webm"/>
								        <source type="video/ogg"   src="//videos.electroteque.org/bitrate/big_buck_bunny_600k.ogv"/>
								    </video>
								    <button id="save" style="display:none;">Save</button>
								    
								    <div id="myProgress">
  										<div id="myBar"></div>
									</div>
                                    @if($desc->source !== 'vimeo')
                                        <div class="snapshot preview2"></div>
                                    @endif
                                    
                                </div>
                        
                                  <script>
                                   
                                $(document).ready(function() {
                                    //alert("{{$file_name}}_1"); 
			                        var player1 = videojs('local');
                                    @if($desc->source !== 'vimeo')
    			                        player1.snapshot({
    								        //snapshotname: "test-[time]",
    								        snapshotname: "{{$file_name}}_1",
    								        previewContainer: ".preview",
    								        debuglog: true,
    								        //serverurl: "https://flowplayer.electroteque.org/snapshot/index/save",
    								        //tokenurl: "https://flowplayer.electroteque.org/snapshot/index/token",
    								        success: "Capture Complete",
    								        error: "Capture Failed Try Again",
    								        type: "jpg",
    								        savelocal: false,
    								        quality: 1, //0.9
    								        originbaseurl: "http://crossorigin.electroteque.org/video/",
    								        notification: {
    								            showDelay: 1000,
    								            hideDelay: 2000
    								        }
    								        ,
    								        snapshot: {
    								        	type: "jpg"
    								        	}
    								    });
                                    @endif

	                                 var uploader = new plupload.Uploader({
                                        browse_button: "hiddenButton",
                                        runtimes : 'html5,flash',
                                      
                                        //S3 bucket location
                                        //url : 'https://aceplayout.s3-us-west-2.amazonaws.com/',
                                        //url: 'https://s3.amazonaws.com/aceplayout',
                                        url : "https://aceplayout.s3.amazonaws.com:443/",
                                        multipart: true,
                                        //S3 policy and signature details
                                        multipart_params: {
                                            'key': '{{$file_name}}', // use filename as a key
                                            'Filename': '{{$file_name}}', // adding this to keep consistency across the runtimes
                                            'acl': 'public-read',
                                            'Content-Type': 'image/jpeg',
                                            //'AWSAccessKeyId' : 'AKIAJWU4DYR6OMHE2YPQ',
                                            //'policy': '{{$policy_encoded}}',
                                            //'signature': '{{$signature}}'
                                                'AWSAccessKeyId' : '<?php echo $accessKeyId; ?>',
                                                'policy': '<?php echo $policy; ?>',
                                                'signature': '<?php echo $signature; ?>'
                                        },

                                		file_data_name: 'file',
                                		filters : {
                                			// Maximum file size
                                			max_file_size : '10mb',
                                			// Specify what files to browse for
                                			mime_types: [
                                				{title : "Image files", extensions : "jpg,jpeg"}
                                			]
                                		},

                                        //file filters
                                        filters : {
                                            //minimum image width
                                            min_img_width: 100,
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
                                        flash_swf_url : 'https://1stud.io/js/videojs_snapshot_580/demo/javascript//Moxie.swf'
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
                                         alert(JSON.stringify(up));
                                         alert(JSON.stringify(file));
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

// 	 	    				       function dataURItoBlob(dataURI) {
// 	 	    				    	  var raw = window.atob(dataURI);
// 	 	    				    	  var rawLength = raw.length;

// 	 	    				    	  var uInt8Array = new Uint8Array(rawLength);

// 	 	    				    	  for (var i = 0; i < rawLength; ++i) {
// 	 	    				    	  uInt8Array[i] = raw.charCodeAt(i);
// 	 	    				    	  }

// 	 	    				    	  return new Blob([uInt8Array], {type: mimeType});
// 	 	    				    	  }

	 	    				       function dataURItoBlob(dataURI) {
	 	    				    	    var binary = atob(dataURI.split(',')[1]);
	 	    				    	    var array = [];
	 	    				    	    for(var i = 0; i < binary.length; i++) {
	 	    				    	        array.push(binary.charCodeAt(i));
	 	    				    	    }
	 	    				    	    return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
	 	    				    	}
	 	    				        
	 	    				        function sleep(delay) {
	 	    				         var start = new Date().getTime();
	 	    				         while (new Date().getTime() < start + delay);
	 	    				        }
	 	    				       
 	    						    function postVideoToAmazon(e, data1) {
						    	         console.log(555555555555,data1);
 	 	    						  var full_fn = 'https://onestudio.imgix.net/'+data1.filename+'.jpg?w=336&h=210&fit=crop&crop=entropy&auto=format,enhance&q=60';

	    							  //CORS ref http://bencoe.tumblr.com/post/30685403088/browser-side-amazon-s3-uploads-using-cors
                                      //http://stackoverflow.com/questions/2547046/make-a-bucket-public-in-amazon-s3
 	 	    						    
 		 	    					  AWS.config.update({accessKeyId: '{{$aws_access_key_id}}', secretAccessKey: '{{$aws_secret_key}}'});
 		 	    				     
	 	 	    					  // Configure your region
	 	 	    					  AWS.config.region = '';//'us-west-2';

	 	 	    					  var bucket = new AWS.S3({params: {Bucket: '{{$file_bucket}}'}});

                                      //http://stackoverflow.com/questions/12883871/how-to-upload-base64-encoded-image-data-to-s3-using-javascript-only
	 	 	    					  var typedArray = dataURItoBlob(data1.image);
	 	 	    					
 	 	    						  var params = {Key: ""+data1.filename+'.jpg', ContentType: "image/jpeg", Body: typedArray};

                                      bucket.upload(params).on('httpUploadProgress', function(evt) {

	 	 	 	    					  var progress = (evt.loaded * 100) / evt.total;
	 	 	 	    						  
	 	 	    						  console.log("Uploaded :: " + parseInt((evt.loaded * 100) / evt.total)+'%');
	 
	 	 	    						  var elem = document.getElementById("myBar");
	 	 	    						  elem.style.width = progress + '%';
	
	 	 	    						  if(progress >= 100){
	 	 	    							 //imgx purge after image content on s3 was changed
	 	 	    							 //alert(full_fn);
                                             var video_id = $('#local').data('id');
                                             console.log(6666666,video_id);
	 	 	    							 $.ajax({
									            url: ace.path('imgx_purge_image'),
									            type: "POST",
									            data: {
									                "img_url" : full_fn,
                                                    'video_id' : video_id
									            },
									            dataType: "json",
									            success: function (data) {
									               //alert(JSON.stringify(data));
									               //if(data.status) { }
	                                               //sleep(10000);
	 	 	    							       
	 	 	    							       // var imgel = $("img[src$='"+full_fn+"']");
	 	 	    							       // imgel.attr("src", imgel.attr("src") + "&t=" +  new Date().getTime());
	 	 	    							       //alert(imgel.attr("src"));
	 	 	    							  		 	 	    						
			 	 	    						   //window.location.reload(true);
			 	 	    						   //document.location.reload(true);
									               
									               window.location.replace("videos");
									            }
											  });
	 	 	    							  //location.reload();
	 	 	    						      //window.location.replace("videos");
	 	 	    						  }
 	 	    						      
 	 	    						  }).send(function(err, data) {
 	 	    							  //alert("bucket.upload err: "+err);
 	 	    							  //alert(JSON.stringify(data));
 	 	    						  });
 	 	    						  
                                       return;
                                       alert(full_fn);
                                       //ADD ALTERNATIVE OPTION... 
                                       //TO IMPLEMENT file select to upload
                                       //http://docs.aws.amazon.com/AWSJavaScriptSDK/guide/browser-configuring.html#The_Global_Configuration_Object__AWS_config_

 	 	    						    var filename = data1.filename;
 	 	    						    var filesiez = data1.size;
 	    						    	 
 	 	    						    var pos = filename.lastIndexOf('.');
 	 	                                var videoFormat = filename.slice(pos + 1);
 	 	                                var dataSubmit = data1;

 	    	                            // Get the policy  and signature
 	    	                            $.ajax({
 	    	                                url: "send_amazon_video_logo",
 	    	                                type: "POST",
 	    	                                async: true,
 	    	                                data: {
 	    	                                    "ext": 'jpg',
 	    	                                    "filename": data1.filename
 	    	                                },
 	    	                                dataType: "json",
 	    	                                success: function (data) {
 	    	                                	
 	    	                                    $("#key").val(data.filename);
 	    	                                    $("#policy").val(data.policy_encoded);
 	    	                                    $("#signature").val(data.signature);
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

						                files.push(file);
						                uploader.addFile(files);
			                         }
                                     
								    player1.on("capturecomplete", function(e, data) {
								        //console.log("Capture Complete: ", data);
								        console.log("Capture Complete ");
			        					console.log(777777777,e);
								        postVideoToAmazon(e, data);
								        return false;                       

								        
								        
								    	var files = [];
								    	alert(data.filename);
								    	var file = new plupload.File(
								    	  new o.File(null, {
								    	  name: data.filename,
								    	  data: data.image 
								    	  })
								    	);

								    	//do stuff for plupload here

								    	files.push(file);
								    	//file.customData = { id: 234 };
// 						                //setup the thumbnail image
// 						                var file = new plupload.File(
// 						                    new o.File(null, {
// 						                        name: '{{$desc->file_name}}_1.jpg',
// 						                        data: image.thumbnail
// 						                    })
// 						                );
// 						                //this is required to be able to differentiate between the two to chang the S3 path above.
// 						                file.thumb = true;
// 						                files.push(file);
								    	
								    	uploader.addFile(files);

								    	uploader.start();

								        //var img = $("<img/>").attr("src","data:image/png;base64," + player1.captureData.image);
								        
								        $(".preview2").empty().append(data.canvas);
								    });							     

								});
								</script>
                        </div>
                        <p>{{$desc->description}}</p>
                        <p>{{$desc->storage}}</p>
                    </div>
                </section>

            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>





<?php
/**
 * Snapshot Example with S3 client side upload support
 *
 * @author Daniel Rossi Electroteque Media <danielr@electroteque.org>
 * @copyright 2015
 */

/**
 * S3 Policy and Signature Generation Details
 */
$bucket = 'snapshot-electroteque-org';

// these can be found on your Account page, under Security Credentials > Access Keys
$accessKeyId = 'AKIAJD5DVL7TPFO6LJ3A';
$secret = 'AgEhYdI2URc7IO+YL8TAPBaXM5Z03ghUsQNIWK8M';

// prepare policy
$policy = base64_encode(json_encode(array(
    // ISO 8601 - date('c'); generates uncompatible date, so better do it manually
    'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),
    'conditions' => array(
        array('bucket' => $bucket),
        array('acl' => 'public-read'),
        array('starts-with', '$key', ''),
        // for demo purposes we are accepting only images
        array('starts-with', '$Content-Type', 'image/'),
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

<!DOCTYPE html>
<html lang="en">

<head>

    <title></title>
    <link rel="stylesheet" type="text/css"
          href="http://releases.flowplayer.org/5.5.1/skin/minimalist.css"
              />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script src="js/flowplayer.min.js"></script>

    <script type="text/javascript" src="js/plupload.full.min.js"></script>

    <style>
body { font: 12px "Lucida Grande",sans-serif; }
        .flowplayer { width: 620px; height: 350px; background-color: #444; margin: 50px auto;  }
        .flowplayer.is-loading { border: 1px solid #000; }
    </style>


</head>

<body id="plugins_snapshot">


<div id="wrap">

<style>
    .snapshot.btn {
            display: block;
            width: 32px;
        height: 25px;
        top: 30px;
        right: 5px;
        position: absolute;
        background: url('../images/snapshoticon.png');
        border-style:none;

        opacity: 0.7;

    }

    .snapshot.btn:hover {
            opacity: 1;

        }

    .notification {
            width: 300px;
        height: 50px;
        text-align: center;
        vertical-align: middle;
        background-color: #CCCCCC;
        border-radius: 10px;
        margin-left: auto ;
        margin-right: auto ;
    }

    .notification.message {
            color: #FFFFFF;
            font-size: 12px;

    }

    .snapshot.preview {
            float: right;
            width: 600px;

    }

    #player {
        float: left;
        background: #000 url(../images/bbbfp5.jpg) 0 0 no-repeat;
        background-position: center;
        background-size:620px 350px;
        width: 620px; height: 350px; background-color: #444;
    }

    #local {
        float: left;
        background: #000 url(../images/bbbfp5.jpg) 0 0 no-repeat;
        background-position: center;
        background-size:620px 350px;
        width: 620px; height: 350px; background-color: #444;
    }

    #jsapi {
        background: #000 url(../images/bbbfp5.jpg) 0 0 no-repeat;
        background-position: center;
        background-size:620px 350px;
        width: 620px; height: 350px; background-color: #444;
    }


</style>

<script src="http://1stud.io/js/videojs_snapshot_580/demo/javascript/snapshot-5.8.0.js"></script>




<div id="content">


<h1>
    <strong>Video Screen Snapshot S3 Client Side Upload Example</strong>
</h1>

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
    <div id="s3" class="flowplayer" data-usesnapshot="true" data-embed="false" data-snapshotname="test">
        <video preload='none'>
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
                url : 'https://<?php echo $bucket; ?>.s3.amazonaws.com:443/',
                multipart: true,
                //S3 policy and signature details
                multipart_params: {
                    'key': '${filename}', // use filename as a key
                    'Filename': '${filename}', // adding this to keep consistency across the runtimes
                    'acl': 'public-read',
                    'Content-Type': 'image/jpeg',
                    'AWSAccessKeyId' : '<?php echo $accessKeyId; ?>',
                    'policy': '<?php echo $policy; ?>',
                    'signature': '<?php echo $signature; ?>'
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


</div>


</body>
</html>
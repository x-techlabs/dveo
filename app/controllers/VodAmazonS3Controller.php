<?php
    /**
 * Created by Sublime.
 * User: user
 * Date: 11/10/16
 * Time: 11:00 AM
 */
require public_path().'/aws_sdk/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class VodAmazonS3Controller extends BaseController
{
	private $source_aws = null;
	private $dest_aws = null;

	public function __construct()
	{
		$access = array(
			       'accessKey' => 'AKIAIRTKQOOVDLF6KFZQ',
			       'secretKey' => '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',
			       'hostname' => 'https://s3-us-west-1.amazonaws.com/');
		$this->source_aws = new VodAmazonS3Manager($access);
		$accessd = array(
                               'accessKey' => 'AKIAILCMCGX2JPTEPUJA',
                               'secretKey' => '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',
                               'hostname' => 'https://s3-us-west-1.amazonaws.com/');
        $this->dest_aws = new VodAmazonS3Manager($accessd);
	}

	public function accessamazons3()
	{
		$aws = null;
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/AccessAmazonS3/1';
		$token = Input::get('token');
		$value = Input::get('origin');
 
		$payload = Input::all();
                $session = null;

		if (strcasecmp($value, 'src') == 0) {  
				// this is to over ride the $source_aws
				$accesskey = $payload['accessKey'];
				$secretkey = $payload['secretKey'];
				$hostname  = $payload['hostname'];

				$aws = array(
						'accessKey' => $accesskey,
						'secretKey' => $secretkey,
						'hostname' => $hostname);
				$this->source_aws = new VodAmazonS3Manager($aws);
                                $path = $path . "?token=" . $token;
				$session = json_decode($this->source_aws->post($path,$aws));
		}
        if (strcasecmp($value, 'dest') == 0) {
                         	// this is to over ride the $source_aws
				$accesskey = $payload['accessKey'];
				$secretkey = $payload['secretKey'];
				$hostname  = $payload['hostname'];

				$aws = array(
						'accessKey' => $accesskey,
						'secretKey' => $secretkey,
						'hostname' => $hostname);
				$this->dest_aws = new VodAmazonS3Manager($aws);
                                $path = $path . "?token=" . $token;
				$session = json_decode($this->dest_aws->post($path,$aws));
                }
        //$aws->set_session($session->{'_sessionid'});
        //Log::info('This is the vodRegister() output'+$session);

        header('Content-Type: application/json');
        return Response::json($session, 200);
	}

	public function displayallbuckets() 
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/DisplayAllBuckets/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$value = Input::get('origin');
		$response = null;

		$finalpath = $path . '?token=' . $token . '&sessionid=' . $sessionid;

		if (strcasecmp($value, 'src') == 0) {
			$response =  json_decode($this->source_aws->get($path,null));
		}
		if (strcasecmp($value, 'dest') == 0) {
			$response = json_decode($this->dest_aws->get($path,null));
		}
		//Log::info('This is the displayallbuckets() output'+$response);
		header('Content-Type: application/json');
		return Response::json($response,200);
	}

	public function downloadrequestmedia()
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/DownloadRequestedMedia/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$value = Input::get('origin');
		$response = null;


		$payloads = Input::all();
		if (strcasecmp($value, 'src') == 0) { 
			$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
		        $response = json_decode($this->source_aws->post($path,$payloads));
		}
                if (strcasecmp($value, 'dest') == 0) {
			$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
		        $response = json_decode($this->dest_aws->post($path,$payloads));
                }
                
		header('Content-Type: application/json');
		return Response::json($response,200);
	}

	public function getmediadownloadstatus()
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/GetMediaDownloadStatus/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$value = Input::get('origin');
		$response = null;

		if (strcasecmp($value, 'src') == 0) { 
			$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
			$response = json_decode($this->source_aws->post($path,null));
		}

                if (strcasecmp($value, 'dest') == 0) {
			$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
		        $response = json_decode($this->dest_aws->post($path,null));
                }
		header('Content-Type: application/json');
		return Response::json($response,200);
	}

	public function createnewbucket()
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/CreateNewBucket/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$newbucket = Input::get('newbucket');
		$value = Input::get('origin');
		$response = null;

		$payloads = Input::all();
		if (strcasecmp($value, 'src') == 0) { 
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid . '&newbucket=' . $newbucket;
			$response = json_decode($this->source_aws->get($path,null));
                }

                if (strcasecmp($value, 'dest') == 0) {
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid . '&newbucket=' . $newbucket;
			$response = json_decode($this->dest_aws->get($path,null));
                }
		header('Content-Type: application/json');
		return Response::json($response,200);
	}

	public function uploadmediaintobucket()
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/UploadMediaIntoBucket/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$value = Input::get('origin');
		$response = null;

		$payloads = Input::all();

		$filenamea = $payloads['filenamea'];
		$filenameb = $payloads['filenameb'];
		$filenamec = $payloads['filenamec'];
		$filenamed = $payloads['filenamed'];
		$filenamee = $payloads['filenamee'];

		$filenamef = $payloads['filenamef'];
		$filenameg = $payloads['filenameg'];
		$filenameh = $payloads['filenameh'];
		$filenamei = $payloads['filenamei'];
		$filenamej = $payloads['filenamej'];

		$bucketkeya = array(
							'bucketname' => 'aceplayout',
							'id' => 1,
							'key' => $filenamea);

		$bucketkeyb = array(
							'bucketname' => 'aceplayout',
							'id' => 2,
							'key' => $filenameb);

		$bucketkeyc = array(
							'bucketname' => 'aceplayout',
							'id' => 3,
							'key' => $filenamec);

		$bucketkeyd = array(
							'bucketname' => 'aceplayout',
							'id' => 4,
							'key' => $filenamed);

		$bucketkeye = array(
							'bucketname' => 'aceplayout',
							'id' => 5,
							'key' => $filenamee);

		$bucketkeyf = array(
							'bucketname' => 'aceplayout',
							'id' => 6,
							'key' => $filenamef);

		$bucketkeyg = array(
							'bucketname' => 'aceplayout',
							'id' => 7,
							'key' => $filenameg);

		$bucketkeyh = array(
							'bucketname' => 'aceplayout',
							'id' => 8,
							'key' => $filenameh);

		$bucketkeyi = array(
					
							'bucketname' => 'aceplayout',
							'id' => 9,
							'key' => $filenamei);

		$bucketkeyj = array(
					
							'bucketname' => 'aceplayout',
							'id' => 10,
							'key' => $filenamej);


		$post_data = array(
			               'bucketkeys' => array($bucketkeya, $bucketkeyb, $bucketkeyc, $bucketkeyd, $bucketkeye, $bucketkeyf, $bucketkeyg, $bucketkeyh, $bucketkeyi, $bucketkeyj));

		if (strcasecmp($value, 'dest') == 0) { 
					$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
					$response = json_decode($this->dest_aws->post($path,$post_data));
		}
		if (strcasecmp($value, 'src') == 0) { 
					$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
					$response = json_decode($this->source_aws->post($path,$post_data));
		}
		header('Content-Type: application/json');
		return Response::json($response,200);
	}


	public function uploadmediasingleintobucket()
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/UploadMediaIntoBucket/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$value = Input::get('origin');
		$response = null;

		$payloads = Input::all();

		$filenamea = $payloads['filenamea'];
		$filenameb = $payloads['filenameb'];
		$filenamec = $payloads['filenamec'];
		$filenamef = $payloads['filenamef'];

		$bucketkeya = array(
							'bucketname' => 'aceplayout',
							'id' => 1,
							'key' => $filenamea);

		$bucketkeyb = array(
							'bucketname' => 'aceplayout',
							'id' => 2,
							'key' => $filenameb);

		$bucketkeyc = array(
							'bucketname' => 'aceplayout',
							'id' => 3,
							'key' => $filenamec);

		$bucketkeyf = array(
							'bucketname' => 'aceplayout',
							'id' => 4,
							'key' => $filenamef);

		$post_data = array(
			               'bucketkeys' => array($bucketkeya, $bucketkeyb, $bucketkeyc,$bucketkeyf));

		if (strcasecmp($value, 'dest') == 0) { 
					$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
					$response = json_decode($this->dest_aws->post($path,$post_data));
		}
		if (strcasecmp($value, 'src') == 0) { 
					$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
					$response = json_decode($this->source_aws->post($path,$post_data));
		}
		header('Content-Type: application/json');
		return Response::json($response,200);
	}

	public function getmediauploadstatus()
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/GetMediaUploadStatus/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$value = Input::get('origin');
		$response = null;

                if (strcasecmp($value, 'dest') == 0) {
					$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
					$response = json_decode($this->dest_aws->get($path,null));
                }
                 
                if (strcasecmp($value, 'src') == 0) {
					$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
					$response = json_decode($this->source_aws->get($path,null));
                }
		header('Content-Type: application/json');
		return Response::json($response,200);
	}

	public function deletebucketmedia()
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/Dele2teBucketMedia/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$value = Input::get('origin');
		$response = null;

		$payloads = Input::all();
        
        	if(strcasecmp($value, "src") == 0)
        	{
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
			$response = json_decode($this->source_aws->post($path,$payloads));
        	}
        	if(strcasecmp($value, "dest") == 0)
        	{
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
			$response = json_decode($this->dest_aws->post($path,$payloads));
		}
		header('Content-Type: application/json');
		return Response::json($response,200);
	}

	public function deleteonebucket()
	{
		$path = 'http://138.197.211.60:8080/AmazonS3Manager/services/AmazonS3Manager/DeleteOneBucket/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$bucketname = Input::get('deletebucket');
		$value = Input::get('origin');
		$response = null;

		$valid = false;	

		if(strcasecmp($value, 'src') == 0)
        	{
        		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid . '&deletebucket='. $bucketname;
        		$response = json_decode($this->source_aws->get($path,null));
        	}
        	if(strcasecmp($value, 'dest') == 0)
		{
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid . '&deletebucket='. $bucketname;
			$response = json_decode($this->dest_aws->get($path,null));
		}
		header('Content-Type: application/json');
		return Response::json($response,200);	
	}

    function CallOtherServerByCurl($cRequestUrl, $post)
    {
        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL, $cRequestUrl);
        curl_setopt($crl, CURLOPT_HEADER, 0);
        curl_setopt($crl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($crl, CURLOPT_POST,1);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post);
        $ip = curl_exec($crl);

        $errno = curl_error($crl);
        //print "[$errno]";
        if ($errno)
        {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
        curl_close($crl);
        return $ip;
    }

    public function send_videos_to_amazon()
    {
        $video_format = trim( Input::get('video_format') );

        // UUID generation
        mt_srand((double)microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = "_"; // "_"
        $filename = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        $filename = mb_strtolower($filename);

        $form = array(
            'key' => 'videos/' . $filename . '.' . $video_format . '',
            'AWSAccessKeyId' => 'AKIAIDGRDUJ7ZG5DNJEA',
            'acl' => 'public-read',
        );

        $form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [ { "acl": "public-read" },
                                { "bucket": "prolivestream" },
                                [ "starts-with", "$key",
                        "videos/' . $filename . '.' . $video_format . '"
                    ]
                ]
            }';

        $form['policy_encoded'] = base64_encode($form['policy']);
        $form['signature'] = base64_encode(hash_hmac(
                'sha1', $form['policy_encoded'],
                'VneSpJvid3oY1pA17KQ9zRmqnpb6sudn0MkY49im',
                true
            )
        );

        return Response::json([
            'policy_encoded' => $form['policy_encoded'],
            'signature' => $form['signature'],
            'filename' => $form['key']
        ], 200);
    }

    //-------------------------------------------------------------------------- 
    // http://docs.aws.amazon.com/AmazonS3/latest/dev/LLuploadFilePHP.html

	public function video_add_to_table()
	{
		$videoDataString = Input::get('videoData');
        $payload = json_decode($videoDataString, true);
        //Log::info( print_r($payload, true) );
		//$payload = Input::all(); 

        $p = strrpos($payload['file_name'], '.');
        $payload['file_name'] = substr($payload['file_name'], 0, $p);

        if ($payload['title']=="null" || $payload['title']=='')
        {
            $fparts = explode('/', $payload['file_name']);
            $p = strpos($fparts[1], '_');
            $payload['title'] = substr($fparts[1], $p+1);

            $payload['title'] = str_replace('_HD_Profile', '', $payload['title']);
            $payload['title'] = str_replace('_SD_Profile', '', $payload['title']);
            $payload['title'] = str_replace('_SD_Profile_1', '', $payload['title']);
            $payload['title'] = str_replace('_', ' ', $payload['title']);

            $payload['description'] = $payload['title'];
        }

		$video = new Video;
		$video->title        		= $payload['title'];
		$video->description  		= $payload['description'];
        $video->channel_id          = BaseController::get_channel_id();
		$video->thumbnail_name 		= "https://s3.amazonaws.com/aceplayout/" . $payload['thumbnail_name'];
		$video->start_time 			= $payload['start_time'];
		$video->duration 			= $payload['duration'];
		$video->file_name 			= $payload['file_name'];
		$video->video_format 		= $payload['video_format'];
		$video->job_id 				= $payload['job_id'];
		$video->encode_status 		= $payload['encode_status'];
		$video->type 				= $payload['type'];
		$video->hd_width 			= $payload['hd_width'];
        $video->hd_height 			= $payload['hd_height'];      
        $video->hd_file_size 		= $payload['hd_file_size']; 
        $video->hd_video_bitrate	= $payload['hd_video_bitrate'];
        $video->hd_audio_codec		= $payload['hd_audio_codec'];
        $video->hd_video_codec		= $payload['hd_video_codec'];
        $video->hd_mime_type		= $payload['hd_mime_type'];
        $video->sd_file_name		= $payload['sd_file_name'];
        $video->sd_duration			= $payload['sd_duration'];
        $video->sd_width            = $payload['sd_width'];
        $video->sd_height           = $payload['sd_height'];
        $video->sd_file_size        = $payload['sd_file_size'];
        $video->sd_video_bitrate    = $payload['sd_video_bitrate'];
        $video->sd_audio_codec      = $payload['sd_audio_codec'];
        $video->sd_video_codec      = $payload['sd_video_codec'];
        $video->sd_mime_type        = $payload['sd_mime_type'];
        $video->mb_file_name        = $payload['mb_file_name'];
        $video->mb_duration         = $payload['mb_duration'];
        $video->mb_width            = $payload['mb_width'];
        $video->mb_height           = $payload['mb_height'];
        $video->mb_file_size        = $payload['mb_file_size'];
        $video->mb_video_bitrate    = $payload['mb_video_bitrate'];
        $video->mb_audio_codec      = $payload['mb_audio_codec'];
        $video->mb_video_codec      = $payload['mb_video_codec'];
        $video->mb_mime_type        = $payload['mb_mime_type'];
        $video->created_at          = time();   // $payload['created_at'];
        $video->updated_at          = time();   // $payload['updated_at'];
        $video->storage             = ' ';
        $status = $video->save();

        $response = array(
        					'update' => 'success');
        header('Content-Type: application/json');
        return Response::json($response, 200);
	}

    //-------------------------------------------------------------------------- 

    public function GetMetadataFrom($minfo, $section, $key, $removeFilter=array())
    {
        for($i=0;$i<count($minfo);$i++)
        {
            if (trim($minfo[$i])==$section) break;
        }

        for(;$i<count($minfo);$i++)
        {
            if (trim($minfo[$i])=='') break;

            $keyValue = explode(':', $minfo[$i]);
            if (trim($keyValue[0]) == $key) 
            {
                $out = trim($keyValue[1]);
                foreach($removeFilter as $rf) $out = str_replace($rf, '', $out);
                return trim($out);
            }
        }
        return '';
    }

    public function getmetadata($info)
    {
        $fullPath = storage_path('videos/'.$info[1]);
        $command = escapeshellcmd("mediainfo -full $fullPath");
        $out = shell_exec($command." 2>&1");
        $minfo = explode("\n", $out);

        //Log::info(print_r($minfo, true) );

        $result  = $info[2];
        $result .= '^'.($this->GetMetadataFrom($minfo, 'General', 'Duration') / 1000);
        $result .= '^'.$this->GetMetadataFrom($minfo, 'Video', 'Width');
        $result .= '^'.$this->GetMetadataFrom($minfo, 'Video', 'Height');
        $result .= '^'.$this->GetMetadataFrom($minfo, 'General', 'File size');
        $result .= '^'.($this->GetMetadataFrom($minfo, 'Video', 'Bit rate') / 1000);
        $result .= '^'.$this->GetMetadataFrom($minfo, 'Audio', 'Format');
        $result .= '^'.$this->GetMetadataFrom($minfo, 'Video', 'Format');
        $result .= '^'.$this->GetMetadataFrom($minfo, 'General', 'Internet media type');
        return $result;
    }

    //-------------------------------------------------------------------------- 

    public function uploadfilestoawsserver_step0($info, $timeStamp)
    {
        // Create a new multipart upload and get the upload ID.

        // $info = status | filename2Upload | AWSObjectKeyName | UploadId | parts | ETags for each partno
        // split filename 
        $cno = 'channel_'.BaseController::get_channel_id();
        $info[2] = str_replace($cno.'_', $cno.'/'.$timeStamp.'_', $info[1]);

        $s3 = S3Client::factory( array('key' => 'AKIAIMCQJSKDT4QOHJVA', 'secret' => 'sVaocQvafINQ85/j7kQ/nNV2yBOT0H4aA/U0eoQ6'));
        $result = $s3->createMultipartUpload(array(
            'Bucket'       => "aceplayout",
            'Key'          => $info[2],
            'StorageClass' => 'STANDARD',  // 'REDUCED_REDUNDANCY',
            'ACL'          => 'public-read',
        ));
        $info[3] = $result['UploadId'];
        $info[0] = 1;
        return $info; 
    }

    public function uploadfilestoawsserver_step1($info)
    {
        // Upload the file in parts.

        // $info = status | filename2Upload | AWSObjectKeyName | UploadId | parts | ETags for each partno
        $offset = $info[4] * (1024 * 1024 * 5);   
        $fullPath = storage_path('videos/'.$info[1]);
        $fsize = filesize($fullPath);

        if ($offset >= $fsize) { $info[0] = 2;  return $info; }

        $file = fopen($fullPath, 'r');
        // Though fopen sets pointer to beginning of file, it is good practise to specify whence
        fseek($file, $offset, SEEK_SET);  

        $s3 = S3Client::factory( array('key' => 'AKIAIMCQJSKDT4QOHJVA', 'secret' => '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'));
        try {    
            $result = $s3->uploadPart(array(
                'Bucket'     => "aceplayout",
                'Key'        => $info[2],
                'UploadId'   => $info[3],
                'PartNumber' => $info[4]+1,
                'Body'       => fread($file, 5 * 1024 * 1024)
            ));

            $info[4]++;
            if ($info[5] != '') $info[5] .= '^'; 
            $info[5] .= $result['ETag'];

            fclose($file);
        } 
        catch (S3Exception $e) {
            $result = $s3->abortMultipartUpload(array(
                'Bucket'     => "aceplayout",
                'Key'        => $info[2],
                'UploadId'   => $info[3]
            ));
        }
        return $info;
    }

    public function uploadfilestoawsserver_step2($info)
    {
        // 4. Complete multipart upload.
        $parts = array();
        $tags = explode('^', $info[5]);
        foreach($tags as $k => $t) 
            $parts[] = array('PartNumber' => $k+1, 'ETag' => $t);


        $s3 = S3Client::factory( array('key' => 'AKIAIMCQJSKDT4QOHJVA', 'secret' => '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'));
        $result = $s3->completeMultipartUpload(array(
            'Bucket'   => "aceplayout",
            'Key'      => $info[2],
            'UploadId' => $info[3],
            'Parts'    => $parts
        ));
        $info[0] = 4;
        $info[] = $result['Location'];

        return $info;
    }

    public function uploadfilestoawsserver()
    {
        // $filesToUpload[0] = status of all files 0 or 1
        // $filesToUpload[i] = status|filename2Upload|AWSObjectKeyName|UploadId|parts

		$filesToUpload = explode('~', Input::get('pids'));
        $completeCount = 1;
        $timeStamp = time();
        for ($i=1 ; $i < count($filesToUpload) ; $i++)
        {
    		$info = explode('|', $filesToUpload[$i]);

            if ($info[0]=='4') {  $completeCount++;  continue;  }
            else if ($info[0]=='0') $info = $this->uploadfilestoawsserver_step0($info, $timeStamp);
            else if ($info[0]=='1') $info = $this->uploadfilestoawsserver_step1($info);
            else if ($info[0]=='2') $info = $this->uploadfilestoawsserver_step2($info);

    		$filesToUpload[$i] = implode('|', $info);
            //Log::info( print_r($info, true) );
        }

        if ($completeCount == count($filesToUpload))
        {
            $out = array();
            for ($i=1 ; $i < count($filesToUpload)-1 ; $i++)
            {
                $info = explode('|', $filesToUpload[$i]);
                $out[] = $this->getmetadata($info);

                $fullPath = storage_path('videos/'.$info[1]);
                unlink($fullPath);

            }

            $info = explode('|', end($filesToUpload));
            $out[] = $info[2];

            $fullPath = storage_path('videos/'.$info[1]);
            unlink($fullPath);

            print '1~'.implode('|', $out);
            exit();
        }
		print implode('~', $filesToUpload);
    }

    //-------------------------------------------------------------------------- 
	public function createThumbnail($f)
    {
        $fullPath = storage_path('videos/'.$f);
        $p = strrpos($fullPath, '.');
        $jpg = substr($fullPath, 0, $p).'.jpg';
        $jpg = str_replace('_HD_Profile', '_HD_Profile_1', $jpg);
        if (file_exists($jpg)) unlink($jpg); 

        $cmd = "ffmpeg -i $fullPath -ss 00:00:01.000 -vframes 1 $jpg";
        //Log::info($cmd);
        exec($cmd);
        return str_replace( storage_path('videos/'), '', $jpg);
    }

	public function transcodeProgress($progressFile)
    {
        $lines = file( storage_path('videos/'.$progressFile) );
        for ($i = count($lines)-1 ; $i > 0 ; $i--)
        {
            $pos = strpos($lines[$i], 'out_time_ms=');
            if ($pos !== false) 
            {
                $d = substr($lines[$i], $pos+12) / 1000;
                return $d;
            }
        }
        return 0;
    }

	public function istranscodeprocessComplete()
    {
		$fileUploadData = explode('^', Input::get('pids'));

		$pids = explode('|', $fileUploadData[0]);
		$progressFiles = explode('|', $fileUploadData[2]);
        $completedTime = 0;
        $totalDuration = 3000 * $fileUploadData[3];
        $complete = 0;

        for ( $i = 0 ; $i < count($pids) ; $i++)
        {
            $completedTime += $this->transcodeProgress($progressFiles[$i]);
            $pid = $pids[$i];
            $out = exec("ps -p $pid");
            if (strpos($out, $pid) === false) $complete++;   
        }

        if ($complete < 3)
        {
            $progress = intval( ($completedTime / $totalDuration) * 100 );
            Log::info('progress = '.$progress);
            print '0^'. $progress;  
            exit();
        }

        // delete progress status files  
        for ( $i = 0 ; $i < count($progressFiles) ; $i++)
        {
            $fullPath = storage_path('videos/'.$progressFiles[$i]);
            unlink($fullPath);
        }


        $out = array();
		$files = explode('|', $fileUploadData[1]);

        // Create a thumbnail from HD Profile, HD Profile is the first element in $files array
        $files[] = $this->createThumbnail($files[0]);

        foreach($files as $f)
        {
            // status|filename2Upload|AWSObjectKeyName|UploadId|parts
            $out[] = '0|'.$f.'||0|0||';
        }
        print '1^';
        print '0~'.implode('~', $out);
    }

    //-------------------------------------------------------------------------- 

    public function ProgressiveTranscode($profile, $sourcefile)
    {
        $pn = str_replace(' ', '_', $profile['name']);
        $ext = '.'.pathinfo($sourcefile, PATHINFO_EXTENSION); 
        $destinationfile = str_replace($ext, '_'.$pn.$ext, $sourcefile);
        $logfile = str_replace($ext, '_'.$pn.'.pbr', $sourcefile);

        $cmd = "ffmpeg -i $sourcefile -c:v libx264 -c:a aac ";
        $cmd .= " -r ".$profile['fps'];                                     // 29.97 
        $cmd .= " -b:v ".$profile['videoBitrate']."k";                      // 2500k 
        $cmd .= " -b:a ".$profile['audioBitrate']."k";                      // 128k
        $cmd .= " -ar ".$profile['audioSampleRate'];                        // 48000
        $cmd .= " -vf \"scale=w=".$profile['width'].":h=".$profile['height'].",setdar=dar=".str_replace(':', '/',$profile['aspectMode'])."\" ";      // -vf scale=1280x720,setdar=16:9 
        $cmd .= " -progress $logfile ";
        $cmd .= "$destinationfile";

        Log::info($cmd);
        if (file_exists($destinationfile)) unlink($destinationfile); 
        if (file_exists($logfile)) unlink($logfile); 

        $command = 'nohup '.$cmd.' > /dev/null 2>&1 & echo $!';
        exec($command ,$op);
        return array($op[0], basename($destinationfile), basename($logfile));

//      avconv -i flower_loop.mp4 -c:v libx264 -c:a libvo_aacenc -r 29.97 -b:v 2500k -b:a 128k -ar 48000 -vf "scale=w=1280:h=720, setdar=dar=16/9" -threads 0 flower_loop_1.mp4
    }

    public function start_transcode()
    {
		$hd_profile_1 = array(
						    'id' =>'1',
						    'keyframeInterval' => 250,
						    'presetName' => 'HD Profile',
						    'createdAt' => '2016/10/17',
						    'updatedAt' => '2016/10/17',
						    'width' => 1280,
    						'height' => 720,
    						'upscale' => true,
    						'fps' => 29.97,
    						'name' => 'HD Profile',
    						'title' => 'MP4 H.264',
    						'videoBitrate' => 2500,
    						'audioBitrate' => 128,
    						'x264Options' => '',
    						'aspectMode' => '16:9',
    						'audioSampleRate' => '48000',
    						'priority' => 1,
    						'timeCode' => true,
    						'extname' => 'mp4');
		$sd_profile_1 = array(
						    'id' => '2',
						    'keyframeInterval' => 250,
						    'presetName' => 'SD Profile -1',
						    'createdAt' => '2016/10/17',
						    'updatedAt' => '2016/10/17',
						    'width' => 720,
    						'height' => 480,
    						'upscale' => true,
    						'fps' => 29.97,
    						'name' => 'SD Profile 1',
    						'title' => 'MP4 H.264 Basic',
    						'videoBitrate' => 1200,
    						'audioBitrate' => 96,
    						'x264Options' => '+ildct+ilme -top 1',  // interlace video generation
    						'aspectMode' => '16:9',
    						'audioSampleRate' => '48000',
    						'priority' => 2,
    						'timeCode' => true,
    						'extname' => 'mp4');
		$sd_profile_2 = array(
						    'id' => '3',
						    'keyframeInterval' => 250,
						    'presetName' => 'SD Profile -2',
						    'createdAt' => '2016/10/17',
						    'updatedAt' => '2016/10/17',
						    'width' => 404,
    						'height' => 224,
    						'upscale' => true,
    						'fps' => 29.97,
    						'name' => 'SD Profile',
    						'title' => 'MP4 H.264 Auto',
    						'videoBitrate' => 400,
    						'audioBitrate' => 56,
    						'x264Options' => '',  // interlace video generation
    						'aspectMode' => '16:9',
    						'audioSampleRate' => '48000',
    						'priority' => 3,
    						'timeCode' => true,
    						'extname' => 'mp4');


		$files = explode('|', Input::get('name'));

        foreach($files as $f)
        {
            $fullPath = storage_path('videos/'.$f);

            // Find original video duration
            $command = escapeshellcmd("mediainfo -full $fullPath");
            $out = shell_exec($command." 2>&1");
            $minfo = explode("\n", $out);
            $duration = $this->GetMetadataFrom($minfo, 'General', 'Duration') / 1000;
            Log::info("duration = $duration");
            
            $pid1 = $this->ProgressiveTranscode($hd_profile_1, $fullPath);
            $pid2 = $this->ProgressiveTranscode($sd_profile_1, $fullPath);
            $pid3 = $this->ProgressiveTranscode($sd_profile_2, $fullPath);

            $outResult[] = $pid1[0].'|'.$pid2[0].'|'.$pid3[0];  // process ids of three threads
            $outResult[] = $pid1[1].'|'.$pid2[1].'|'.$pid3[1];  // output file names
            $outResult[] = $pid1[2].'|'.$pid2[2].'|'.$pid3[2];  // progress status file names
            $outResult[] = $duration;
        }
        print implode('^', $outResult); 
    }

    //-------------------------------------------------------------------------- 

	public function pushmediaobject()
	{
        $out = '';

        if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['tmp_name'] != '') 
        {
            $fname = 'channel_'.BaseController::get_channel_id().'_'.str_replace(' ', '_', $_FILES['uploadedFile']['name']);
            $fname = str_replace('+','_',$fname);

            $fullPath = storage_path('videos/'.$fname);
            Log::info($_FILES['uploadedFile']['tmp_name'].' => '.$fullPath);
            move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $fullPath);
            $out = basename($fullPath);
        }
        print 'file uploaded|'.$out;
        exit();
	}
}

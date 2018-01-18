<?php
    /**
 * Created by Sublime.
 * User: user
 * Date: 11/10/16
 * Time: 11:00 AM
 */
class VodTranscoderController extends BaseController
{
	private $HD_1280720_profile_1 = null;
	private $SD_720480_profile_2  = null;
	private $SD_404224_profile_3  = null;
	private $newProfile = null;

	public function __construct()
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
    						'name' => 'SD Profile -1',
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

		$this->HD_1280720_profile_1 = new VodTranscoderManager($hd_profile_1);
		$this->SD_720480_profile_2 = new VodTranscoderManager($sd_profile_1);
		$this->SD_404224_profile_3 = new VodTranscoderManager($sd_profile_2);
	}

	public function createtranscodingprofile()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/CreateTranscodingProfile/1';
		$token = Input::get('token');
		$payload = Input::all();

		$name = $payload['name'];
		if( (strcasecmp($name, 'HD Profile') != 0) || (strcasecmp($name, 'SD Profile -1') != 0) || (strcasecmp($name, 'SD Profile -2') != 0) )
		{

			$this->newProfile = new VodTranscoderManager($payload);
			$path = $path . '&token=' . $token;
			$this->newProfile->set_profile_hd1($name);
			$response = json_decode($this->newProfile->put($path,$payload));
		}
		if(strcasecmp($name, 'HD Profile') == 0)
		{  
			$path = $path . '&token=' . $token;
			$this->HD_1280720_profile_1->set_profile_hd1($name);
			$response = json_decode($this->HD_1280720_profile_1->put($path,$payload));
		}
		if(strcasecmp($name, 'SD Profile -1') == 0)
		{  
			$path = $path . '&token=' . $token;
			$this->SD_720480_profile_2->set_profile_sd1($name);
			$response = json_decode($this->SD_720480_profile_2->put($path,$payload));
		}
		if(strcasecmp($name, 'SD Profile -2') == 0)
		{  
			$path = $path . '&token=' . $token;
			$this->SD_404224_profile_3->set_profile_sd2($name);
			$response = json_decode($this->SD_404224_profile_3->put($path,$payload));
		}
		
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}


	public function deletetranscodingprofile()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/DeleteTranscodingProfile/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$profileid = Input::get('profileid');

		$finalpath = $path . '&token=' . $token . '&sessionid=' . $sessionid . '&profileid=' . $profileid;

		$response = json_decode($this->HD_1280720_profile_1->get($finalpath,null));

		//Log::info('This is the deletetranscodingprofile() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function modifytranscodingprofile()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/ModifyTranscodingProfile/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$payload = Input::all();

		$name = $payload['name'];
		if( (strcasecmp($name, 'HD Profile') != 0) || (strcasecmp($name, 'SD Profile -1') != 0) || (strcasecmp($name, 'SD Profile -2') != 0) )
		{
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
			$this->newProfile->set_profile_hd1($name);
			$response = json_decode($this->newProfile->post($path,$payload));
		}
		if(strcasecmp($name, 'HD Profile') == 0)
		{  
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
			$this->HD_1280720_profile_1->set_profile_hd1($name);
			$response = json_decode($this->HD_1280720_profile_1->post($path,$payload));
		}
		if(strcasecmp($name, 'SD Profile -1') == 0)
		{  
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
			$this->SD_720480_profile_2->set_profile_sd1($name);
			$response = json_decode($this->SD_720480_profile_2->post($path,$payload));
		}
		if(strcasecmp($name, 'SD Profile -2') == 0)
		{  
			$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
			$this->SD_404224_profile_3->set_profile_sd2($name);
			$response = json_decode($this->SD_404224_profile_3->post($path,$payload));
		}
		//Log::info('This is the modifytranscodingprofile() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function getalltranscodingprofile()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/GetAllVideoTranscodingProfile/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
		$response = json_decode($this->HD_1280720_profile_1->get($path,null));
		
		//Log::info('This is the getalltranscodingprofile() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function createsingletranscodingjobs()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/CreateTranscodingJobs/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$payload = Input::all();

		$name = $payload['name'];

		$media = array(
						'bucketname' => 'phase-2-testsuite',
						'id' => '1',
						'key' => $name,
						'amazon' => true,
						'ftp' => false);

		$postdata = array(
							'awssessionid' => 1,
							'ftpsessionid' => 1,
							'profileid' => array(1,2,3),
							'profilename' => array('HD-Profile','SD-Profile-1','SD-Profile-2'),
							'medialist' => array($media),
							'priority' => 1,
							'jobcreationstart' => '10/1/2016 12:00:00');

		// for the current requirement we are not verifying the the profile names of HD Profile SD Profile -1 and SD Profile -2
		$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
		$response = json_decode($this->HD_1280720_profile_1->post($path,$postdata));

		//Log::info('This is the getalltranscodingprofile() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}


	public function createtranscodingjobs()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/CreateTranscodingJobs/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$payload = Input::all();

		$namea = $payload['namea'];
		$nameb = $payload['nameb'];
		$namec = $payload['namec'];
		$named = $payload['named'];
		$namee = $payload['namee'];

		$media1 = array(
						 'bucketname' => 'phase-2-testsuite',
						 'id' => '1',
						 'key' => $namea,
						 'amazon' => true,
						 'ftp' => false);
		$media2 = array(
						 'bucketname' => 'phase-2-testsuite',
						 'id' => '2',
						 'key' => $nameb,
						 'amazon' => true,
						 'ftp' => false);
		$media3 = array(
						 'bucketname' => 'phase-2-testsuite',
						 'id' => '3',
						 'key' => $namec,
						 'amazon' => true,
						 'ftp' => false);
		$media4 = array(
						 'bucketname' => 'phase-2-testsuite',
						 'id' => '4',
						 'key' => $named,
						 'amazon' => true,
						 'ftp' => false);
		$media5 = array(
						 'bucketname' => 'phase-2-testsuite',
						 'id' => '5',
						 'key' => $namee,
						 'amazon' => true,
						 'ftp' => false);


		$postdata = array(
							'awssessionid' => 1,
							'ftpsessionid' => 1,
							'profileid' => array(1,2,3),
							'profilename' => array('HD-Profile','SD-Profile-1','SD-Profile-2'),
							'medialist' => array($media1,$media2,$media3,$media4,$media5
												),
							'priority' => 1,
							'jobcreationstart' => '10/1/2016 12:00:00');



		// for the current requirement we are not verifying the the profile names of HD Profile SD Profile -1 and SD Profile -2
		$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
		$response = json_decode($this->HD_1280720_profile_1->post($path,$postdata));

		//Log::info('This is the getalltranscodingprofile() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function scheduletranscodingjobs()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/ScheduleTranscodingJobs/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$payload = Input::all();

   
		$postdata = array(
						'starttime' => 'now',
						'jobids' => array(1),
						'guardtime' => '00:02'); 

		// for the current requirement we are not going to check the number of video's been transcoded in the batch its 5 fixed.
		$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
		$response = json_decode($this->HD_1280720_profile_1->put($path,$postdata));

		//Log::info('This is the scheduletranscodingjobs() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function gettranscodingjobsstatus()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/GetTranscodingJobsStatus/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$jobid     = Input::get('jobid');

		
		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid . '&jobid=' . $jobid;
		$response = json_decode($this->HD_1280720_profile_1->get($path,null));

		//Log::info('This is the gettranscodingjobsstatus() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function gettranscodingjobsresults()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/GetTranscodingJobsResults/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$jobid     = Input::get('jobid');

		//$path = $path . '&token=' . $token . '&sessionid=' . $sessionid . '&jobid=' . $jobid;
		$response = json_decode($this->HD_1280720_profile_1->get($path,null));

		//Log::info('This is the gettranscodingjobsresults() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function generateallthumbnails()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/GenerateAllThumbnails/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$payload = Input::all(); 

		// for the current requirement we are not going to check the number of video's been transcoded in the batch its 5 fixed.
		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
		$response = json_decode($this->HD_1280720_profile_1->put($path,$payload));

		//Log::info('This is the generateallthumbnails() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function canceltranscodingjobs()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/CancelTranscodingJobs/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$jobid     = Input::get('jobid');

		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
		$response = json_decode($this->HD_1280720_profile_1->get($path,null));

		//Log::info('This is the canceltranscodingjobs() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function notifytranscodingjobserrors()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/NotifyTranscodingJobsErrors/1'; 
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;

		$response = json_decode($this->HD_1280720_profile_1->get($path,null));

		//Log::info('This is the notifytranscodingjobserrors() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function gets3mediametadatainfo()
	{
		$path = 'http://138.197.211.60:8080/TranscodeManager/services/TranscodeManager/GetS3MediaMetadataInfo/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$bucketname = Input::get('bucketname');
		$media = Input::get('media');

		$path = $path . '?token=' . $token . '&sessionid=' . $sessionid . '&bucketname=' . $bucketname . '&media=' . $media;

		$response = json_decode($this->HD_1280720_profile_1->get($path,null));

		
		//Log::info('This is the gets3mediametadatainfo() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function vod_add_video()
	{
		$payload = Input::all(); 

		$video = new Video;
		$video->title        		= $payload['title'];
		$video->description  		= $payload['description'];
                $video->channel_id              = 13;
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
        $video->created_at          = $payload['created_at'];
        $video->updated_at          = $payload['updated_at'];
        $video->storage             = ' ';
        $status = $video->save();

        $response = array(
        					'update' => 'success');
        header('Content-Type: application/json');
        return Response::json($response, 200);
	}

}

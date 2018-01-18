<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/26/14
 * Time: 3:22 PM
 */

use GuzzleHttp\Client;

class UploadController extends BaseController
{
    public function upload()
    {

    	$this->data['s3_backet'] = 'aceplayout';
    	$this->data['aws_access_key_id'] = 'AKIAJWU4DYR6OMHE2YPQ';
    	$this->data['aws_secret_key'] = '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';
    	
    	if (empty($path)) {

            // Return the view
            return $this->render('upload/upload2');
            //return $this->render('upload/addvideos');

        }

    }

    public function upload2()
    {

    	$this->data['s3_backet'] = 'aceplayout';
    	$this->data['aws_access_key_id'] = 'AKIAJWU4DYR6OMHE2YPQ';
    	$this->data['aws_secret_key'] = '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';
    	
    	if (empty($path)) {

            // Return the view
            return $this->render('upload/upload2');
            //return $this->render('upload/addvideos');

        }


    }

    public function hybrikFileSuccess($jobId)
    {
        $row = Video::where('job_id',$jobId)->first();
        if(!empty($row) && count($row) > 0){
        $row->job_id = '';
            $row->save();
        }

    }

    public function uploadLink()
    {
        $this->data['title'] = "";
        $this->data['collections'] = array();
        $this->data['part2'] = $this->render('video/add_video');

        $this->data['extLink'] = "";
        $this->data['source_dd'] = "unknown";
        return $this->render('upload/uploadlink');
    }
    public function createVideoFromS3() 
    {
        $path = Input::get('uuid');
        $title = Input::get('name');
        $fileKey = Input::get('key');
        $useHybrik = Input::get('hybrik');
        $video = new Video;
        $video->title = $title;
        if ($useHybrik === 'true')
        {
            $video->file_name = $path.'_720p';
            $video->sd_file_name = $path.'_360p';
        }
        else
            $video->file_name = $path;
        $video->video_format = "MP4";
        $video->channel_id = BaseController::get_channel_id();
        $video->storage = '';
        $video->source = "internal";
        $video->duration = 200;
        $video->thumbnail_name = '/images/'.$path.'.png';
        $commandString = 'ffmpeg -ss 0:03 -i https://s3.amazonaws.com/aceplayout/'.$fileKey.' -vframes 1 /var/www/1stud.io/public_html/1stud/public/images/'.$path.'.png 2>&1';
        exec($commandString, $output);

        $timeString="00:00:00";
        foreach ($output as $line) {
           $pos = strpos($line, "Duration:"); 
           if ($pos === false) {
           } else {
               $timeString = substr($line, $pos + 10, 8);
               $hoursStr = substr($timeString, 0, 2);
               $minutesStr = substr($timeString, 3, 2);
               $secondsStr = substr($timeString, 6, 2);
               $totalTime =(int)$hoursStr * 3600 + (int)$minutesStr * 60 + (int)$secondsStr;
               $video->duration = $totalTime;
           }
        }

        if ($useHybrik === 'true')
        {

            $client = new Client();
//			$response = $client->request('POST', 'http://107.170.239.152:8000/submitjob', [
//			$response = $client->request('POST', 'http://127.0.0.1:8000/submitjob', [
			$response = $client->request('POST', 'http://localhost:8000/submitjob', [
				'form_params' => [
				'key' => $fileKey
				]
			]);
        	$video->job_id = $response->getBody();
        }
        $video->save();
        return $path . "   ". $title . "||".$timeString."||";

    }


    public function send_amazon_logo() {
        $ext = Input::get('ext');

        if ($ext == '' || !is_string($ext)) {

            App::abort(403, 'Logo format is wrong or doesn\'t exist');
        }

        $filename = 'channel_' . BaseController::get_channel_id().".".$ext;

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $name       = 'Focus-HD1.jpg';

        // Save logo extention
        $channel = Channel::find($channel_id);
        $channel->logo_ext = $ext;
        $channel->save();

        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->focus_hd = $name;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->focus_hd = $name;
            $data->save();
        }


        $form = array(
            'key' => 'logos/' . $filename,
            'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ',
            'acl' => 'public-read',
        );

        $form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },

                    {
                        "bucket": "aceplayout"
                    },
                    [
                        "starts-with",
                        "$key",
                        "logos/' . $filename . '"
                    ]
                ]
            }';

        $form['policy_encoded'] = base64_encode($form['policy']);
        $form['signature'] = base64_encode(hash_hmac(
                'sha1', $form['policy_encoded'],
                'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L',
                true
            )
        );

        BaseController::get_channel()->touch();
        BaseController::get_channel()->save();

        return Response::json([
            'policy_encoded' => $form['policy_encoded'],
            'signature' => $form['signature'],
            'ext' => $ext,
            'filename' => $form['key']
        ], 200);
    }

    public function send_amazon_video_logo() {
    	$ext = Input::get('ext');
    	$filename = Input::get('filename');
    
    	if ($ext == '' || !is_string($ext)) {
    
    		App::abort(403, 'Video Logo format is wrong or doesn\'t exist');
    	}
    	
    	$form = array(
    			'key' => $filename,
    			'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ',	//aceplayout
    			'acl' => 'public-read',
    	);
    
    	$form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },
    
                    {
                        "bucket": "aceplayout"
                    },
                    [
                        "starts-with",
                        "$key",
                        "logos/' . $filename . '"
                    ]
                ]
            }';
    
    	$form['policy_encoded'] = base64_encode($form['policy']);
    	$form['signature'] = base64_encode(hash_hmac(
    			'sha1', $form['policy_encoded'],
    			//'VneSpJvid3oY1pA17KQ9zRmqnpb6sudn0MkY49im',
    			'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L',
    			true
    			)
    			);
    
    	BaseController::get_channel()->touch();
    	BaseController::get_channel()->save();
    
    	return Response::json([
    			'policy_encoded' => $form['policy_encoded'],
    			'signature' => $form['signature'],
    			'filename' => $form['key']
    	], 200);
    }

	// Mobile-Web  image
	public function send_amazon_mobileweb_image() {
		$ext = Input::get('ext');
		$video_id = Input::get('video_id');

		if ($ext == '' || !is_string($ext)) {
			App::abort(403, 'Mobile-Web image format is wrong or doesn\'t exist');
		}

		$filename = 'channel_' . BaseController::get_channel_id().'_mobileweb_video_' . $video_id.'.jpg' ;

		$video = Video::find($video_id);
		$video->mobileweb_image_url = $filename;
		$video->save();

		$form = array(
			'key' => 'banners/' . $filename,
			'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ',
			'acl' => 'public-read',
		);
		$form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },

                    {
                        "bucket": "aceplayout"
                        		
                    },
                    [
                        "starts-with",
                        "$key",
                        "banners/' . $filename . '"
                    ]
                ]
            }';

		$form['policy_encoded'] = base64_encode($form['policy']);
		$form['signature'] = base64_encode(hash_hmac(
				'sha1', $form['policy_encoded'],
				'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L',
				true
			)
		);
		BaseController::get_channel()->touch();
		BaseController::get_channel()->save();

		return Response::json([
			'policy_encoded' => $form['policy_encoded'],
			'signature' => $form['signature'],
			'filename' => $form['key']
		], 200);
	}
	
	public function send_amazon_mobileweb_image_for_playlist() {
		$ext = Input::get('ext');
		$tvapp_playlist_id = Input::get('tvapp_playlist_id');

		if ($ext == '' || !is_string($ext)) {
			App::abort(403, 'Mobile-Web image format is wrong or doesn\'t exist');
		}

		$filename = 'channel_' . BaseController::get_channel_id().'_mobileweb_playlist_' . $tvapp_playlist_id.'.jpg' ;

		$tvapp_playlist_id = TvappPlaylist::find($tvapp_playlist_id);
		$tvapp_playlist_id->mobileweb_image_url = $filename;
		$tvapp_playlist_id->save();

		$form = array(
			'key' => 'banners/' . $filename,
			'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ',
			'acl' => 'public-read',
		);
		$form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },

                    {
                        "bucket": "aceplayout"
                        		
                    },
                    [
                        "starts-with",
                        "$key",
                        "banners/' . $filename . '"
                    ]
                ]
            }';

		$form['policy_encoded'] = base64_encode($form['policy']);
		$form['signature'] = base64_encode(hash_hmac(
				'sha1', $form['policy_encoded'],
				'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L',
				true
			)
		);
		BaseController::get_channel()->touch();
		BaseController::get_channel()->save();

		return Response::json([
			'policy_encoded' => $form['policy_encoded'],
			'signature' => $form['signature'],
			'filename' => $form['key']
		], 200);
	}


    // TV app image
	public function send_amazon_tvapp_image(){
		$ext = Input::get('ext');
		$video_id = Input::get('video_id');

		if ($ext == '' || !is_string($ext)) {
			App::abort(403, 'TV app image format is wrong or doesn\'t exist');
		}

		$filename = 'channel_' . BaseController::get_channel_id().'_'.'TVapps_vod_poster_' . $video_id.'.jpg' ;

		$video = Video::find($video_id);
		$video->tvapp_image_url = $filename;
		$video->save();

		$form = array(
			'key' => 'banners/' . $filename,
			'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ',
			'acl' => 'public-read',
		);
		$form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },

                    {
                        "bucket": "aceplayout"
                        		
                    },
                    [
                        "starts-with",
                        "$key",
                        "banners/' . $filename . '"
                    ]
                ]
            }';

		$form['policy_encoded'] = base64_encode($form['policy']);
		$form['signature'] = base64_encode(hash_hmac(
				'sha1', $form['policy_encoded'],
				'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L',
				true
			)
		);
		BaseController::get_channel()->touch();
		BaseController::get_channel()->save();

		return Response::json([
			'policy_encoded' => $form['policy_encoded'],
			'signature' => $form['signature'],
			'filename' => $form['key']
		], 200);
	}

	public function send_amazon_poster_image(){
		$ext = Input::get('ext');
		$video_id = Input::get('video_id');

		if ($ext == '' || !is_string($ext)) {

			App::abort(403, 'Poster format is wrong or doesn\'t exist');
		}

		// $filename = 'channel_' . BaseController::get_channel_id().'_'.'tvapp_playlist_' . $tvapp_playlist_id ;
		// $filename = 'channel_' . BaseController::get_channel_id().'_'.'tvapp_playlist_' . $tvapp_playlist_id.'.'.$ext ;
		$filename = 'channel_' . BaseController::get_channel_id().'_poster_video_' . $video_id.'.jpg' ;

		$video = Video::find($video_id);
		$video->custom_poster = $filename;
		$video->save();

		$form = array(
			'key' => 'banners/' . $filename,
			'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ', //aceplayout
			'acl' => 'public-read',
		);
		$form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },

                    {
                        "bucket": "aceplayout"
                        		
                    },
                    [
                        "starts-with",
                        "$key",
                        "banners/' . $filename . '"
                    ]
                ]
            }';

		$form['policy_encoded'] = base64_encode($form['policy']);
		$form['signature'] = base64_encode(hash_hmac(
				'sha1', $form['policy_encoded'],
				'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L',
				true
			)
		);

		BaseController::get_channel()->touch();
		BaseController::get_channel()->save();


		return Response::json([
			'policy_encoded' => $form['policy_encoded'],
			'signature' => $form['signature'],
			'filename' => $form['key']
		], 200);
	}

	public function send_amazon_playlist_banner() {
        $ext = Input::get('ext');
        $tvapp_playlist_id = Input::get('tvapp_playlist_id');
        
        if ($ext == '' || !is_string($ext)) {

            App::abort(403, 'Playlist Logo format is wrong or doesn\'t exist');
        }

        // $filename = 'channel_' . BaseController::get_channel_id().'_'.'tvapp_playlist_' . $tvapp_playlist_id ;
        // $filename = 'channel_' . BaseController::get_channel_id().'_'.'tvapp_playlist_' . $tvapp_playlist_id.'.'.$ext ;
        $filename = 'channel_' . BaseController::get_channel_id().'_'.'tvapp_playlist_' . $tvapp_playlist_id.'.jpg' ;

//		$playlist = Playlist::find($tvapp_playlist_id);
//		$playlist->featured_image_url = $filename;
//		$playlist->save();

        $form = array(
            'key' => 'banners/' . $filename,
            'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ', //aceplayout
            'acl' => 'public-read',
        );
        //"bucket": "aceplayout"
        $form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },

                    {
                        "bucket": "aceplayout"
                        		
                    },
                    [
                        "starts-with",
                        "$key",
                        "banners/' . $filename . '"
                    ]
                ]
            }';

        $form['policy_encoded'] = base64_encode($form['policy']);
        $form['signature'] = base64_encode(hash_hmac(
                'sha1', $form['policy_encoded'],
                'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L',
                true
            )
        );

        BaseController::get_channel()->touch();
        BaseController::get_channel()->save();


        return Response::json([
            'policy_encoded' => $form['policy_encoded'],
            'signature' => $form['signature'],
            'filename' => $form['key']
        ], 200);
    }
    
    public function send_amazon_playlist_logo() {
        $ext = Input::get('ext');
        $tvapp_playlist_id = Input::get('tvapp_playlist_id');
        
        if ($ext == '' || !is_string($ext)) {

            App::abort(403, 'Playlist Logo format is wrong or doesn\'t exist');
        }

        // $filename = 'channel_' . BaseController::get_channel_id().'_'.'tvapp_playlist_' . $tvapp_playlist_id ;
        // $filename = 'channel_' . BaseController::get_channel_id().'_'.'tvapp_playlist_' . $tvapp_playlist_id.'.'.$ext ;
        $filename = 'channel_' . BaseController::get_channel_id().'_'.'tvapp_playlist_' . $tvapp_playlist_id.'.jpg' ;
       
        $form = array(
            'key' => 'logos-poster/' . $filename,
            'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ', //aceplayout
            'acl' => 'public-read',
        );
        //"bucket": "aceplayout"
        $form['policy'] = '{
            "expiration": "2020-12-01T12:00:00.000Z",
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },

                    {
                        "bucket": "aceplayout"
                        		
                    },
                    [
                        "starts-with",
                        "$key",
                        "logos-poster/' . $filename . '"
                    ]
                ]
            }';

        $form['policy_encoded'] = base64_encode($form['policy']);
        $form['signature'] = base64_encode(hash_hmac(
                'sha1', $form['policy_encoded'],
                'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L',
                true
            )
        );

        BaseController::get_channel()->touch();
        BaseController::get_channel()->save();


        return Response::json([
            'policy_encoded' => $form['policy_encoded'],
            'signature' => $form['signature'],
            'filename' => $form['key']
        ], 200);
    }
    
    public function send_amazon()
    {

//        $s3 = AWS::get('s3');
//        $s3->putBucketPolicy(array(
//            'Bucket' => 'prolivestream',
//            'Policy' => '{
//              "Version":"2012-10-17",
//              "Statement":[{
//                "Sid":"AddPerm",
//                    "Effect":"Allow",
//                  "Principal": "*",
//                  "Action":["s3:GetObject"],
//                  "Resource":["arn:aws:s3:::prolivestream/*"
//                  ]
//                }
//              ]
//            },
//            { "expiration": "2007-12-01T12:00:00.000Z",
//                  "conditions": [
//                    {"bucket": "johnsmith"},
//                    ["starts-with", "$key", "user/eric/"],
//                    {"acl": "public-read"},
//                    {"success_action_redirect": "http://johnsmith.s3.amazonaws.com/successful_upload.html"},
//                    ["starts-with", "$Content-Type", "image/"],
//                    {"x-amz-meta-uuid": "14365123651274"},
//                    ["starts-with", "$x-amz-meta-tag", ""]
//                  ]
//            }
//            '
//        ));
//       $policy = $s3->getBucketPolicy(array('Bucket' => 'prolivestream'));
//       $signature = $s3->getSignature();
//        echo '<pre>';
//        var_dump($policy);
//        echo '</pre>';
//        echo '<pre>';
//        var_dump($signature);
//        echo '</pre>';
        $video_format = Input::get('video_format');

        $video_format = trim($video_format);

        if ($video_format == '' || !is_string($video_format)) {

            App::abort(403, 'Video format is wrong or doesn\'t exist');
        }

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
                "conditions": [
                    {
                        "acl": "' . $form['acl'] . '"
                    },

                    {
                        "bucket": "prolivestream"
                    },
                    [
                        "starts-with",
                        "$key",
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
//    public function addVideo()
//    {
//        $title = trim(Input::get('title'));
//        $description = trim(Input::get('description'));
//        $duration = trim(Input::get('duration'));

//        $channel_id = trim(Input::get('channel_id'));
//        $file_name = trim(Input::get('file_name'));


//        if (empty($title) || empty($description) || empty($duration) || empty($channel_id) || empty($file_name)) {
//            return Response::json([
//                'status' => false,
//                'message' => 'Wrong data'
//            ], 200);
// //            return View::make('error')->with(array('message' => 'Wrong data'));
//        }

//        $video = new Video;
//        $video->title = $title;
//        $video->description = $description;
//        $video->duration = $duration;
//        $video->status = 0;
//        $video->channel_id = $channel_id;
//        $video->file_name = $file_name;

//        if ($video->save()) {

//        }
//        //http://162.243.64.44/home/addVideo?title=title11&description=desc&duration=3000&channel_id=5&file_name=sdgjiodf25df
//    }


   /**
    * @return \Illuminate\Http\JsonResponse
    */
   public function add_ifame_videos()
   {
   	    $vnames = array(
//    	    array('One House Street feat. Bootsy Collins','Legendary hip hop TV show from the early \'90s','1HSBootsy_1443073478','mp4','jpg'),
// array('"Yup" by Dr. Wippit','"Yup" by Dr. Wippit','YupbyDrWippit_1441882505','mp4','jpg')   	    	
   	    );
   	    
   	    foreach($vnames as $vn){
   	    	
   	    	$videos = Video::where('file_name', '=', $vn[1])->get();
   	    	
   	    	if(count($videos)<1) {
   	    		
	   	    	$video = new Video;
	   	    	$video->title = $vn[0];
	   	    	$video->description = $vn[1];
	   	    	$video->thumbnail_name = 'https://s3.amazonaws.com/ifame/'.$vn[2].'_1.'.$vn[4];
	   	    	//$video->duration = $duration;
	   	    	$video->channel_id = '37';
	   	    	$video->file_name = $vn[2];
	   	    	$video->video_format = $vn[3];
	   	    	
	   	    	$video->save();
   	    	}
   	    	
   	    }
   	    
   	    return Response::json([
   	    		'status' => count($videos)
   	    ], 200);
   	    
   	}


// WORKING PANDASTREAM, TEMP COMMENTED
    public function authorize_upload()
    {
        $panda_config = array(
            'api_host' => 'api.pandastream.com',
            //'cloud_id' => '591912193190d9ccdd618f8596a8a032', //aceplayout
            //'cloud_id' => '436176323a425287c22a84a50f49e908', //ACE
        	'cloud_id' => '8d68d284045f40d9d6358740d1f47dae', //1stud
        	//telestream
            'access_key' => 'aa4ab4b59f22c584b646', //telestream access key
            'secret_key' => '1439b809d987a01c68d1', //telestream secret key
        	'api_port' => 80,
        );
                
        $panda = new Panda($panda_config);

        $payload = json_decode($_POST['payload']);

        $filename = $payload->{'filename'};
        $filesize = $payload->{'filesize'};


        $upload = json_decode($panda->post("/videos/upload.json",
            array('file_name' => $filename, 'file_size' => $filesize, 'profiles' => 'h264.1, h264.2')));

        $response = array('upload_url' => $upload->location);

        header('Content-Type: application/json');

        return Response::json($response, 200);
    }

    public function get_post_proc()
    {
        return $this->post_proc();
    }

    public function post_proc()
    {

//     	$row = DB::table('video_back')->get();
//     	foreach($row as $r){
//     		//Log::info($r2->id);
//     		$ob = [];
//     		$r->id;
//     		$r->channel_id;
//     		$r->thumbnail_name;
//     		$r->file_name;
    		
//     		//temp transfer
//     		$status = Video::where('id', '=', $r->id)->where('channel_id', '=', $r->channel_id)->update(array(
//     				'thumbnail_name'=> 'https://onestudio.imgix.net/'.$r->file_name.'_1.jpg'.'?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60',
//     				'file_name' => $r->file_name
//     		));
//     	}
//     	exit;
    	
        $panda_config = array(
            'api_host' => 'api.pandastream.com',
            //'cloud_id' => '591912193190d9ccdd618f8596a8a032', //aceplayout
            //'cloud_id' => '436176323a425287c22a84a50f49e908', //ACE
        	'cloud_id' => '8d68d284045f40d9d6358740d1f47dae', //1stud Factory ID: 8d68d284045f40d9d6358740d1f47dae
        		
            'access_key' => 'aa4ab4b59f22c584b646', //telestream access key
            'secret_key' => '1439b809d987a01c68d1', //telestream secret key
        );
        
        

        $panda = new Panda($panda_config);

        $notifications = json_decode($panda->get("/notifications.json"));
        //print_r($notifications);exit;
       
        foreach($notifications as $notify => $innerArray){
            if(strcmp($notify,'events')==0){
            	
                foreach($innerArray as $k => $v) {
                    if(strcmp($k,'video_encoded')==0){
                        if(true){ //$v - what was this variable (instead of current true)???
                            $panda2 = new Panda($panda_config);
                            $res = $panda2->get('encodings.json');
                            $json_encodings = json_decode($res, true);
                            //print_r($json_encodings);
                            //foreach($json_encodings as $k1 => $v1) {
                            for($i=0; $i<count($json_encodings); $i++){
	                            $ir = $json_encodings[$i];
	                            
	                            //$ir2 = $json_encodings[7];
	                            //print_r($ir['job_id']);
	                            //HD
	                            if(strcmp($ir['profile_name'],'h264.1')==0){
	                            	
	                            	
	                            	$video_id = $ir['video_id']; //original video id
	                            	
	                            	//file_name field has job_id value initially, then set to encoded hd id
		                            //$videos = Video::where('file_name', '=', $video_id)->get();
		                            $videos = Video::where('job_id', '=', $video_id)->where('hd_height', '=', '0')->get();
		                            foreach ($videos as $video) {

		                            	$hd_file_name = $ir['id']; //here we get encoded mp4 video filename
		                            	$encode_status = $ir['status'];
		                            	
		                            	$file_size = $ir['file_size'];
		                            	$video_bitrate = $ir['video_bitrate'];
		                            	$created_at = $ir['created_at'];
		                            	$mime_type = $ir['mime_type'];
		                            	$duration = $ir['duration'];
		                            	$height = $ir['height'];
		                            	$width = $ir['width'];
		                            	$audio_codec = $ir['audio_codec'];
		                            	$video_codec = $ir['video_codec'];
		                            	 
		                            	$hd_width = $ir['width'];
		                            	$hd_height = $ir['height'];
		                            	$hd_file_size = $ir['file_size'];
		                            	$hd_video_bitrate = $ir['video_bitrate'];
		                            	$hd_audio_codec = $ir['audio_codec'];
		                            	$hd_video_codec = $ir['video_codec'];
		                            	$hd_mime_type = $ir['mime_type'];
		                            		
		                            	if(strcmp($encode_status,'success'==0))$encode_status=2;
		                            	else $encode_status = 0;
		                            
		                            	$size = $width.'X'.$height;
		                            	$date = substr($created_at,0,10);
		                            	$file_size = substr($file_size,0,strlen($file_size)-3);
		                            	print_r($ir);
		                            	//$status = Video::where('file_name', '=', $video_id)->update(array(
		                            	$status = Video::where('job_id', '=', $video_id)->update(array(
		                            			'thumbnail_name'=> 'https://onestudio.imgix.net/'.$hd_file_name.'_1.jpg'.'?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60',
		                            			//'thumbnail_name'=> 'https://s3.amazonaws.com/aceplayout/'.$mp4id.'_1.jpg',
		                            			//'thumbnail_name'=> 'https://s3-us-west-2.amazonaws.com/prolivestream/videos/'.$mp4id.'_1.jpg',
		                            			'file_name' => $hd_file_name,
		                            			'video_format' => 'mp4',
		                            			'encode_status' => $encode_status,
		                            			'duration' => $duration/1000,
		                            			'storage' => $date.' - '.($file_size).'kb, '.$size.', '.$mime_type.', '.$video_codec,
		                            			'start_time' => '2015-06-28 21:35:41',
		                            
		                            			'hd_width' => $hd_width,
		                            			'hd_height' => $hd_height,
		                            			'hd_file_size' => $hd_file_size,
		                            			'hd_video_bitrate' => $hd_video_bitrate,
		                            			'hd_audio_codec' => $hd_audio_codec,
		                            			'hd_video_codec' => $hd_video_codec,
		                            			'hd_mime_type' => $hd_mime_type,
		                            
		                            	));
		                            	//break 4;
		                            	$channel_id = $video->channel_id;
		                            	$this->ftp_S3_to_DEVO_and_schedule($hd_file_name, $channel_id);
		                            }
		                            
	                            }
	                            
	                            if(strcmp($ir['profile_name'],'h264.2')==0){
	                            	//print_r($ir['video_id']."\n");
	                            	//print_r($ir['video_id']."\n");
	                            	//SD
	                            	$video_id = $ir['video_id'];
	                            	
	                            	//$videos = Video::where('job_id', '=', $video_id)->where('sd_file_name', '=', '')->get();
	                            	$videos = Video::where('job_id', '=', $video_id)->where('sd_height', '=', '0')->get();
	                            	foreach ($videos as $video) {
	                            		
	                            		$sd_file_name = $ir['id'];
	                            		$sd_duration = $ir['duration'];
	                            		 
	                            		$sd_width = $ir['width'];
	                            		$sd_height = $ir['height'];
	                            		$sd_file_size = $ir['file_size'];
	                            		$sd_video_bitrate = $ir['video_bitrate'];
	                            		$sd_audio_codec = $ir['audio_codec'];
	                            		$sd_video_codec = $ir['video_codec'];
	                            		$sd_mime_type = $ir['mime_type'];
	                            		
	                            		
	                            		//print_r($ir['video_id']."\n");
	                            		
	                            		
	                            		$status = Video::where('job_id', '=', $video_id)->update(array(
	                            				
	                            				'sd_file_name' => $sd_file_name,
	                            				'sd_duration' => $sd_duration,
	                            	
	                            				'sd_width' => $sd_width,
	                            				'sd_height' => $sd_height,
	                            				'sd_file_size' => $sd_file_size,
	                            				'sd_video_bitrate' => $sd_video_bitrate,
	                            				'sd_audio_codec' => $sd_audio_codec,
	                            				'sd_video_codec' => $sd_video_codec,
	                            				'sd_mime_type' => $sd_mime_type,
	                            	
	                            		));
	                            		
	                            		//no SD on DVEO needed !!!
	                            		//break 4;
	                            		//$channel_id = $video->channel_id;
	                            		//$this->ftp_S3_to_DEVO_and_schedule($sd_file_name, $channel_id);
	                            	 }
	                            }

                            }//for
                            
                        }
                    }
                }
            }
        }
        

        
        
        
        
        
                
        return Response::json([
            'status' => 'OK'
        ], 200);

    }

    //to be moved to app/models/DEVO.php
    public function ftp_S3_to_DEVO_and_schedule($video_name, $channel_id)
    {
//        $ip_address = '162.247.57.18';
//        $port = 25599;
//        $username = 'apiuser';
//        $password = 'Hn7P67583N9m5sS';

        // Creating a new DVEO instance
        //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');

    	
    	//        $ip_address = '198.241.44.164';
    	//        $port = 25599;
    	//        $username = 'ftpuser';
    	//        $password = 'Hn7P67583N9m5sS';
        
        $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');

        $dveo->upload_video($channel_id, 'https://s3.amazonaws.com/aceplayout/'.$video_name.'.mp4', $video_name);
        //$dveo->upload_video($channel_id, 'https://s3-us-west-2.amazonaws.com/prolivestream/videos/'.$video_name.'.mp4', $video_name);
        //https://s3-us-west-2.amazonaws.com/prolivestream/videos/e604fe98_42b5_232a_df27_9cb02640d88f.mp4

        ////$dveo->upload_video(BaseController::get_channel_id(), 'http://s3.amazonaws.com/aceplayout/'.$video_name, $video_name);
        ////var_dump($dveo->get_playlist(1));
        ////$dveo->set_playlist(BaseController::get_channel_id(), [
        ////    1531080100 => "$video_name"
        ////]);

        //>>$dveo->schedule_video($channel_id, time(), $video_name . '.mp4');

    }

    //s3 to dveo restoring
    public function s3_to_dveo()
    {
        $video_names = array(


    //////////////
            //video////ze repe////pload////
    ///     '36e1dc65_455d_85d8_a367_0706f921febf.mp4',

    ////////////////////

'd63c096f_9753_18da_fe57_9135f40fd979',
'7026292f_e90d_c966_0a84_b68aa8eafeea'

        );
        //set_time_limit(36000);
        $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');
        $dveo->upload_video_array('group3/tv',$video_names);
        
        
//        $count=0;
//        foreach ($video_names as &$video_name) {
//            $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');
//            //$dveo->upload_videos_by_group('group1', 'https://prolivestream.s3.amazonaws.com/videos/' . $video_name . '.mp4', $video_name);
//            $dveo->upload_videos_by_group('group1', 'https://s3-us-west-2.amazonaws.com/prolivestream/videos/' . $video_name . '.mp4', $video_name);
//            if($count >3) break;
//            $count++;
//        }
//        unset($video_name); //unset reference
    }

    //    private function recPandaComplete($panda, $limit)
//    {
//        $response = $panda->get('encodings.json');//json_decode($panda->post('upload_response'));
//        $json_encodings = json_decode($response, true);
//        foreach($json_encodings as $row => $innerArray){
//            foreach($innerArray as $key => $value){
//                $videoNameMp4='';
//                if(strcmp($key,"id")==0){
//                    //echo json_encode(array('id' => $value));
//                    $videoNameMp4 = $value.'.mp4';
//                }
//
//                if(strcmp($key,"status")==0){
//                    if(strcmp($value,"success")!=0 && $limit > 0){
//                        sleep(1);
//                        --$limit;
//                        $response = $this->recPandaComplete($panda, $limit);
//                    }else{
//                        //transcoded video set on s3 successfully
//                        //ftp video from s3 to DVEO
//                        $file = "channel_006_.m3u8";
//                        //$this->ftp_S3_to_DEVO($videoNameMp4);
//                        return $response;
//                    }
//                }
//
//
//            }
//        }
//        return $response;
//    }

    public function GetInfoFrom_Vimeo($url)
    {
        // See more at: https://arjunphp.com/how-to-get-thumbnail-of-vimeo-video-using-the-video-id/#sthash.ZJSYSNuy.dpuf
        $parts = explode('.', basename($url));
        Log::info("vimeo ID = ".$parts[0]);

        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$parts[0].".php"));
        if (count($hash) > 0)
        {
            return $hash[0];
/*
            vimeo Info = Array
            (
                [id] => 195904053
                [title] => Road Through The Night Sky - Milky Way Timelapse 4K
                [description] => 
                [url] => https://vimeo.com/195904053
                [upload_date] => 2016-12-15 22:42:09
                [thumbnail_small] => http://i.vimeocdn.com/video/608240763_100x75.jpg
                [thumbnail_medium] => http://i.vimeocdn.com/video/608240763_200x150.jpg
                [thumbnail_large] => http://i.vimeocdn.com/video/608240763_640.jpg
                [user_id] => 60355957
                [user_name] => OneStudio
                [user_url] => https://vimeo.com/user60355957
                [user_portrait_small] => https://secure.gravatar.com/avatar/7a7d594adf4e7ee545ab5c971a172902?d=http%3A%2F%2Fi.vimeocdn.com%2Fportrait%2Fdefaults-green_30x30.png&s=30
                [user_portrait_medium] => https://secure.gravatar.com/avatar/7a7d594adf4e7ee545ab5c971a172902?d=http%3A%2F%2Fi.vimeocdn.com%2Fportrait%2Fdefaults-green_75x75.png&s=75
                [user_portrait_large] => https://secure.gravatar.com/avatar/7a7d594adf4e7ee545ab5c971a172902?d=http%3A%2F%2Fi.vimeocdn.com%2Fportrait%2Fdefaults-green_100x100.png&s=100
                [user_portrait_huge] => https://secure.gravatar.com/avatar/7a7d594adf4e7ee545ab5c971a172902?d=http%3A%2F%2Fi.vimeocdn.com%2Fportrait%2Fdefaults-green_300x300.png&s=300
                [stats_number_of_likes] => 0
                [stats_number_of_plays] => 4
                [stats_number_of_comments] => 0
                [duration] => 237
                [width] => 1280
                [height] => 720
                [tags] => 
                [embed_privacy] => anywhere
            )
*/
        }
        return array();
    }

    public function add_videos()
    {
        $title = trim(Input::get('title'));
        $description = trim(Input::get('description'));
        $collections = Input::get('collections');
        $file_name = trim(Input::get('file_name'));
        $video_format = trim(Input::get('video_format'));
        $encoded_video_id = trim(Input::get('encoded_video_id'));
        $source = trim(Input::get('source'));

        if(empty($title) || empty($file_name) || empty($video_format)) {
            return Response::json([
                'status' => false,
                'message' => Error::returnError(Error::ERROR_SOME_DATA_IS_EMPTY)
            ]);
        }
        if($source == 'vimeo' && strpos($file_name, "https://player.vimeo.com/external/") !== 0){
            return Response::json([
                'status' => false,
                'message' => 'Wrong Vimeo URL. The link is not acceptable'
            ]);
        }
        Log::info(print_r($_REQUEST, true));

        $video = array(
            'file_name' => $file_name,
            'video_format' => $video_format
        );

//        //Zencoder not used now, Pandastream used
//        $job = $this->video_encode($video);
//
//        if (!$job['job_status']) {
//
//            // Add failed jobs, and unset fild data
//            return Response::json([
//                'status' => false,
//                'message' => Error::returnError(Error::ERROR_FAILED_JOB)
//            ]);
//        }
        if ($source != 'internal')
        {
            $video = new Video;
            $video->title = $title;
            $video->description = $description;
            $video->file_name = $file_name;
            $video->job_id = '';
            $video->video_format = $video_format;
            $video->channel_id = BaseController::get_channel_id();
            $video->storage = '';
            $video->source = $source;
            $video->thumbnail_name = '';
            if ($source == 'vimeo') 
            {
                $vimeo = $this->GetInfoFrom_Vimeo($file_name);
                if (count($vimeo) > 4)
                {
                    $video->title = $vimeo['title'];
                    $video->description = $vimeo['description'];
                    $video->thumbnail_name = $vimeo['thumbnail_large'];
                    $video->duration = $vimeo['duration'];
                    $video->hd_width = $vimeo['width'];
                    $video->hd_height = $vimeo['height'];
                    $video->job_id = $vimeo['id'];
                }
            }
            $video->save();
        }
        else
        {

            $videos = Video::where('storage', '=', $encoded_video_id)->get();
            foreach ($videos as $video) {
                if (!empty($collections)) {
                    foreach ($collections as $collection) {
                        $video_in_collections = new Videos_in_collections;
                        $video_in_collections->video_id = $video->id;
                        $video_in_collections->collection_id = $collection;
                        $video_in_collections->save();
                    }
                }
            }

            $status = Video::where('storage', '=', $encoded_video_id)->update(array(
                'title'=> $title,
                'description' => $description,
                'storage' => ''
            ));
        }
        return Response::json([
            'status' => true,
            'videoForm' => $file_name.$encoded_video_id
        ]);
    }

    /**
     * Call the video encoder function and change in database encoding status
     *
     * @param $video - array
     * @return array|bool
     */
    public function video_encode($video)
    {
        $job = Video::encode_video(
            $video['file_name'],
            $video['video_format']
        );

        if (!$job) {
            return array(
                'failed_job' => array(
                    'video_name' => $video['video_name'],
                    'video_format' => $video['video_format']
                ),
                'job_status' => false
            );
        } else {

            return array(
                'job_id' => $job['job_id'],
                'output_id' => $job['output_id'],
                'job_status' => true
            );
        }
    }

    /**
     *
     * @return json
     */
    public function notification_from_zencoder()
    {
        if (!Request::isJson()) {
            return;
        }

        $json_arr_obj = Input::json();

        $arr = [];
        foreach ($json_arr_obj as $json_arr) {

            $arr[] = $json_arr;

        }

        $json_arr = $json_arr_obj;

        if ($arr[2]['state'] == 'finished') {
            $encoding_status = 2;

            if($arr[2]['label'] == 'amazons3') {
//                $this->get_thumbnail(
//                    $arr[2]['url'],
//                    "/var/www/public/videos_thumbnails/".str_random(32)."jpg",
//                    $arr[0]['id']
//                );
            }

        } else {
            $encoding_status = 0;
        }

        $second = ceil($arr[2]['duration_in_ms'] / 1000);

        $status = Video::where('job_id', '=', $arr[0]['id'])->update(array(

            'thumbnail_name'=> $arr[2]['thumbnails'][0]['images'][0]['url'],
            'encode_status' => $encoding_status,
            'duration' => $second
        ));

        return Response::json([
            'status' => $status
        ], 200);


    }

    public function get_thumbnail($s3_link, $path, $id)
    {

        $video_tools = new VideoTools('prolivestream');

        // Ô¾Õ¡Õ¶Õ¸Ö‚Ö�Õ¸Ö‚Õ´Õ¶Õ¥Ö€Õ« Õ°Õ¡Õ´Õ¡Ö€ Õ¡ Õ§Õ½ Õ¯Õ¬Õ¡Õ½Õ¨
        $notification = new Notification();

        // HTTP-Õ¸Õ¾ Õ®Õ¡Õ¶Õ¸Ö‚Ö�Õ« Õ¶Õ¯Õ¡Ö€Õ¨ Õ½Õ¡Ö€Ö„Õ¥Õ¬Õ¸Ö‚ Õ´Õ¡Õ½Õ«Õ¶
        $notification->add(Notification::HTTP, [
            'endpoint' => asset('/') . '/get_thumbnail_notification?id='.$id,
        ]);

        // WebSocketÖŠÕ¸Õ¾ Õ¸Ö‚Õ²Õ¡Ö€Õ¯Õ« Õ®Õ¡Õ¶Õ¸Ö‚Ö�Õ¸Ö‚Õ´Õ¨
//        $notification->add(Notification::WebSocket, [
//            'user_id' => 0,
//            'key' => 'Õ«Õ¶Õ¹ÖŠÕ¸Ö€ key, Õ¸Ö€Õ¨ Õ¯Õ¸Ö‚Õ²Õ¡Ö€Õ¯Õ¾Õ« WebSocket-Õ¸Õ¾ user-Õ«Õ¶',
//        ]);

        $video_tools->create_thumbnail(
            $s3_link,
            $path,
            3,
            $notification
        );
    }

    public function get_thumbnail_notification(){

        $path = trim(Input::get('path'));
        $id = trim(Input::get('id'));

        if(empty($path) || empty($id)) {
            return;
        }

        $path_parts = pathinfo($path);

        $video = Video::where('job_id', '=', $id);

        $video->thumbnail_name = $path_parts['filename'];

        $video->save();


    }

    //CODE REMOVED FROM Video.php NOT USED NOW

    //    public function toArray()
//    {
//        $video = parent::toArray();
//        $thumbnails = Thumbnails::where('video_id', '=', $this->id)->get()->toArray();
//        $video['thumbnails'] = $thumbnails;
//        return $video;
//    }

    /**
     * Encoding video whit Zencoder
     *
     * @param $video_name - this is  a input and output video name
     * @param $format - this is the input video format
     * @return bool
     */

    public static function encode_video($video_name, $format)
    {
        $videoFormat = Channel::find(BaseController::get_channel_id())->format;
        if($videoFormat == 'sd') {
            $videoSize = '720x404';
            $videoWidth = '720';
            $videoHeight = '404';
            $videoBitrate = '2000';
        } else if($videoFormat == 'hd') {
            $videoSize = '1280x720';
            $videoWidth = '1280';
            $videoHeight = '720';
            $videoBitrate = '2600';
        }

        require_once "../vendor/zencoder/zencoder-php/Services/Zencoder.php";
        // Create a new Zencoder instance
        // 796a08bd860ce9365020a592bcf44af4

        // 613b9399696a07afef566c64dbd26552
        $zencoder = new Services_Zencoder('a5ae9e0b95c11364c40419366fa6520e');

        // FTP access for FTP upload
        $output_url = "ftp://ftpuser:Hn7P67583N9m5sS@" . BaseController::get_dveo_ip() . "/group3/tv/";

        $output_url_2 = "s3://prolivestream/videos/";

        try {


            $output = [];
            $output[] = [
                'url' => $output_url . $video_name . ".mp4",
                'base_url' => $output_url,
                'filename' => "{$video_name}.mp4",
                'format' => 'mp4',
                'video_codec' => 'h264',
                'audio_codec' => 'aac',
                'size' => $videoSize,
                'width' => $videoWidth,
                'height' => $videoHeight,
                'video_bitrate' => $videoBitrate,
                'label' => $video_name,
                'public' => false,
                'notifications' => [
                    'url' => asset('/') . '/channel_' . BaseController::get_channel_id() . '/get_from_zencoder',
                    'format' => 'json',
                ]
            ];

            $output[] = [
                'url' => $output_url_2 . $video_name . ".mp4",
                'base_url' => "s3://prolivestream/",
                'filename' => "{$video_name}.mp4",
                'format' => 'mp4',
                'video_codec' => 'h264',
                'audio_codec' => 'aac',
                'size' => $videoSize,
                'width' => $videoWidth,
                'height' => $videoHeight,
                'video_bitrate' => $videoBitrate,
                'label' => 'amazons3',
                'public' => false,
                'notifications' => [
                    'url' => asset('/') . '/channel_' . BaseController::get_channel_id() . '/get_from_zencoder',
                    'format' => 'json',
                ],

                'thumbnails' => [
                    "format" => "jpg",
                    "number" => 1,
                    "aspect_mode" => "preserve",
                    "base_url" => "s3://prolivestream/thumbnails",
                    "filename" => $video_name,
                    "height"=> 251,
                    "public" => 1
                ]
            ];


            // Create a new job
            $encoding_job = $zencoder->jobs->create([
//                    'input' => "s3://charter.xtech/{$video->guid}.mp4",
                'input' => "https://prolivestream.s3-us-west-2.amazonaws.com/videos/{$video_name}.{$format}",
                'region' => 'us',
                'download_connections' => 5,
                'private' => false,
//                    'mock' => true,
                'output' => $output,
            ]);

            return array(
                'job_status' => true,
                'job_id' => $encoding_job->id,
                'output_id' => $encoding_job->outputs[$video_name]->id
            );
        } catch (Services_Zencoder_Exception $e) {

            // Write the error in laravel.log file
            foreach ($e->getErrors() as $error) {
                Log::error("Zencoder: {$error}");
            }

            // function return false when get the error
            return array(
                'wrong_data' => "wrong data",
//                     'failed_jobs' => $failed_jobs,
//                     'status' =>
            );
        }
    }
    //END OF CODE REMOVED FROM Video.php
}

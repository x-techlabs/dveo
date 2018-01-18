<?php
	use \App\Helpers\Playlists\TvappPlaylistHelper as TvHelper;
	class ImagesController extends BaseController {


		public function index()
		{
			if(!Auth::user()->is(User::USER_MANAGE_MEDIA) || !in_array(BaseController::get_channel_id(), Auth::user()->checkChannelIds())) 
			{
            	return App::abort(404);
        	}
			if( Auth::user()->playout_access == 1){
				return App::abort(404);
			}
        	$channel_id = BaseController::get_channel_id();
        	$images = Images::where('channel_id', $channel_id)->get();
        	$collections = Image_folders::all();
			
			// Get slides
        	$playlists = self::get_slides();

			foreach($playlists as $playlist) {
	            $video_ids = Image_in_slide::where('slide_id', '=', $playlist->id)->get();

	            foreach($video_ids as $video_id) {
	                $video = Images::find($video_id['image_id']);

	                if(isset($video['file_name'])) {
	                    $playlist->video_thumb = 'https://s3.amazonaws.com/1stud-images/'.$video['file_name'];
	                    break;
	                } else {
	                    $playlist->video_thumb = 'http://speakingagainstabuse.com/wp-content/themes/AiwazMag/images/no-img.png';
	                }
	            }
	        }

	        $this->data['playlists'] = $playlists;

        	$this->data['images'] = $images;
        	$this->data['collections'] = $collections;
        	$this->data['channel_id'] = $channel_id;
			return $this->render('images/index');
		}

	    public static function get_slides() {
	        $playlists = Image_slide::where("channel_id", "=", BaseController::get_channel_id())->orderBy('id', 'desc')->get();
	        return Time::change_to_human_data_in_array($playlists);
	    }

		public function upload_image_get()
		{
			return $this->render('images/upload_image');
		}

		public function createImageFromS3()
	    {
	        $path = Input::get('uuid');
	        $fullpath = Input::get('key');
	        $title = Input::get('name');
	        $ext = pathinfo($fullpath, PATHINFO_EXTENSION);
	        $image = new Images;
	        $image->title = $title;
            $image->file_name = $fullpath;
	        $image->image_format = $ext;
	        $image->channel_id = BaseController::get_channel_id();
	        $image->storage = '';
	        $image->source = "internal";
	        $image->thumbnail_name = '/images/'.$fullpath;
	        $commandString = 'ffmpeg -ss 0:03 -i https://s3.amazonaws.com/1stud-images/'.$path.'.jpg -vframes 1 /var/www/1stud.io/public_html/1stud/public/images/'.$fullpath.' 2>&1';
	        exec($commandString, $output);

	        $timeString="00:00:00";
	        $image->save();
	        return $path . "   ". $title . "||".$timeString."||";

	    }

        public function start_transcode_image()
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
				'title' => 'JPG H.264',
				'aspectMode' => '16:9',
				'priority' => 1,
				'timeCode' => true,
				'extname' => 'jpg');
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
				'title' => 'JPG H.264 Basic',
				'aspectMode' => '16:9',
				'priority' => 2,
				'timeCode' => true,
				'extname' => 'jpg');
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
				'title' => 'JPG H.264 Auto',
				'aspectMode' => '16:9',
				'priority' => 3,
				'timeCode' => true,
				'extname' => 'jpg');

			$files = explode('|', Input::get('name'));

	        foreach($files as $f)
	        {
	            $fullPath = storage_path('images/'.$f);
	            $command = escapeshellcmd("mediainfo -full $fullPath");
	            $out = shell_exec($command." 2>&1");
	            $minfo = explode("\n", $out);
	            $duration = $this->GetMetadataFrom($minfo, 'General', 'Duration') / 1000;
	            Log::info("duration = $duration");
	            
	            $pid1 = $this->ProgressiveTranscode($hd_profile_1, $fullPath);
	            $pid2 = $this->ProgressiveTranscode($sd_profile_1, $fullPath);
	            $pid3 = $this->ProgressiveTranscode($sd_profile_2, $fullPath);

	            $outResult[] = $pid1[0].'|'.$pid2[0].'|'.$pid3[0];
	            $outResult[] = $pid1[1].'|'.$pid2[1].'|'.$pid3[1];
	            $outResult[] = $pid1[2].'|'.$pid2[2].'|'.$pid3[2];
	            $outResult[] = $duration;
	        }
	        print implode('^', $outResult); 
	    }
        public function ProgressiveTranscode($profile, $sourcefile)
	    {
	        $pn = str_replace(' ', '_', $profile['name']);
	        $ext = '.'.pathinfo($sourcefile, PATHINFO_EXTENSION); 
	        $destinationfile = str_replace($ext, '_'.$pn.$ext, $sourcefile);
	        $logfile = str_replace($ext, '_'.$pn.'.pbr', $sourcefile);

	        $cmd = "ffmpeg -i $sourcefile -c:v libx264 -c:a aac ";
	        $cmd .= " -r ".$profile['fps'];
	        $cmd .= " -b:v ".$profile['videoBitrate']."k";
	        $cmd .= " -b:a ".$profile['audioBitrate']."k";
	        $cmd .= " -ar ".$profile['audioSampleRate'];
	        $cmd .= " -vf \"scale=w=".$profile['width'].":h=".$profile['height'].",setdar=dar=".str_replace(':', '/',$profile['aspectMode'])."\" ";
	        $cmd .= " -progress $logfile ";
	        $cmd .= "$destinationfile";

	        Log::info($cmd);
	        if (file_exists($destinationfile)) unlink($destinationfile); 
	        if (file_exists($logfile)) unlink($logfile); 

	        $command = 'nohup '.$cmd.' > /dev/null 2>&1 & echo $!';
	        exec($command ,$op);
	        return array($op[0], basename($destinationfile), basename($logfile));
	    }

    	public function pushmediaobject_img()
		{
	        $out = '';

	        if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['tmp_name'] != '') 
	        {
	            $fname = 'channel_'.BaseController::get_channel_id().'_'.str_replace(' ', '_', $_FILES['uploadedFile']['name']);
	            $fname = str_replace('+','_',$fname);

	            $fullPath = storage_path('images/'.$fname);
	            Log::info($_FILES['uploadedFile']['tmp_name'].' => '.$fullPath);
	            move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $fullPath);
	            $out = basename($fullPath);
	        }
	        print 'file uploaded|'.$out;
	        exit();
		}

		public function istranscodeprocesscomplete_img()
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

	        for ( $i = 0 ; $i < count($progressFiles) ; $i++)
	        {
	            $fullPath = storage_path('videos/'.$progressFiles[$i]);
	            unlink($fullPath);
	        }


	        $out = array();
			$files = explode('|', $fileUploadData[1]);

	        $files[] = $this->createThumbnail($files[0]);

	        foreach($files as $f)
	        {
	            $out[] = '0|'.$f.'||0|0||';
	        }
	        print '1^';
	        print '0~'.implode('~', $out);
	    }

    	public function createThumbnail($f)
	    {
	        $fullPath = storage_path('images/'.$f);
	        $p = strrpos($fullPath, '.');
	        $jpg = substr($fullPath, 0, $p).'.jpg';
	        $jpg = str_replace('_HD_Profile', '_HD_Profile_1', $jpg);
	        if (file_exists($jpg)) unlink($jpg); 

	        $cmd = "ffmpeg -i $fullPath -ss 00:00:01.000 -vframes 1 $jpg";
	        exec($cmd);
	        return str_replace( storage_path('videos/'), '', $jpg);
	    }

		public function transcodeProgress($progressFile)
	    {
	        $lines = file( storage_path('images/'.$progressFile) );
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

        public function uploadfilestoawsserver_step0($info, $timeStamp)
	    {
	        $cno = 'channel_'.BaseController::get_channel_id();
	        $info[2] = str_replace($cno.'_', $cno.'/'.$timeStamp.'_', $info[1]);

	        $s3 = S3Client::factory( array('key' => 'AKIAIMCQJSKDT4QOHJVA', 'secret' => 'sVaocQvafINQ85/j7kQ/nNV2yBOT0H4aA/U0eoQ6'));
	        $result = $s3->createMultipartUpload(array(
	            'Bucket'       => "1stud-images",
	            'Key'          => $info[2],
	            'StorageClass' => 'STANDARD',
	            'ACL'          => 'public-read',
	        ));
	        $info[3] = $result['UploadId'];
	        $info[0] = 1;
	        return $info; 
	    }

	    public function uploadfilestoawsserver_step1($info)
	    {
	        $offset = $info[4] * (1024 * 1024 * 5);   
	        $fullPath = storage_path('images/'.$info[1]);
	        $fsize = filesize($fullPath);

	        if ($offset >= $fsize) { $info[0] = 2;  return $info; }

	        $file = fopen($fullPath, 'r');
	        fseek($file, $offset, SEEK_SET);  

	        $s3 = S3Client::factory( array('key' => 'AKIAIMCQJSKDT4QOHJVA', 'secret' => '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'));
	        try {    
	            $result = $s3->uploadPart(array(
	                'Bucket'     => "1stud-images",
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
	                'Bucket'     => "1stud-images",
	                'Key'        => $info[2],
	                'UploadId'   => $info[3]
	            ));
	        }
	        return $info;
	    }

	    public function uploadfilestoawsserver_step2($info)
	    {
	        $parts = array();
	        $tags = explode('^', $info[5]);
	        foreach($tags as $k => $t) 
	            $parts[] = array('PartNumber' => $k+1, 'ETag' => $t);


	        $s3 = S3Client::factory( array('key' => 'AKIAIMCQJSKDT4QOHJVA', 'secret' => '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'));
	        $result = $s3->completeMultipartUpload(array(
	            'Bucket'   => "1stud-images",
	            'Key'      => $info[2],
	            'UploadId' => $info[3],
	            'Parts'    => $parts
	        ));
	        $info[0] = 4;
	        $info[] = $result['Location'];

	        return $info;
	    }

	    public function uploadimagestoawsserver()
	    {
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
	        }

	        if ($completeCount == count($filesToUpload))
	        {
	            $out = array();
	            for ($i=1 ; $i < count($filesToUpload)-1 ; $i++)
	            {
	                $info = explode('|', $filesToUpload[$i]);
	                $out[] = $this->getmetadata($info);

	                $fullPath = storage_path('images/'.$info[1]);
	                unlink($fullPath);

	            }

	            $info = explode('|', end($filesToUpload));
	            $out[] = $info[2];

	            $fullPath = storage_path('images/'.$info[1]);
	            unlink($fullPath);

	            print '1~'.implode('|', $out);
	            exit();
	        }
			print implode('~', $filesToUpload);
	    }

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
	        $fullPath = storage_path('images/'.$info[1]);
	        $command = escapeshellcmd("mediainfo -full $fullPath");
	        $out = shell_exec($command." 2>&1");
	        $minfo = explode("\n", $out);

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

	    public function image_add_to_table()
		{
			$videoDataString = Input::get('videoData');
	        $payload = json_decode($videoDataString, true);

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

			$video = new Images;
			$video->title        		= $payload['title'];
			$video->description  		= $payload['description'];
	        $video->channel_id          = BaseController::get_channel_id();
			$video->thumbnail_name 		= "https://s3.amazonaws.com/1stud-images/" . $payload['thumbnail_name'];
			$video->file_name 			= $payload['file_name'];
			$video->encode_status 		= $payload['encode_status'];
			$video->type 				= $payload['type'];
			$video->hd_width 			= $payload['hd_width'];
	        $video->hd_height 			= $payload['hd_height'];      
	        $video->hd_file_size 		= $payload['hd_file_size'];
	        $video->hd_mime_type		= $payload['hd_mime_type'];
	        $video->sd_file_name		= $payload['sd_file_name'];

	        $video->sd_width            = $payload['sd_width'];
	        $video->sd_height           = $payload['sd_height'];
	        $video->sd_file_size        = $payload['sd_file_size'];
	        $video->sd_mime_type        = $payload['sd_mime_type'];
	        $video->created_at          = time();
	        $video->updated_at          = time();
	        $video->storage             = ' ';
	        $status = $video->save();

	        $response = array('update' => 'success');
	        header('Content-Type: application/json');
	        return Response::json($response, 200);
		}

		public function delete_image()
		{
			$imgId = Input::get('id');

	        $image = Images::find($imgId);
	        $image->delete();

	        $videos_in_playlist = Image_in_folders::where('image_id', '=', $videoId)->get();
	        foreach($videos_in_playlist as $video_in_playlist) {
	            $video_in_playlist->delete();
	        }

	        return Response::json([
	            'status' => true
	        ], 200);

		}
		public function get_images_for_folders() {
	        $images = Images::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'desc')->get();
	        $images = Time::change_to_human_data_in_array($images);

	        $collections = Image_folders::where('channel_id', '=', BaseController::get_channel_id())->orderBy('title', 'ASC')->get();

	        $this->data['images'] = $images;
	        $this->data['collections'] = $collections;
	    	$this->data['playlists'] = array();
	    	$this->data['parent_playlist_id'] = -1;
	    	$this->data['parent_playlist_level'] = 0;
	    	$this->data['calledFrom'] = '';

	        return $this->render('images/images_all_for_folders');
	    }

		public function add_to_folder()
		{
	        $title = trim(Input::get('title'));
	        $file = Input::file('file');
	        $playlist = Input::get('playlist');

	        if (empty($title)) {

	            return Response::json([
	                'status' => false,
	                'message' => 'Wrong data'
	            ], 200);
	        }


	        $collection = new Image_folders;
	        $collection->title = $title;
	        $collection->channel_id = BaseController::get_channel_id();

	        if ($collection->save()) {

	            if (is_array($playlist)) {

	                $insert = array();
	                foreach ($playlist as $vid) {
	                    $insert[] = array('image_id' => $vid, 'folder_id' => $collection->id );
	                }

	                Image_in_folders::Insert($insert);
	            }

	            return Response::json([
	                'status' => true,
	                'folder' => $collection,
	                'collection_id' => $collection->id
	            ], 200);
	        }
			
		}
		
		public function edit_folder_get(){
			$id = Input::get('id');

	        $collection = Image_folders::find($id);
	        $this->data['collection'] = $collection;
	        $video_ids = Image_in_folders::where('folder_id', '=', $id)->get();

	        $videos = array();
	        $v = array();
	        foreach ($video_ids as $video_id) 
	        {
	            $vObj = Images::find($video_id['image_id']);
	            if (!is_object($vObj)) 
	            {
	                Log::info("[VMC] video record not found => ".$video_id['image_id']);
	                continue;
	            }

	            $videos[] = Images::find($video_id['image_id']);
	            $v[] = $video_id['image_id'];
	        }
	        $channel = Channel::find(BaseController::get_channel_id());
	        $api_url_exist = '0';
	        $p = strpos($channel->storage, 'login_url|');
	        if ($p > 0)
	        {
	            $api_url = substr($channel->storage, $p+10);
	            $p = strpos($api_url, '|');
	            if ($p > 0) $api_url_exist = '1'; 
	        }

	        $this->data['videos'] = Time::change_to_human_data_in_array($videos);
	        $this->data['vmc'] = implode('|', $v);
	        $this->data['api_url_exist'] = $api_url_exist;

	        Log::info("vids in collection :".implode('|', $v));

	        return $this->render('images/edit_folder');

		}
		public function edit_folder_post(){
	        $id = Input::get('id');
	        $title = Input::get('title');
	        $playlist = Input::get('playlist');

	        $collection = Image_folders::find($id);
	        $collection->title = $title;
	        $collection->save();

	        Image_in_folders::where('folder_id', '=', $id)->delete();
	        if (is_array($playlist)) {

	            $insert = array();
	            foreach ($playlist as $vid) {
	                $insert[] = array('image_id' => $vid, 'folder_id' => $id );
	            }

	            Image_in_folders::Insert($insert);
	        }

	        return Response::json([
	            'status' => true,
	            'folder' => $collection
	        ], 200);

		}

		public function delete_folder(){
			$collectionId = Input::get('collectionId');

	        $collection = Image_folders::find($collectionId);
	        $collection->delete();

	        Image_in_folders::where('folder_id', '=', $collectionId)->delete();

	        return Response::json([
	            'status' => true
	        ], 200);
		}
	    public function get_image_by_id() {
	        $id = Input::get('id');

	        $video = Images::find($id);

	        $collections = Image_folders::where('channel_id', '=', BaseController::get_channel_id())->get();
	        $video_in_collections = Image_in_folders::where('image_id', '=', $id)->get();

	        $channel = Channel::find(BaseController::get_channel_id());
	        $api_url_exist = '0';
	        $p = strpos($channel->storage, 'login_url|');
	        if ($p > 0)
	        {
	            $api_url = substr($channel->storage, $p+10);
	            $p = strpos($api_url, '|');
	            if ($p > 0) $api_url_exist = '1';
	        }

	        $this->data['video'] = $video;
	        $this->data['collections'] = $collections;
	        $this->data['video_in_collections'] = $video_in_collections;
	        $this->data['api_url_exist'] = $api_url_exist;

	        return $this->render('images/edit_image');
	    }

	    public function edit_image() {
	        $id = Input::get('id');
	        $title = Input::get('title');
	        $collections = Input::get('collections');

	        $image = Images::find($id);

	        $image->title = $title;

	        if($image->save()) {

	            Image_in_folders::where('image_id', '=', $image->id)->delete();

	            if (is_array($collections))
	            {
	                foreach($collections as $collection) {
	                    $images_in_collections = new Image_in_folders;
	                    $images_in_collections->image_id = $image->id;
	                    $images_in_collections->folder_id = $collection;
	                    $images_in_collections->save();
	                }
	            }

	            $imageInplayLists = Image_in_slide::where('image_id', '=', $image->id)
					// ->where('type', '=', '0')
					->get();

	            // $t = new TVAppController();

	            foreach($imageInplayLists as $playList)
	            {
	                if ($playList->slide_id == 0)
	                {
	                    $this->BuildFeedXML($playList->slide_id);
	                }
	                else
	                {
	                    $image = Image_slide::find( $playList->slide_id);
	
	                    if ($image->channel_id == BaseController::get_channel_id())
	                    {
	                        $this->BuildFeedXML($playList->slide_id);
	                    }
	                }
	            }

	            return Response::json([
	                'status' => true
	            ], 200);
	        }

	    }


        public function BuildFeedXML($id)
	    {
	        $c = Channel::find(BaseController::get_channel_id());

	        $this->BuildPlaylistRokuXml($id);
	        $this->BuildPlaylistMrss($id);

	        return 1;
	    }

	    private function BuildPlaylistRokuXml($id) {

	        $playlist = Image_slide::with([
	            'images',
	        ])->find($id);
	        $channel_id = BaseController::get_channel_id();
	        $t = new TvHelper($channel_id);

	        $title = $t->xtrim($playlist->title).'.xml';
	        $path = public_path()."/imagery/channel_$channel_id/roku/xml/";
	        if (!File::isDirectory($path))
				File::makeDirectory($path,0777,true);
	        $view = View::make('images.roku_xml.slide', [
	            'slides' => $playlist,
	        ]);

	        File::put($path.$title, $view->render());
	    }

	    private function BuildPlaylistMrss($id) {
	        $channel_id = BaseController::get_channel_id();
	        $path = public_path()."/imagery/channel_$channel_id/roku/mrss";
	        if (!File::isDirectory($path))
				File::makeDirectory($path,0777,true);
	        $t = new TvHelper($channel_id);
	        $playlist = Image_slide::find($id);
	        $title = '/'.$t->xtrim($playlist->title).'.mrss';
			$playlist->mrss_url = $title;
	        $playlist->save();
	        $view = View::make('images.mrss.slide', [
	            'slides' => $playlist,
	        ]);

	        File::put($path.$title, $view->render());
	    }

	    public function add_to_slide() {

	        $title = trim(Input::get('title'));
	        $description = trim(Input::get('description'));

	        if (empty($title)) {

	            return Response::json([
	                'status' => false,
	                'message' => 'Wrong data'
	            ], 200);
	        }

	        $playlist = new Image_slide;
	        $playlist->title = $title;
	        $playlist->description = $description;
	        $playlist->channel_id = BaseController::get_channel_id();

	        if ($playlist->save()) {
	            return Response::json([
	                'status' => true,
	                'playlist_id' => $playlist->id
	            ], 200);
	        }
	    }

	    public function insert_image_in_playlist() {

	        $playlist = Input::get('playlist');
	        if (is_array($playlist)) {

	            $insert = array();

	            $duration = 0;

	            $thumbnail_name = '';
	            $video_array_for_playlist = array();
				if(isset($playlist['playlists'])) {
					foreach($playlist['playlists'] as $video_id) {

						$videos = Images::find((int)$video_id);
						array_push($video_array_for_playlist,$videos->getVideoPathAttribute());

	                    $insert[] = array(
	                        'slide_id' => $playlist['playlist_id'],
	                        'image_id' => $video_id
	                    );

	                    $thumbnail_name = $videos->thumbnail_name;
	                }
	            } else {
					$playlist = Image_slide::find($playlist['playlist_id']);
	                return Response::json([
	                    'status' => true,
						'slide'  => $playlist
	                ], 200);
	            }

	            $playlist = Image_slide::find($playlist['playlist_id']);
	            $playlist->thumbnail_name = $thumbnail_name;
			    //Integrate Segmenter
			    // $client = new Client();
	      //        error_log("ARRAY : " . json_encode($video_array_for_playlist));
	      //        $response = $client->request('POST', 'http://104.131.157.125:3001/playlist', [
	      //            'form_params' => [
	      //               'name' => $playlist->title,
	      //                'duration' => $playlist->duration,
	      //                'videos' => json_encode($video_array_for_playlist)
	      //            ]
	      //        ]);

	            $videos_in_playlists = Image_in_slide::where('slide_id', '=', $playlist['id'])->get();

	            foreach($videos_in_playlists as $videos_in_playlist) {
	                $videos_in_playlist->delete();
	            }

	            Image_in_slide::Insert($insert);
	            $playlist->save();

	            return Response::json([
	                'status' => true,
	                'slide'  => $playlist
	            ], 200);
	        } else {
	            return Response::json([
	                'status' => false,
	                'slide'  => ''
	            ], 200);
	        }
	    }

        public function delete_slide() {
	        $playlist_id = Input::get('playlistId');
			$playlist = Image_slide::find($playlist_id);
			error_log("****STEP ! DELETE");
			// $client = new Client();
			// error_log("****STEP ! DELETE". $playlist->title.$playlist->duration);
			// $response = $client->request('POST', 'http://104.131.157.125:3001/playlist', [
			//  	'form_params' => [
			// 	'option' => "delete",
			// 	'name' => $playlist->title,
			// 	'duration' => $playlist->duration
			//  	]
			// ]);

	        Image_slide::find($playlist_id)->delete();
	        // Schedule::where('playlist_id', '=', $playlist_id)->delete();
	        Image_in_slide::where('slide_id', '=', $playlist_id)->delete();
	    }

	    public function get_slide_by_id() {
	        $id = trim(Input::get('id'));
	        $playlist = Image_slide::find($id);

	        $video_ids = Image_in_slide::where('slide_id', '=', (int) $playlist->id)->get();

	        $videos = [];

	        foreach ($video_ids as $video_id) {

	            $video = Images::find($video_id['image_id']);

	            if (!$video) continue;

	            $video->time = Time::change_to_human_data_in_object($video);

	            //$video = Time::change_to_human_data_in_array([$video->toArray()]);
	            $videos[] = $video;
	        }

	        $playlist->time = Time::change_to_human_data_in_object($playlist);

	        return View::make('images/edit_slide')->with('playlist', $playlist)->with('videos', $videos);
	    }

        public function edit_slide_post() {
	        $id = trim(Input::get('id'));
	        $title = trim(Input::get('title'));
	        $description = trim(Input::get('description'));

	        if (empty($id) || empty($title)) {
	            return Response::json([
	                'status' => false,
	                'message' => 'Wrong data'
	            ], 200);
	        }


	        $playlist = Image_slide::find($id);
	        $playlist->title = $title;
	        $playlist->description = $description;
	        $playlist->channel_id = BaseController::get_channel_id();

	        if ($playlist->save()) {
	            return Response::json([
	                'status' => true,
	                'playlist_id' => $playlist->id
	            ], 200);
	        }
	    }







	}

?>
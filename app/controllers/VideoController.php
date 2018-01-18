<?php

include_once("TVAppController.php");

use \App\Helpers\Playlists\TvappPlaylistHelper as TvHelper;
class VideoController extends BaseController {

    public function get_videos() {
        if(!Auth::user()->is(User::USER_MANAGE_MEDIA) || !in_array(BaseController::get_channel_id(), Auth::user()->checkChannelIds())) {
            return App::abort(404);
        }

        /// temp /// 
//         $videos = Video::where('id', '>', '0')->orderBy('id', 'desc')->get();
//         //http://prolivestream.s3.amazonaws.com/thumbnails/7026292f_e90d_c966_0a84_b68aa8eafeea.jpg
//         foreach ($videos as $key => $val) {
//         	$id = (int)$val['id'];
//         	$thumb = (string)$val['thumbnail_name'];
//         	//$thumb = str_replace('http://prolivestream.s3.amazonaws.com/thumbnails/','https://onestudio.imgix.net/',$thumb);
//         	//$thumb = str_replace('https://s3.amazonaws.com/aceplayout/','https://onestudio.imgix.net/',$thumb);
//         	//$thumb = str_replace('https://s3.amazonaws.com/ifame/','https://onestudio.imgix.net/',$thumb);
//         	if(strpos($thumb, 'enhance&q=')==false){
//         	   $thumb = $thumb.'?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60';
//         	}else if(strpos($thumb, 'w=266&h=150')!==false){
//         		$thumb = str_replace('w=266&h=150','w=336&h=210',$thumb);
//         	}
        			
//         	$status = Video::where('id', '=', $id)->update(array(
//         			'thumbnail_name'=> $thumb
//         	));
        	
//         	//$arr[$key]['thumbnail_name'] = $thumb;
//         }
        /// end of temp ///

        
        DB::connection()->disableQueryLog();

        // Get videos
        $stype = Input::get('stype');
        $search = Input::get('search');

        if ($stype==0) $videos = Video::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'desc')->get();
        else if ($stype==2) $videos = Video::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'asc')->get();
        else if ($stype==1) $videos = Video::where('channel_id', '=', BaseController::get_channel_id())->orderBy('title', 'asc')->get();

        if ($search != '')
        {
            $fv = array();
            foreach($videos as $v)
            {
                if (stripos($v->title, $search) !== false) $fv[] = $v; 
            }
            $videos = $fv;
        }

        $fv = array();
        foreach($videos as $v)
        {
            $vic = Videos_in_collections::where('video_id', '=', $v->id)->first();
            if (is_object($vic))
            {
                $collection = Collections::find($vic->collection_id);
                $v->mb_file_name = $collection->title;
            }
            $fv[] = $v;
        }
        $videos = $fv;



        
        // Change durations to human format
        $videos = Time::change_to_human_data_in_array($videos);

		foreach ($videos as $video) {
			$video['shows'] = Video_show::where('video_id',$video->id)->with('show_names')->get();
		}
        // Set data
        $this->data['videos'] = $videos;
        $this->data['stype'] = $stype;
        $this->data['search'] = $search;
        
        if (Session::get('scrollVideoTo') > 0) $this->data['scrollToVideo'] = Session::get('scrollVideoTo');
        else $this->data['scrollToVideo'] = '0';

        Session::set('scrollVideoTo', '');

        //$collections = Collections::all();
        return $this->render('index');
    }
    
    public function downloadVideo($channel_id,$video_id){
        $video = Video::find($video_id);
        $file_name = $this->__get_video_path($video);
        set_time_limit(0);
        header('Content-Description: File Transfer');
        header('Content-Type: video/mp4');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($video->title.'.mp4') . "\"");
        readfile($file_name);
        exit();
    }

    public function getPrerollUrl(){
        $channel_id = BaseController::get_channel_id();
        $videos = DB::table('videos_in_collections')
            ->join('collections', 'collections.id', '=', 'videos_in_collections.collection_id')
            ->Where('collections.pre_roll', 'yes')
            ->Where('collections.channel_id', $channel_id)
            ->get();
        if(count($videos) > 0){
            $item = array_rand($videos,1);
            $video_id =  (count($videos) == 1) ? $videos[0]->video_id : $videos[$item]->video_id;
            $video = Video::find($video_id);
            $file_name = $video->file_name;
            if (strpos($file_name, 'https://') !== false || strpos($file_name, 'http://') !== false) {
                $prerollUrl = $file_name;
            }
            else{
                $prerollUrl = $this->__get_video_path($video);
            }
        }
        else{
            $prerollUrl = '';
        }
        return $prerollUrl;

    }

    public function __get_video_path($v)
    {
        if ($v->source == '0' || $v->source == 'internal') 
        {
            $fname = 'http://'.BaseController::get_channel_id().'.1studio.tv.global.prod.fastly.net/'.$v->file_name.'.mp4';
            return $fname;
        }
        return $v->file_name;
    }

    public function get_video_path()
    {
    	$vid = Input::get('vid');
        $prerollUrl = $this->getPrerollUrl();
        if ($vid > 0)
        {
            $v = Video::find($vid);
            print json_encode(array(
                'videoUrl' => $this->__get_video_path($v),
                'prerollUrl' => $prerollUrl,
				'v_source' => $v->source
            ));
        }
        exit();
    }

    public function get_video_duration()
    {
    	$vid = Input::get('vid');
        Log::info("get_video_duration($vid)");
        if ($vid > 0)
        {
            $v = Video::find($vid);
            $url = $this->__get_video_path($v);

            $command = escapeshellcmd("avconv -i $url");
            $out = shell_exec($command." 2>&1");
            //Log::info("get_video_duration->result = ".$out);
            $pos = strpos($out, 'Duration:');
            if ($pos !== false)
            {
                $duration = substr($out, $pos+9);
                $pos = strpos($duration, ',');
                $duration = substr($duration, 0, $pos);
                $tparts = explode(':', $duration);
                $seconds = round($tparts[0] * 3600 + $tparts[1] * 60 + $tparts[2]);
                return "success:".$seconds;
            }
            else return "error:Error accessing video url";
        }
        return "error:Error invalid video id";
    }

    public function add_video() {
//        $collections = Collections::all();

//        $coll = array();

//        foreach($collections as $collection) {
//            array_push($coll, $collection->id, $collection->title);
//        }


        $title = trim(Input::get('title'));
        $file_name = trim(Input::get('file_name'));
        $video_format = trim(Input::get('video_format'));
        $encoded_video_id = trim(Input::get('encoded_video_id'));
        $video = new Video;
        $video->title = $title;
        $video->file_name = $encoded_video_id;
        $video->job_id = $encoded_video_id;
        $video->video_format = $video_format;
        $video->channel_id = BaseController::get_channel_id();
        $video->storage = $encoded_video_id;
        $video->save();

        $filename = pathinfo($title);

        $collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->get();

        return View::make('video/add_video')->with('title', $filename['filename'])
            ->with('collections', $collections);
    }

    //hybrik
    public function add_video_s3() {
    
    	$title = trim(Input::get('title'));
    	$file_name = trim(Input::get('file_name'));
    	$video_format = trim(Input::get('video_format'));
    	$encoded_video_id = trim(Input::get('encoded_video_id'));
    	$video = new Video;
    	$video->title = $title;
    	$video->file_name = $file_name;
    	$video->job_id = $encoded_video_id;
    	$video->video_format = $video_format;
    	$video->channel_id = BaseController::get_channel_id();
    	$video->storage = $encoded_video_id;
    	$video->save();
    
    	$filename = pathinfo($title);
    
    	$collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->get();
    
    	return View::make('video/add_video')->with('title', $filename['filename'])
    	->with('collections', $collections);
    }
    
    

    public function get_videos_for_playlists() {
        $videos = Video::where('channel_id', '=', BaseController::get_channel_id())->where('source', '=', 'internal')->orderBy('id', 'desc')->get();
        $videos = Time::change_to_human_data_in_array($videos);

        $collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->orderBy('title', 'ASC')->get();

        $this->data['videos'] = $videos;
        $this->data['collections'] = $collections;

        // Added playlists, parent_playlist_id, parent_playlist_level
    	$this->data['playlists'] = array();
    	$this->data['parent_playlist_id'] = -1;
    	$this->data['parent_playlist_level'] = 0;
    	$this->data['calledFrom'] = '';

        return $this->render('video/video_all_for_playlists');
    }

    public function tvapp_get_videos_for_playlists() {
    	$videos = Video::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'desc')->get();
    	$videos = Time::change_to_human_data_in_array($videos);
    
    	$collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->orderBy('title', 'ASC')->get();

    	$level = Input::get('level');
    	$id = Input::get('id');
  		$playlists = TvappPlaylist::where('level', '=', $level+1)
                                  ->where('channel_id', '=', BaseController::get_channel_id())
  		                          ->get();

    
    	$this->data['videos'] = $videos;
    	$this->data['collections'] = $collections;

        // Added playlists, parent_playlist_id, parent_playlist_level
    	$this->data['playlists'] = $playlists;
    	$this->data['parent_playlist_id'] = $id;
    	$this->data['parent_playlist_level'] = $level;
    	$this->data['calledFrom'] = 'tvapp';
    
    	return $this->render('video/video_all_for_playlists');
    }

    public function tvweb_get_videos_for_playlists() {
    	$videos = Video::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'desc')->get();
    	$videos = Time::change_to_human_data_in_array($videos);
    
    	$collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'DESC')->get();
    
    	$this->data['videos'] = $videos;
    	$this->data['collections'] = $collections;

        // Added playlists, parent_playlist_id, parent_playlist_level
    	$this->data['playlists'] = array();
    	$this->data['parent_playlist_id'] = 0;
    	$this->data['parent_playlist_level'] = 0;
    	$this->data['calledFrom'] = 'tvweb';
    
    	return $this->render('video/video_all_for_playlists');
    }
    
    public function get_videos_by_playlist_id() {
        $playlist_id = Input::get('playlist_id');

        if (trim($playlist_id) == '') {

            return Response::json([
                'status' => false,
                'message' => Error::returnError(Error::ERROR_PLAYLIST_ID_EMPTY)
            ], 200);
        }

        if ((int) $playlist_id == 0) {

            return Response::json([
                'status' => false,
                'message' => Error::returnError(Error::ERROR_WRONG_DATA)
            ], 200);
        }

        $video_ids = Video_in_playlist::where('playlist_id', '=', (int) $playlist_id)->get();

        $videos = [];

        foreach ($video_ids as $video_id) {

            $video = Video::find($video_id['video_id']);

            if (!$video) continue;

            //$video = Time::change_to_human_data_in_array([$video->toArray()]);
            $videos[] = $video;
        }

        $videos = Time::change_to_human_data_in_array($videos);

        $this->data['videos'] = $videos;

        return $this->render('playlist/videos_for_playlist');
    }
    
    
    public function tvapp_get_videos_by_playlist_id() {
    	$playlist_id = Input::get('playlist_id');
    
    	if (trim($playlist_id) == '') {
    
    		return Response::json([
    				'status' => false,
    				'message' => Error::returnError(Error::ERROR_PLAYLIST_ID_EMPTY)
    		], 200);
    	}
    
    	if ((int) $playlist_id == 0) {
    
    		return Response::json([
    				'status' => false,
    				'message' => Error::returnError(Error::ERROR_WRONG_DATA)
    		], 200);
    	}
    
    	$video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', (int) $playlist_id)->get();
    
    	$videos = [];
    
    	foreach ($video_ids as $video_id) {
    
    		$video = Video::find($video_id['video_id']);
    
    		if (!$video) continue;
    
    		//$video = Time::change_to_human_data_in_array([$video->toArray()]);
    		$videos[] = $video;
    	}
    
    	$videos = Time::change_to_human_data_in_array($videos);
    
    	$this->data['videos'] = $videos;
    
    	//return $this->render('tvapp/tvapp_videos_for_playlist');
    	return $this->render('tvapp/tvapp_videos_for_playlist');
    }

    

    public function tvweb_get_videos_by_playlist_id() {
    	$playlist_id = Input::get('playlist_id');
    
    	if (trim($playlist_id) == '') {
    
    		return Response::json([
    				'status' => false,
    				'message' => Error::returnError(Error::ERROR_PLAYLIST_ID_EMPTY)
    		], 200);
    	}
    
    	if ((int) $playlist_id == 0) {
    
    		return Response::json([
    				'status' => false,
    				'message' => Error::returnError(Error::ERROR_WRONG_DATA)
    		], 200);
    	}
    
    	$video_ids = TvwebVideo_in_playlist::where('tvweb_playlist_id', '=', (int) $playlist_id)->get();
    
    	$videos = [];
    
    	foreach ($video_ids as $video_id) {
    
    		$video = Video::find($video_id['video_id']);
    
    		if (!$video) continue;
    
    		//$video = Time::change_to_human_data_in_array([$video->toArray()]);
    		$videos[] = $video;
    	}
    
    	$videos = Time::change_to_human_data_in_array($videos);
    
    	$this->data['videos'] = $videos;
    
    	//return $this->render('tvweb/tvweb_videos_for_playlist');
    	return $this->render('tvweb/tvweb_videos_for_playlist');
    }
    
    
//    public function get_videos_by_playlist_id() {
//
//        $playlist_id = Input::get('playlist_id');
//
//
//        if (trim($playlist_id) == '') {
//
//
//            return Response::json([
//                'status' => false,
//                'message' => Error::returnError(Error::ERROR_PLAYLIST_ID_EMPTY)
//            ], 200);
//        }
//
//        if ((int) $playlist_id == 0) {
//
//            return Response::json([
//                'status' => false,
//                'message' => Error::returnError(Error::ERROR_WRONG_DATA)
//            ], 200);
//        }
//
//        $video_ids = Video_in_playlist::where('playlist_id', '=', (int) $playlist_id)->get();
//
//        $videos = [];
//
//        foreach ($video_ids as $video_id) {
//
//            $video = Video::find($video_id['video_id']);
//
//            if (!$video) continue;
//
//            $video = Time::change_to_human_data_in_array([$video->toArray()]);
//            $videos[] = $video;
//        }
//
//        $video_block_string
//            = "<div class=\"row center-block contnet-wrap playlists height-inherit\">
//            <div class=\"col-md-6 height-inherit\" id=\"video-md-col\">
//                <div style=\"background-color: #ffffff; border-radius: 10px; padding: 0 10px 0 10px; margin-top: 8px;\" class=\"height\">
//                    <p class=\"title-name\">Videos
//
//                        <button class=\"btn btn-success plus\">
//                            <a href=\"upload\">+</a>
//                         </button>
//
//                    </p>
//                    <p style=\"text-align: right;\">
//
//                    </p>
//                    <div class=\"input-group\">
//                        <input type=\"text\" class=\"form-control\" placeholder=\"Search\" id=\"search-query-3\">
//                    <span class=\"input-group-btn\">
//                        <button type=\"submit\" class=\"btn\"><span class=\"fui-search\"></span></button>
//                     </span>
//                        </div>
//
//                                            <div class=\"row center-block margin-block\" id=\"container_content\">
//                                                    <div class=\"blocks height\">
//
//             <div class=\"col-md-12\" style=\"text-align: center; height: 100%;  overflow-y: scroll;\" id=\"video_blocks\">
//             <hr class=\"hr-2\">
//        ";
//
//        $desc_header
//            = "<div class=\"row center-block contnet-wrap description\" style=\"margin-top: -357%;\">
//                            <div class=\"col-md-4\">
//                                <div class=\"description-block\">
//                                    <p class=\"title-name\">
//                                         DESCRIPTION
//                                    </p>
//
//                                    <span class=\"glyphicon glyphicon-remove\"></span>
//                            ";
//
//        $desc_footer
//            = "
//        </div></div></div></div></div></div>
//        ";
//
//        $video_description = [];
//
//
//        foreach ($videos as $video) {
//            $video = $video[0];
//
//            $video_block_string .= "
//                        <section data-video_id=\"{$video['id']}\" style='cursor: pointer;' class=\"section_video\">
//
//                                <div class=\"row center-block\">
//                                   <div class=\"col-md-6\" style=\"text-align: center;\">
//                                       <img src=\"{$video['thumbnail_name']}\" class='thumbnail_video'>
//                                          </div>
//                                          <div class=\"col-md-6\" style=\"\">
//                                       <p style=\"text-align: left\">
//                                            {$video['title']}
//                                        </p>
//                                        <p style=\"text-align: left\">
//                                            <img src=\"/images/time_icon.png\" style=\"margin-top: -4px;\">
//                                            {$video['time']}
//                                        </p>
//                                </div>
//                                </div>
//
//                                 <hr class=\"hr-2-2\">
//                         </section>
//            ";
//
//
//            // Generate video description
//            $video_description[$video['id']] = "
//                        <section style='cursor: pointer;'>
//                             <hr class=\"hr-2\">
//                                <div class=\"row center-block\">
//                                   <div class=\"col-md-12\" style=\"text-align: center;\">
//            <video class=\"video-js\" id=\"video-js\" preload=\"auto\" poster=\"{$video['thumbnail_name']}\" data-setup=\"{}\">
//              <source src=\"https://prolivestream.s3-us-west-2.amazonaws.com/playlists/{$video['file_name']}.mp4\"
//              type=\"video/mp4\">
//            </video>
//                                </div>
//
//                                 <hr class=\"hr-2-2\">
//
//                                 <p> {$video['description']}</p>
//                                 <hr class=\"hr-2-2\">
//                        </section>
//            ";
//
//        }
//        $video_block_string
//            .= "
//        </div></div></div></div></div></div>
//        ";
//
//
//        return Response::json([
//            'playlists' => $video_block_string,
//            'desc_header' => $desc_header,
//            'desc_footer' => $desc_footer,
//            'video_desc_blocks' => $video_description
//        ], 200);
//
//    }

    public function get_video_description() {

        $video_id = trim(Input::get('video_id'));

        if (empty($video_id)) {

            return;
        }

        $desc = Video::find((int) $video_id);
        
        $this->data['desc'] = $desc;

               
        $filename = $desc->file_name;
        $filenameExt = $desc->file_name.'.jpg';
        //"bucket": "prolivestream"
        $form = array(
        		'key' => $filenameExt,
        		'AWSAccessKeyId' => 'AKIAJWU4DYR6OMHE2YPQ',
        		'acl' => 'public-read',
        );
        
        $form['policy'] = '{
            "expiration": "2016-12-01T12:00:00.000Z",
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
                        "$filenameExt"
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
       
        $file_bucket = "aceplayout";
        if (strpos($desc->thumbnail_name, 'ifame') !== false) {
            $file_bucket = 'ifame';
        }
               
        $this->data['policy_encoded'] = $form['policy_encoded'];
        $this->data['signature'] = $form['signature'];
        $this->data['key'] = $form['key'];
        $this->data['file_bucket'] = $file_bucket;
        $this->data['file_name'] = $filename;
        $this->data['file_name_ext'] = $filenameExt;
        
        $this->data['aws_access_key_id'] = $form['AWSAccessKeyId'];
        $this->data['aws_secret_key'] = 'j+9HpYI9t8r/Qvlj4vKsgqhWrMebGTmq7+TFGp9L';
        $this->data['video_id'] = $video_id;        
        //$accessKeyId = 'AKIAIJCI7GWDUP52SRXQ';
        //$secret = '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';

        $this->data['video_file'] = "http://$file_bucket".".s3.amazonaws.com/".$desc->file_name.".mp4";
        if ($desc->source != 'internal') $this->data['video_file'] = $desc->file_name;
                
        return $this->render('video/video_desc');

    }
	public function get_video_by_id_for_tvapp() {		
        $id = Input::get('id');		
        $video = Video::find($id);		
        if ($video->viewing=='') $video->viewing = 'inherit';		
        $collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->get();		
        $video_in_collections = Videos_in_collections::where('video_id', '=', $id)->get();		
        $channel = Channel::find(BaseController::get_channel_id());		
        $api_url_exist = '0';		
        $p = strpos($channel->storage, 'login_url|');		
        if ($p > 0)		
        {		
            $api_url = substr($channel->storage, $p+10);		
            $p = strpos($api_url, '|');		
            if ($p > 0) $api_url_exist = '1'; 		
        }		
        $data =Array();		
        $data['video'] = $video;		
        $data['collections'] = $collections;		
        $data['video_in_collections'] = $video_in_collections;		
        return $data;		
    }

    public function get_video_by_id() {
        $id = Input::get('id');

		$tags = Channel_tags::where('channel_id','=',BaseController::get_channel_id())->get();
		$shows = Channel_show::where('channel_id','=',BaseController::get_channel_id())->get();
		$video_show = Video_show::where('video_id',$id)->select('show_id')->get();
        $video_tags = Video_tags::where('video_id',$id)->select('tag_id')->get();

		$video = Video::find($id);
        if ($video->viewing=='') $video->viewing = 'inherit';

        $collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->get();
        $video_in_collections = Videos_in_collections::where('video_id', '=', $id)->get();

        $channel = Channel::find(BaseController::get_channel_id());
        $api_url_exist = '0';
        $p = strpos($channel->storage, 'login_url|');
        if ($p > 0)
        {
            $api_url = substr($channel->storage, $p+10);
            $p = strpos($api_url, '|');
            if ($p > 0) $api_url_exist = '1'; 
        }

	   $this->data['tags'] = $tags;
	   $tag_ids = array();
	   $show_ids = array();
	   if(isset($video_show) && count($video_show) > 0){
		   foreach ($video_show as $video_show) {
			   array_push($show_ids, $video_show->show_id);
		   }
	   }
	   if(isset($video_tags) && count($video_tags) > 0){
		   foreach ($video_tags as $video_tag) {
			   array_push($tag_ids, $video_tag->tag_id);
		   }
		}

		$this->data['shows'] = $shows;
		$this->data['show_ids'] = $show_ids;
		$this->data['tag_ids'] = $tag_ids;
        $this->data['video'] = $video;
        $this->data['collections'] = $collections;
        $this->data['video_in_collections'] = $video_in_collections;
        $this->data['api_url_exist'] = $api_url_exist;

        return $this->render('video/edit_video');
    }

    public function get_videos_by_collections() {
        $id = Input::get('id');

        if($id == 00) {
            $videos = Video::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'DESC')->get();

            return Response::json([
                'videos' => Time::change_to_human_data_in_array($videos)
            ], 200);
        } else if($id == 01) {
            $videos = Video::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'DESC')->get();
            $allVideos = [];
            foreach($videos as $video) {
                array_push($allVideos, $video->id);
            }

            $videos_in_collections = Videos_in_collections::all();
            $allCategorized = [];
            foreach($videos_in_collections as $videos_in_collection) {
                array_push($allCategorized, $videos_in_collection->video_id);
            }

            foreach($allCategorized as $categorized) {
                if(in_array($categorized, $allVideos)) {
                    unset($allVideos[array_search($categorized, $allVideos)]);
                }
            }
            if(!empty($allVideos)) {
                $uncategorized = Video::whereIn('id', $allVideos)->orderBy('id', 'DESC')->get();

                return Response::json([
                    'videos' => Time::change_to_human_data_in_array($uncategorized)
                ], 200);
            } else {
                return Response::json([
                    'videos' => ''
                ], 200);
            }

        } else {
            $videos_in_collections = Videos_in_collections::where('collection_id', '=', $id)->get();

            $videos = [];
            foreach($videos_in_collections as $videos_in_collection) {
                array_push($videos, Video::find($videos_in_collection->video_id));
            }

            return Response::json([
                'videos' => Time::change_to_human_data_in_array($videos)
            ], 200);
        }
    }

    public function edit_video() {
        Log::info("edit_video => ".print_r($_REQUEST, true));
        $id = Input::get('id');
        $title = Input::get('title');
        $description = Input::get('description');
        $collections = Input::get('collections');
        $duration = Input::get('duration');
        $viewing = Input::get('viewing');
        if ($viewing=='') $viewing = 'inherit';
        $thumbnail_source = Input::get('thumbnail_source');
		$tags = Input::get('tags');
		$show = Input::get('show');
		$season = Input::get('season');
		$episod = Input::get('episod');

		if(isset($tags) && !empty($tags)){
			$video_tag = Video_tags::where('video_id',$id)->get();
			if(count($video_tag) > 0) {
				Video_tags::where('video_id', $id)->delete();
			}
			foreach ($tags as $tag){
				$video_tags = new Video_tags;
				$video_tags->video_id = $id;
				$video_tags->tag_id = $tag;
				$video_tags->save();
			}
		}
		if(isset($show) && !empty($show)){
			$video_show = Video_show::where('video_id',$id)->get();
			if(count($video_show) > 0) {
				Video_show::where('video_id', $id)->delete();
			}
			foreach ($show as $value){
				$video_shows = new Video_show;
				$video_shows->video_id = $id;
				$video_shows->show_id = $value;
				$video_shows->save();
			}
	   	}

        $video = Video::find($id);

        $video->title = $title;
        $video->description = $description;
        $video->duration = $duration;
        $video->viewing = $viewing;
//		$video->show = $show;
		$video->episode = $episod;
		$video->season = $season;
        $video->thumbnail_source = $thumbnail_source;

        if($video->save()) {

            Videos_in_collections::where('video_id', '=', $video->id)->delete();

            if (is_array($collections))
            {
                foreach($collections as $collection) {
                    $videos_in_collections = new Videos_in_collections;
                    $videos_in_collections->video_id = $video->id;
                    $videos_in_collections->collection_id = $collection;
                    $videos_in_collections->save();
                }
            }

            //Log::info("edit_video => ".$video->id);
            $videoInplayLists = TvappVideo_in_playlist::where('video_id', '=', $video->id)
                                                     ->where('type', '=', '0')
                                                     ->get();

            // $t = new TVAppController();

            $t = new TvHelper(BaseController::get_channel_id());

            foreach($videoInplayLists as $playList)
            {
                if ($playList->tvapp_playlist_id == 0)
                {
                    $t->BuildFeedXML($playList->tvapp_playlist_id);
                }
                else
                {
                    $video = TvappPlaylist::find( $playList->tvapp_playlist_id);
                    //Log::info("edit_video -> ".$playList->tvapp_playlist_id." => ".$video->channel_id." => ".$video->id );
                    if ($video->channel_id == BaseController::get_channel_id())
                    {
                        //Log::info("edit_video / tvapp_buildXML / ".$playList->tvapp_playlist_id);
                        $t->BuildFeedXML($playList->tvapp_playlist_id);
                    }
                }
            }
            Session::set('scrollVideoTo', $id);

            return Response::json([
                'status' => true
            ], 200);
        }

    }

    public function delete_videos() {
        $videoId = Input::get('id');

        $video = Video::find($videoId);
        $video->delete();

        $videos_in_playlist = Video_in_playlist::where('video_id', '=', $videoId)->get();
        foreach($videos_in_playlist as $video_in_playlist) {
            $video_in_playlist->delete();
        }

        return Response::json([
            'status' => true
        ], 200);
    }
    
    

    function imgix_purge($url) {
    	$headers = array(
    			'Content-Type:application/json',
    			'Authorization: Basic '. base64_encode('32rqre8FuJwKhPwDXFb6Uaq4AEcSJ8vz'.':')
    	);
    	$payload = json_encode(array("url" => $url));
    	$curl = curl_init('https://api.imgix.com/v2/image/purger');
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    	curl_setopt($curl, CURLOPT_POST, 1);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec($curl);
    	curl_close($curl);
        // var_dump($url,$response);die;
    	return $response;
    }
    
    //action
    public function imgx_purge_image() {
    	
    	$imgUrl = Input::get('img_url');
    	$video_id = Input::get('video_id');
        // var_dump($imgUrl);die;
    	//imgix - Purging Images (when image is updated on s3 refresh image cache on imgx)
    	//https://docs.imgix.com/setup/purging-images
    	// curl "https://api.imgix.com/v2/image/purger" \
    	// -u "32rqre8FuJwKhPwDXFb6Uaq4AEcSJ8vz:" \
    	// -d "url=$imgUrl"
    	$video = Video::find($video_id);
        $video->thumbnail_name = $imgUrl;
        $video->save();
    	//imgix account key
    	//https://webapp.imgix.com/account?_ga=1.105082691.1347729318.1466708433

    	//cirl example
    	//https://gist.github.com/jacktasia/17cefd2c41a5b44d8460
    	$res = $this->imgix_purge($imgUrl);
    	
    	// var_dump($video_id,$imgUrl);die;
    	
//     	DB::connection()->disableQueryLog();
//     	$videos = Video::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'desc')->get();
//     	$videos = Time::change_to_human_data_in_array($videos);
//     	$this->data['videos'] = $videos;
//     	return $this->render('index');
    	
    	return Response::json([
    			'status' => true,
    			'res' => $res 
    	], 200);
    }
    
} 
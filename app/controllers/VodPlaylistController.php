<?php 

	class VodPlaylistController extends BaseController{
		
		public function index() {
		    if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
		        return App::abort(404);
		    }
			if( Auth::user()->playout_access == 1){
				return App::abort(404);
			}

		    $playlists = self::get_playlists();

		    foreach($playlists as $playlist) {
		        $video_ids = Video_in_playlist::where('playlist_id', '=', $playlist->id)->get();

		        foreach($video_ids as $video_id) {
		            $video = Video::find($video_id['video_id']);

		            if(isset($video['thumbnail_name'])) {
		                $playlist->video_thumb = $video['thumbnail_name'];
		                break;
		            } else {
		                $playlist->video_thumb = 'http://speakingagainstabuse.com/wp-content/themes/AiwazMag/images/no-img.png';
		            }
		        }
		    }

			$channel = self::get_channel();
            $channel_id = $channel->id;
			$this->data['channel_id'] = $channel_id;
			$this->data['playlists'] = $playlists;

		    return $this->render('vod_playlist/playlist_index');
		}
		

		public function genereteEmbedCode()
		{
			$data = trim(Input::get('html'));
			$data .= "<style>.playlist_wrapper,#playlist,#list{width: 100% !important;}body{overflow-y: hidden;}</style>";

			$file_path= public_path("playlist/embedPlaylist.html");
			$file = fopen($file_path, "a");

		    // $putData = fwrite($file, $data);
			$putData = file_put_contents($file_path, $data);

		    $putData = fclose($file);

		    if($putData){
		    	$fileUrl = asset('playlist/embedPlaylist.html');
		    	echo json_encode(
		    		array(
		    			'success' => true,
		    			'fileUrl' => $fileUrl
		    		)
		    	);
		    	die;
		    }
			// echo "<pre>";
			// var_dump(htmlspecialchars($data));die;


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

		public function get_playlist_by_id($channel_id,$playlist_id){
			$playlist = Playlist::find($playlist_id);
			$video_ids = Video_in_playlist::where('playlist_id', '=', $playlist_id)->get();
			$videos = [];

			foreach ($video_ids as $video_id) {
				$video = Video::find($video_id['video_id']);
				$path = $this->__get_video_path($video);
				$video['path'] = $path;
				if (!$video) continue;

				$video->time = Time::change_to_human_data_in_object($video);
				$videos[] = $video;
			}
			$playlist->time = Time::change_to_human_data_in_object($playlist);

			$this->data['playlist'] = $playlist;
			$this->data['videos'] = $videos;
			return $this->render('vod_playlist/single_playlist');

		}

		public function get_vod_playlist(){

		    $id = trim(Input::get('id'));
	        $playlist = Playlist::find($id);

	        $video_ids = Video_in_playlist::where('playlist_id', '=', (int) $playlist->id)->get();

	        $videos = [];

	        foreach ($video_ids as $video_id) {

	            $video = Video::find($video_id['video_id']);
				$path = $this->__get_video_path($video);
				$video['path'] = $path;

				if (!$video) continue;

	            $video->time = Time::change_to_human_data_in_object($video);

	            //$video = Time::change_to_human_data_in_array([$video->toArray()]);
	            $videos[] = $video;
	        }
	        $channel = self::get_channel();
	        $channel_id = $channel->id;
	        $playlist->time = Time::change_to_human_data_in_object($playlist);

	        return View::make('vod_playlist/playlist')
	        				->with('playlist', $playlist)
	        				->with('videos', $videos)
	        				->with('channel_id',$channel_id);

		}

		public function get_playlist_rss($channel_id, $playlist_id){
			if($playlist_id){

				$playlist = Playlist::find($playlist_id);

		        $video_ids = Video_in_playlist::where('playlist_id', '=', (int) $playlist->id)->get();

	        	// Get videos
		        $videos = [];

		        foreach ($video_ids as $video_id) {

		            $video = Video::find($video_id['video_id']);

		            if (!$video) continue;

		            $video->time = Time::change_to_human_data_in_object($video);

		            $videos[] = $video;
		        }
		  		header("Content-Type: application/rss+xml; charset=UTF-8");

		  		$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>'. "\n";
				$rssfeed .= '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:jwplayer="http://rss.jwpcdn.com/">'. "\n";
				$rssfeed .= '<channel>'. "\n";
				  $rssfeed .= '<title>Dynamic Playlist</title>'. "\n";
				  $rssfeed .= '<description></description>'. "\n";
				  $rssfeed .= '<jwplayer:kind>DYNAMIC</jwplayer:kind>' . "\n";

		        $playlist->time = Time::change_to_human_data_in_object($playlist);
		        foreach ($videos as $key => $value) {
		        	$videoPath = $this->get_video_path($value->id);
		  		

		        	$rssfeed .= '<item>'. "\n";
					    $rssfeed .= '<title>' . str_replace('&',' ',$value->title) . '</title>'. "\n";
					    $rssfeed .= '<description></description>'. "\n";
					    $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", strtotime($value->created_at)) . '</pubDate>'. "\n";
					    $rssfeed .= '<media:group>'. "\n";
					      $rssfeed .= '<media:content url="'.$videoPath.'" type="video/mp4"/>'. "\n";
					      $rssfeed .= '<media:thumbnail url="'.str_replace('?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60','',$value->thumbnail_name).'"  />'. "\n";
					      $rssfeed .= '<media:keywords></media:keywords>'. "\n";
					    $rssfeed .= '</media:group>'. "\n";
					$rssfeed .= '</item>'. "\n";


		        }
		        // echo "<pre>";
		  		// var_dump($rssfeed);
	 
			    $rssfeed .= '</channel>'. "\n";
				$rssfeed .= '</rss>'. "\n";
	
			    echo $rssfeed;
		  		die;

			}
		}


	    public function get_video_path($vid)
	    {
	        if ($vid > 0)
	        {
	            $v = Video::find($vid);
	            if ($v->source == '0' || $v->source == 'internal') 
		        {
		            $fname = 'http://'.BaseController::get_channel_id().'.1studio.tv.global.prod.fastly.net/'.$v->file_name.'.mp4';
		            return $fname;
		        }
		        return $v->file_name;
	        }
	    }

		public function get_playlist_videos(){
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
	        // dd($videos);
	        // var_dump($videos);die;

	        return $this->render('vod_playlist/playlist_videos');
		}

		public static function get_playlists() {

		    $playlists = Playlist::where("channel_id", "=", BaseController::get_channel_id())->orderBy('id', 'desc')->get();

		    return Time::change_to_human_data_in_array($playlists);
		}



	}



?>

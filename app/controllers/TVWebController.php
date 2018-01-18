<?php

/**
 * Class PlaylistController
 */
class TVWebController extends BaseController {

    public function index() {
        if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
            return App::abort(404);
        }
        
        //set Up Video Site Sections in DB if not set
        $rows = TvwebPlaylist::count();
        if($rows < 1){
	    	$playlist = new TvwebPlaylist;
	    	$playlist->title = 'Watch Live';
	    	$playlist->description = 'Live URLs (m3u8)';
	    	$playlist->channel_id = BaseController::get_channel_id();
	    	$playlist->save();
	    	$playlist = new TvwebPlaylist;
	    	$playlist->title = 'Feature Videos';
	    	$playlist->description = 'Currently used first 4 in playlist';
	    	$playlist->channel_id = BaseController::get_channel_id();
	    	$playlist->save();
        }

        
        return $this->render('tvweb/tvweb_index');
    }
   
    public function tvweb_playlists() {
    	if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
    		return App::abort(404);
    	}
    
    	$playlists = self::get_tvweb_playlists();
    
    	foreach($playlists as $playlist) {
    		$video_ids = TvwebVideo_in_playlist::where('tvweb_playlist_id', '=', $playlist->id)->get();
    
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
    
    	$this->data['tvweb_playlists'] = $playlists;
    
    	return $this->render('tvweb/tvweb_manage_videos');
    }
    
    public function tvweb_settings() {
    	
    	$this->data['tvweb_setting1'] = '';
    	 
    	return $this->render('tvweb/tvweb_settings');
    }
    
    ///////////////////////
        
    public function tvweb_live_update() {
    	 
    	$title = trim(Input::get('tvweb_title'));
    	$description = trim(Input::get('tvweb_description'));
    	$liveStreamURL = trim(Input::get('tvweb_live_stream_url'));
    	
    	
    	
    	$title = str_replace('&','and',$title);
    	$title = str_replace('"','',$title);
    	$title = str_replace("'","",$title);
    	$title = str_replace('\'','',$title);
    	
    	$description = str_replace('&','and',$description);
    	$description = str_replace('"','',$description);
    	$description = str_replace('\'','',$description);
    	
    	
    	$xml = File::get(public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

     	//xml($string)
    	$xml = $this->injectStr($xml, 'title="', '"', $title, 0);
    	$xml = $this->injectStr($xml, 'description="', '"', $description, 0);
    	$xml = $this->injectStr($xml, 'stream_url="', '"', $liveStreamURL, 0);
    	
    	$path = public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml';
    	 
    	File::put($path.'/categories_linear.xml', $xml);
    	
    	//////////////////////
    	
    	$xml2 = File::get(public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/first.xml');
    	
    	//xml($string)
    	$xml2 = $this->injectStr($xml2, 'title="', '"', $title, 0);
    	$xml2 = $this->injectStr($xml2, 'description="', '"', $description, 0);
    	$xml2 = $this->injectStr($xml2, 'stream_url="', '"', $liveStreamURL, 0);
    	 
    	$path2 = public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml';
    	
    	File::put($path2.'/first.xml', $xml2);
    	
    	
    	
    	$this->data['tvweb_title'] = $title;
    	$this->data['tvweb_description'] = $description;
    	$this->data['tvweb_live_stream_url'] = $liveStreamURL;
    	 
    	return $this->render('tvweb/tvweb_live');
    }

    public function tvweb_about_us_update() {
    
    	$tvweb_about_us = trim(Input::get('about_us'));
    	
    	$tvweb_about_us = str_replace('&','and',$tvweb_about_us);
    	$tvweb_about_us = str_replace('"','',$tvweb_about_us);
    	$tvweb_about_us = str_replace('\'','',$tvweb_about_us);
    	
    	$xml = File::get(public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

    	$intend = strpos($xml, 'title="About Us"');
    	$xml = $this->injectStr($xml, 'description="', '"', $tvweb_about_us, $intend);
    	if(strlen($xml)>30){
    		$path = public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml';
    		File::put($path.'/categories_linear.xml', $xml);
    	}
    
    	/////////////////
    	
        $xml2 = File::get(public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/first.xml');
    	$intend2 = strpos($xml2, 'title="About Us"');
    	$xml2 = $this->injectStr($xml2, 'description="', '"', $tvweb_about_us, $intend2);
    	if(strlen($xml2)>30){
    		$path2 = public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml';
    		File::put($path2.'/first.xml', $xml2);
    	}
    	    	
    	$this->data['tvweb_about_us'] = $tvweb_about_us;

    	return $this->render('tvweb/tvweb_about_us');
    }
    
    
    //////////////////////
        
    //set tvweb playlists order using master_looped field (using master_looped in another context with tvwebs)
    public function tvweb_order_playlist() {

    	$count = 1;
    	foreach($_POST['item'] as $tpid) {
    		Log::error("Zencoder: {$tpid}");
    		
    		$status = TvwebPlaylist::where('id', '=', $tpid)->update(array(
    				'master_looped'=> $count
    		));
    		$count++;
    	}
     	
    	return $this->render('tvweb/tvweb_manage_videos');
    }
    
    
    public static function get_tvweb_playlists() {
    	$playlists = TvwebPlaylist::where("channel_id", "=", BaseController::get_channel_id())->orderBy('master_looped', 'asc')->get();
    	//        $playlists = Playlist::all();
    	//        $timeline = Timeline::get(['percentage', 'classColor']);
    	return Time::change_to_human_data_in_array($playlists);
    }

    public static function get_tvweb_play_lists($channel_id) {
    	$playlists = TvwebPlaylist::where("channel_id", "=", $channel_id)->orderBy('master_looped', 'asc')->get();
    	return Time::change_to_human_data_in_array($playlists);
    }
    
    public function tvweb_add_to_playlist() {
    
    	$title = trim(Input::get('title'));
    	$description = trim(Input::get('description'));
    
    	if (empty($title)) {
    
    		return Response::json([
    				'status' => false,
    				'message' => 'Wrong data'.$title
    		], 200);
    		//            return View::make('error')->with(array('message' => 'Wrong data'));
    	}
    
    	$playlist = new TvwebPlaylist;
    	$playlist->title = $title;
    	$playlist->description = $description;
    	$playlist->channel_id = BaseController::get_channel_id(); // This must by changed
    
    	if ($playlist->save()) {
    
    		return Response::json([
    				'status' => true,
    				'tvweb_playlist_id' => $playlist->id
    		], 200);
    	}
    }

    public function tvweb_edit_playlist() {
    	$id = trim(Input::get('id'));
    	$title = trim(Input::get('title'));
    	$description = trim(Input::get('description'));

    	
    	if (empty($id) || empty($title)) {
    		return Response::json([
    				'status' => false,
    				'message' => 'Wrong data'
    		], 200);
    	}
    	
    	$playlist = TvwebPlaylist::find($id);
    	$playlist->title = $title;
    	$playlist->description = $description;
    	if($title == 'Watch Live'){
    		$playlist->thumbnail_name = trim(Input::get('thumbnail_name'));
    	}
    	//        $playlist->status = 0;
    	$playlist->channel_id = BaseController::get_channel_id();
    	
    	if ($playlist->save()) {
    		return Response::json([
    				'status' => true,
    				'playlist_id' => $playlist->id
    		], 200);
    	}
    	
    }
    
    //not used in this version
    public function tvweb_edit_playlist_xml() {
    	$id = trim(Input::get('id'));
    	$title = trim(Input::get('title'));
    	$description = trim(Input::get('description'));
    
    	if (empty($id) || empty($title)) {
    		return Response::json([
    				'status' => false,
    				'message' => 'Wrong data'
    		], 200);
    	}
    
    	$playlist = TvwebPlaylist::find($id);
    	$playlist->title = $title;
    	$playlist->description = $description;
    	//        $playlist->status = 0;
    	$playlist->channel_id = BaseController::get_channel_id();
    
    	
    	/// update categories.xml
    	
    	//$xmlv = File::get(public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/first.xml');
    
    	
    	
    	/// update categories.xml
    	 
    	$xmlv = File::get(public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/categories.xml');
    	
    	//xml($string)
    	$x1 = strpos($xmlv, '<categories>');
    	$x2 = strpos($xmlv, '</categories>');
    	 
    	$xmlv1 = substr($xmlv, 0, $x1+strlen('<categories>'))."\n\n";
    	$xmlv2 = "\n".substr($xmlv, $x2, strlen($xmlv)-$x2);
    	 
    	$xmlv = '';
    	 
    	//$playlists = TvwebPlaylist::all();
    	$playlists = self::get_tvweb_playlists();
    	 
    	 
    	$count = 0;
    	foreach($playlists as $pl) {
    		++$count;
    	}
    	 
    	$channel_id = BaseController::get_channel_id();
    	foreach($playlists as $pl) {
    		$tlt = $this->xtrim($pl->title);
    	
    		$plimg = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'_'.'tvweb_playlist_'.$pl->id;
    	
    		$ttl = $pl->title;
    		$ttl = str_replace("&","and",$ttl);
    		$ttl = str_replace('"','',$ttl);
    		$ttl = str_replace("'","",$ttl);
    		$ttl = str_replace('\'','',$ttl);
    	
    		$desc = $pl->description;
    		$desc = str_replace("&","and",$desc);
    		$desc = str_replace('"','',$desc);
    		$desc = str_replace("'","",$desc);
    		$desc = str_replace('\'','',$desc);
    	
    		$xmlv .=
    		'<category '."\n".
    		'title="'.$ttl.'" '."\n".
    		'description="'.$desc.'" '."\n".
    	
    		'sd_img="'.$plimg.'" '."\n".
    		'hd_img="'.$plimg.'" '."\n".
    		'feed="http://1stud.io/tvweb/channel_'.$channel_id.'/roku/xml/playlists/'.$tlt.'.xml" '."\n".
    		'playlists_count="'.$count.'" '."\n".
    		'/>'."\n";
    	}
    	
    	$xmlv = $xmlv1.$xmlv.$xmlv2;
    	 
    	$path = public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml';
    	 
    	File::put($path.'/categories.xml', $xmlv);
    	 
     	

    	/// update categories_linear.xml
    	
    	$xmlv = File::get(public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');
    	 
    	//xml($string)
    	$x1 = strpos($xmlv, '<!-- content start -->');
    	$x2 = strpos($xmlv, '<!-- content end -->');
    	
    	$xmlv1 = substr($xmlv, 0, $x1+strlen('<!-- content start -->'))."\n\n";
    	$xmlv2 = "\n".substr($xmlv, $x2, strlen($xmlv)-$x2);
    	
    	$xmlv = '';
    	
    	//$playlists = TvwebPlaylist::all();
    	$playlists = self::get_tvweb_playlists();
    	
    	
    	$count = 0;
    	foreach($playlists as $pl) {
    		++$count;
    	}
    	
    	$channel_id = BaseController::get_channel_id();
    	foreach($playlists as $pl) {
    		$tlt = $this->xtrim($pl->title);
    		
    		$plimg = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'_'.'tvweb_playlist_'.$pl->id;
    		
    		$ttl = $pl->title;
    		$ttl = str_replace("&","and",$ttl);
    		$ttl = str_replace('"','',$ttl);
    		$ttl = str_replace("'","",$ttl);
    		$ttl = str_replace('\'','',$ttl);
    		
    		$desc = $pl->description;
    		$desc = str_replace("&","and",$desc);
    		$desc = str_replace('"','',$desc);
    		$desc = str_replace("'","",$desc);
    		$desc = str_replace('\'','',$desc);
    		
    		$xmlv .=
    		'<category '."\n".
    		'title="'.$ttl.'" '."\n".
    		'description="'.$desc.'" '."\n".
    		
    		'sd_img="'.$plimg.'" '."\n".
    		'hd_img="'.$plimg.'" '."\n".
    		'feed="http://1stud.io/tvweb/channel_'.$channel_id.'/roku/xml/playlists/'.$tlt.'.xml" '."\n".
    		'playlists_count="'.$count.'" '."\n".
    		'/>'."\n";
    	}

    	$xmlv = $xmlv1.$xmlv.$xmlv2;
    	
    	$path = public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml';
    	
    	File::put($path.'/categories_linear.xml', $xmlv);
    	

    	
    	
    	
    	
    	
    	///update tvweb xml files
    	
    	$video_ids = TvwebVideo_in_playlist::where('tvweb_playlist_id', '=', $id)->get();
    	
    	$xmlv = 
    	'<?xml version="1.0" encoding="UTF-8"?>'."\n".
        '<feed>'."\n";
        //'<resultLength>1</resultLength>'."\n".
        //'<endIndex>1</endIndex>'."\n";
    	
    	foreach($video_ids as $video_id) {
    		$video = Video::find($video_id['video_id']);
    		
    		//if (empty($video)) continue;
    		if (!$video) continue;
    		
//     		$videos = Video::where('id', '=', $video_id)->get();
//     		foreach ($videos as $video) {
//     			//here could be only one record
//     			$channel_id = $video->channel_id;
//     		}

    		//$fname = 'https://s3.amazonaws.com/aceplayout/'.$video->file_name.'.mp4';
    		$fname = 'http://'.BaseController::get_channel_id().'.1studio.tv.global.prod.fastly.net/'.$video->file_name.'.mp4';
    		//http://35.1studio.tv.global.prod.fastly.net/0086a1fd4d43c5a01e501c785acaf147.mp4
    		$ttl = $video->title;
    		$ttl = str_replace("&","and",$ttl);
    		//$ttl = str_replace('"','\"',$ttl);
    		//$ttl = str_replace("'","\'",$ttl);
    		$desc = $video->description;
    		$desc = str_replace("&","and",$desc);
    		
    		//hd w=266&h=150
    		//sd w=138&h=77
    		
    		$sdimg = str_replace('https://s3.amazonaws.com/aceplayout/','https://onestudio.imgix.net/',$video->thumbnail_name);
    		$sdimg .= '?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60';
    		//$sdimg .= '?w=138&h=77&fit=crop&crop=entropy&auto=format,enhance&q=40';
    		//$sdimg = 'https://goo.gl/3CbmY2';
    		
    		///// alternative: http://dev.bitly.com/ 
    		//https://www.youtube.com/watch?v=zMXsK6hMlbw
    		//http://code.google.com/apis/console
    		//google shortener key:AIzaSyC_ukHQPxEHSXKhJuf42NtJqwHAyOoMDRw
    		    
    		
    		$longUrl = $sdimg;
    		$apiKey = 'AIzaSyC_ukHQPxEHSXKhJuf42NtJqwHAyOoMDRw';
    		// You can get API key here : Login to google and
    		// go to http://code.google.com/apis/console/
    		// Find API key under credentials under APIs & auth.
    		// You will need to do necessary things to get key there. :)
    		// Watch video below.
    		
    		// *** No need to modify any of the code line below. ***
    		$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
    		$jsonData = json_encode($postData);
    		$curlObj = curl_init();
    		curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
    		curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
    		curl_setopt($curlObj, CURLOPT_HEADER, 0);
    		curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    		curl_setopt($curlObj, CURLOPT_POST, 1);
    		curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
    		$response = curl_exec($curlObj);
    		$json = json_decode($response);
    		
    		curl_close($curlObj);
    		//echo 'Shortened URL ->'.$json->id;
    		$sdimg = $json->id;
    		/////
    		
    		
			$xmlv .= 
			'<item sdImg="'.$sdimg.'" hdImg="'.$sdimg.'">'."\n".
			'<title>'.$ttl.'</title>'."\n".
			'<description>'.$desc.'</description>'."\n".
			'<contentType>Talk</contentType>'."\n".
			'<contentId>1</contentId>'."\n".
			'<media>'."\n".
			'<streamFormat>mp4</streamFormat>'."\n".
			'<streamQuality>SD</streamQuality>'."\n".
			'<streamUrl>'.$fname.'</streamUrl>'."\n".
			'</media>'."\n".
			'<media>'."\n".
			'<streamFormat>mp4</streamFormat>'."\n".
			'<streamQuality>HD</streamQuality>'."\n".
			'<streamUrl>'.$fname.'</streamUrl>'."\n".
			'</media>'."\n".
			'<synopsis>This is the 1st video.</synopsis>'."\n".
			'<genres>Clip</genres>'."\n".
			'<runlength>'.$video->duration.'</runlength>'."\n".
			'<starrating>75</starrating>'."\n".
			'<Rating>NR</Rating>'."\n".
			'</item>'."\n\n";
    		
    	}
    	
    	$xmlv .= '</feed>'."\n";
    	
    	$path = public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/playlists';
    	//$result = File::makeDirectory($path, 0777,true);
    	
    	$title = $this->xtrim($title);
    	
    	File::put($path.'/'.$title.'.xml', $xmlv);
    	
    	//return Response::make($content, 200)->header('Content-Type', 'application/xml');
    	    	
    	////////////////////////
    	
    	
    	if ($playlist->save()) {
    		return Response::json([
    				'status' => true,
    				'playlist_id' => $playlist->id
    		], 200);
    	}
    }
    
    public function xtrim($title) {
    	
    	$title = str_replace("~", "", $title);
    	$title = str_replace("`", "", $title);
    	$title = str_replace("!", "", $title);
    	$title = str_replace("@", "", $title);
    	$title = str_replace("#", "", $title);
    	$title = str_replace("$", "", $title);
    	$title = str_replace("%", "", $title);
    	$title = str_replace("^", "", $title);
    	$title = str_replace("&", "", $title);
    	$title = str_replace("*", "", $title);
    	$title = str_replace("(", "", $title);
    	$title = str_replace(")", "", $title);
    	$title = str_replace("-", "", $title);
    	$title = str_replace("+", "", $title);
    	$title = str_replace("=", "", $title);
    	$title = str_replace("{", "", $title);
    	$title = str_replace("}", "", $title);
    	$title = str_replace("[", "", $title);
    	$title = str_replace("]", "", $title);
    	$title = str_replace("|", "", $title);
    	$title = str_replace("\\", "", $title);
    	$title = str_replace(":", "", $title);
    	$title = str_replace(";", "", $title);
    	$title = str_replace("'", "", $title);
    	$title = str_replace("\"", "", $title);
    	$title = str_replace("?", "", $title);
    	$title = str_replace("/", "", $title);
    	$title = str_replace(">", "", $title);
    	$title = str_replace("<", "", $title);
    	$title = str_replace(".", "", $title);
    	$title = str_replace(",", "", $title);
    	 
    	$title = str_replace(" ", "_", $title);
    	
    	return $title;
    	
    }
    
    public function tvweb_get_playlist_by_id() {
    	$id = trim(Input::get('id'));
    	$playlist = TvwebPlaylist::find($id);
    
    	$video_ids = TvwebVideo_in_playlist::where('tvweb_playlist_id', '=', (int) $playlist->id)->get();
    
    	$videos = [];
    
    	foreach ($video_ids as $video_id) {
    
    		$video = Video::find($video_id['video_id']);
    
    		if (!$video) continue;
    
    		$video->time = Time::change_to_human_data_in_object($video);
    
    		//$video = Time::change_to_human_data_in_array([$video->toArray()]);
    		$videos[] = $video;
    	}
    
    	
   	
    	$channel_id = BaseController::get_channel_id();
    	$channel = Channel::find($channel_id);
    	
    	
    	$playlist->time = Time::change_to_human_data_in_object($playlist);
    
    	return View::make('tvweb/tvweb_edit_playlist')->with('playlist', $playlist)->with('videos', $videos)->with('channel', $channel);
    	//->with('title', $channel['title'])->with('timestamp', $channel['timestamp'])->with('status', $channel['timestamp']);
    }
    
    public function tvweb_insert_video_in_playlist() {
    
    	$playlist = Input::get('playlist');
    
    	//var_dump($playlist); die();
    	if (is_array($playlist)) {
    
    		$insert = array();
    
    		$duration = 0;
    
    		$thumbnail_name = '';
    		if(isset($playlist['playlists'])) {
    			foreach($playlist['playlists'] as $video_id) {
    
    				$videos = Video::find((int)$video_id);
    
    				$duration += (int)$videos->duration;
    
    				$insert[] = array(
    						'tvweb_playlist_id' => $playlist['playlist_id'],
    						'video_id' => $video_id
    				);
    
    				$thumbnail_name = $videos->thumbnail_name;
    			}
    		} else {
    			return Response::json([
    					'status' => true
    			], 200);
    		}
    
    		$playlist = TvwebPlaylist::find($playlist['playlist_id']);
    		$playlist->duration = $duration;
    		$playlist->thumbnail_name = $thumbnail_name;
    
    		$videos_in_playlists = TvwebVideo_in_playlist::where('tvweb_playlist_id', '=', $playlist['id'])->get();
    
    		foreach($videos_in_playlists as $videos_in_playlist) {
    			$videos_in_playlist->delete();
    		}
    
    		TvwebVideo_in_playlist::Insert($insert);
    		$playlist->save();
    
    		return Response::json([
    				'status' => true
    		], 200);
    	} else {
    		return Response::json([
    				'status' => false
    		], 200);
    	}
    }
    
    public function tvweb_delete_playlist() {
    	$tvweb_playlist_id = Input::get('tvweb_playlistId');
    	$tvwebl = TvwebPlaylist::find($tvweb_playlist_id);
    	TvwebPlaylist::find($tvweb_playlist_id)->delete();
    	//Schedule::where('tvweb_playlist_id', '=', $playlist_id)->delete();
    	TvwebVideo_in_playlist::where('tvweb_playlist_id', '=', $tvweb_playlist_id)->delete();
    	File::delete(public_path().'/tvweb/channel_'.BaseController::get_channel_id().'/roku/xml/playlists/'.$this->xtrim($tvwebl->title).'.xml');
    	return Response::json([
    			'status' => true
    	], 200);
    }
    
    //    public function master_loop() {
    //        $id = trim(Input::get('id'));
    //        $playlist = Playlist::find($id);
    //
    //        $videos_in_playlist = Video_in_playlist::where('playlist_id', '=', $id)->get();
    //
    //        $dveo = BaseController::get_dveo();
    //
    //        if($playlist->type != 2) {
    //            $allPlaylists = Playlist::where('channel_id', '=', BaseController::get_channel_id())->get();
    //
    //            foreach($allPlaylists as $one) {
    //                $one->master_looped = 0;
    //                $one->type = 0;
    //                $one->save();
    //            }
    //
    //            ###
    //            $playlistvideos = [];
    //            foreach ($videos_in_playlist as $video_in_playlist) {
    //                $video = Video::find($video_in_playlist['video_id']);
    //                $playlistvideos[] = $video['file_name'] . "." . $video['video_format'];
    //                //$playlistvideos[] .= time() . " " . $video['file_name'] . "." . $video['video_format'] . "\n";
    //            }
    //
    //            // Change playlist on DVEO
    //            //$dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());
    //
    //            // Creating a new DVEO instance
    //            //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');
    //            $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');
    //            $dveo->schedule_videos(BaseController::get_channel_id(),  $playlistvideos);
    //            ###
    //
    //            $dveo->restart_stream(BaseController::get_channel()->stream);
    //
    //            $playlist->master_looped = Carbon::now()->getTimestamp();
    //            $playlist->type = 2;
    //            $playlist->save();
    //
    //            return Response::json([
    //                'status' => true,
    //                'loopOff' => true
    //            ], 200);
    //        } else {
    //            $playlist->master_looped = 0;
    //            $playlist->type = 0;
    //            $playlist->save();
    //
    //            $dveo->stop_stream(BaseController::get_channel()->stream);
    //
    //            return Response::json([
    //                'status' => true,
    //                'loopOff' => false
    //            ], 200);
    //        }
    //    }
    
    //not used in TV
    public function tvweb_update_files() {
    	$id = trim(Input::get('id'));
    	$playlist = TvwebPlaylist::find($id);
    
    	$videos_in_playlist = TvwebVideo_in_playlist::where('playlist_id', '=', $id)->get();
    
    	$dveo = BaseController::get_dveo();
    
    	if($playlist->type != 2) {
    		$allPlaylists = TvwebPlaylist::where('channel_id', '=', BaseController::get_channel_id())->get();
    
    		foreach($allPlaylists as $one) {
    			$one->master_looped = 0;
    			$one->type = 0;
    			$one->save();
    		}
    
    		// Change playlist on DVEO
    		$dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());
    		$dveo->restart_stream(BaseController::get_channel()->stream);
    
    		$playlist->master_looped = Carbon::now()->getTimestamp();
    		$playlist->type = 2;
    		$playlist->save();
    
    		return Response::json([
    				'status' => true,
    				'loopOff' => true
    		], 200);
    	} else {
    		$playlist->master_looped = 0;
    		$playlist->type = 0;
    		$playlist->save();
    
    		$dveo->stop_stream(BaseController::get_channel()->stream);
    
    		return Response::json([
    				'status' => true,
    				'loopOff' => false
    		], 200);
    	}
    }
    
    /// ROUTINES:
    
    private function injectStr($str, $token1, $token2, $target, $intend){
    	$x1_len = strlen($token1);
    	$x1 = strpos($str, $token1, $intend);
    	if($x1>0){
    		$x2 = strpos($str, $token2, $x1+$x1_len);
    		if($x2>$x1){
    			$xml1 = substr($str, 0, $x1+$x1_len);
    			$xml2 = substr($str, $x2);
    			$str = $xml1.$target.$xml2;
    		}
    	}
    	return $str;
    }
    
    private function getSubstring($str, $token1, $token2, $intend){
    	$x1_len = strlen($token1);
    	$x1 = strpos($str, $token1, $intend);
    	if($x1>0){
    		$x2 = strpos($str, $token2, $x1+$x1_len);
    		if($x2>$x1){
    			$str = substr($str, $x1+$x1_len, $x2-($x1+$x1_len));
    		}
    	}
    	return $str;
    }
    
    
    ///REST UTILS
    
    public function XMLFiletoJSONparse ($url) {
    	$fileContents= file_get_contents($url);
    	$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    	$fileContents = trim(str_replace('"', "'", $fileContents));
    	
    	return new SimpleXMLElement($fileContents);
    	    	
    	$simpleXml = simplexml_load_string($fileContents);
    	$json = json_encode($simpleXml);
    	//$array = json_decode($json,TRUE);
    	return $json;
    }

    public function XMLtoJSONparse ($fileContents) {
    	$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    	$fileContents = trim(str_replace('"', "'", $fileContents));
    	 
    	return new SimpleXMLElement($fileContents);

    }
    
    
    ///TVWEB REST

    public function tvweb_get_section_updated_at() {
    	$key = trim(Input::get('key'));
    	$channel_id = trim(Input::get('channel_id'));
    	$section_title = trim(Input::get('section_title'));
    	
    	$playlists = TvwebPlaylist::where('channel_id', '=', $channel_id)
    	                            ->where('title', '=', $section_title)
    	                            ->get();
    	 
    	$json = json_encode($playlists[0]->updated_at);
    	return Response::json($json, 200);
    }
    
    
    public function tvweb_get_section() {
    	$key = trim(Input::get('key'));
    	$channel_id = trim(Input::get('channel_id'));
    	$section_title = trim(Input::get('section_title'));
    	
    	//key to check: sdf88po234pl3zxPP9
    	
    	//if($section_title == 'Watch Live')
    	//$playlists = TvwebPlaylist::where('channel_id', '=', $channel_id)->
    	//                               where('title', '=', $section_title)->get();
    	$playlists = DB::table('tvweb_playlist')->where('channel_id', '=', $channel_id)
    	                                         ->where('title', '=', $section_title)
    	                                         ->get();
    	
    	$json = json_encode($playlists[0]);
    	return Response::json($json, 200);
    }
    
    public function tvweb_get_playlists() {
    	$key = trim(Input::get('key'));
    	$channel_id = trim(Input::get('channel_id'));
    	 
    	//key to check: sdf88po234pl3zxPP9
    	 
    	$playlists = DB::table('tvweb_playlist')->where('channel_id', '=', $channel_id)
    	                                        ->where('type', '=', '0')
    	                                        ->get();
    	
    	$json = json_encode($playlists);
    	return Response::json($json, 200);
    }
    
    public function tvweb_get_videos() {
    	$key = trim(Input::get('key'));
    	$channel_id = trim(Input::get('channel_id'));
    	$playlist_title = trim(Input::get('playlist_title'));
    	$tvweb_playlist_id = trim(Input::get('tvweb_playlist_id'));
    
    	$playlists = DB::table('tvweb_playlist')->where('channel_id', '=', $channel_id)
    	                                        ->where('title', '=', $playlist_title)
    	                                        ->get();
    	 
    	$ar = array(); 
    	
    	foreach($playlists as $pl) {
    		$id = $pl->id;
    		$ttl = $pl->title;
    		$desc = $pl->description;
    		
    		$video_ids = TvwebVideo_in_playlist::where('tvweb_playlist_id', '=', $id)->get();
    		 
    		foreach($video_ids as $video_id) {
    			$video = Video::find($video_id['video_id']);
    			
    			
    			if (!$video) continue;

    			$fname = 'http://'.$channel_id.'.1studio.tv.global.prod.fastly.net/'.$video->file_name.'.mp4';
    			$ttl = $video->title;
    			$desc = $video->description;
    			
    			//$sdimg = str_replace('https://s3.amazonaws.com/aceplayout/','https://onestudio.imgix.net/',$video->thumbnail_name);
    			//$sdimg .= '?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60';
    			//$sdimg .= '?w=640&h=360&fit=crop&crop=entropy&auto=format,enhance&q=60';
    			//$sdimg .= '?w=670&h=406&fit=crop&crop=entropy&auto=format,enhance&q=60';
    				
    			//since 1stud db updated already with imgix
    			$sdimg = $video->thumbnail_name;
    			
    			$longUrl = $sdimg;
    			$apiKey = 'AIzaSyC_ukHQPxEHSXKhJuf42NtJqwHAyOoMDRw';
    			
    			// *** No need to modify any of the code line below. ***
    			$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
    			$jsonData = json_encode($postData);
    			$curlObj = curl_init();
    			curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
    			curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
    			curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
    			curl_setopt($curlObj, CURLOPT_HEADER, 0);
    			curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    			curl_setopt($curlObj, CURLOPT_POST, 1);
    			curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
    			$response = curl_exec($curlObj);
    			$json = json_decode($response);
    			
    			curl_close($curlObj);
    			//echo 'Shortened URL ->'.$json->id;
    			$sdimg = $json->id;
    			/////
    			
    			$ar[] = array($playlist_title => array('tvweb_playlist_id'=>$tvweb_playlist_id,
    					                          'title'=>$ttl, 
    					                          'description'=>$desc,
    					                          'url'=>$fname,
    					                          'duration'=>$video->duration,
    					                          'img'=>$sdimg, 
    					                          ));
    			
    		}
    		
    	}
    	$json = json_encode($ar);
    	//Log::info();
    	 
    	return Response::json($json, 200);
    }
    
    public function tvweb_get_categories_xml() {
    	$key = trim(Input::get('key'));
    	$channel_id = trim(Input::get('channel_id'));

    	
    	$xmlv1 = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
    	         '<categories>'."\n\n";
    	$xmlv2 = '</categories>'."\n";
    	
    	$xmlv = '';
    	
    	$playlists = self::get_tvweb_play_lists($channel_id);
    	
    	
    	$count = 0;
    	foreach($playlists as $pl) {
    		++$count;
    	}
    	
    	foreach($playlists as $pl) {
    		$tlt = $this->xtrim($pl->title);
    		 
    		$plimg = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'_'.'tvweb_playlist_'.$pl->id;
    		 
    		$ttl = $pl->title;
    		$ttl = str_replace("&","and",$ttl);
    		$ttl = str_replace('"','',$ttl);
    		$ttl = str_replace("'","",$ttl);
    		$ttl = str_replace('\'','',$ttl);
    		 
    		$desc = $pl->description;
    		$desc = str_replace("&","and",$desc);
    		$desc = str_replace('"','',$desc);
    		$desc = str_replace("'","",$desc);
    		$desc = str_replace('\'','',$desc);
    		 
    		$xmlv .=
    		'<category '."\n".
    		'title="'.$ttl.'" '."\n".
    		'description="'.$desc.'" '."\n".
    		 
    		'sd_img="'.$plimg.'" '."\n".
    		'hd_img="'.$plimg.'" '."\n".
    		'feed="http://1stud.io/tvweb/channel_'.$channel_id.'/roku/xml/playlists/'.$tlt.'.xml" '."\n".
    		'playlists_count="'.$count.'" '."\n".
    		'/>'."\n";
    	}
                
    	$xmlv = $xmlv1.$xmlv.$xmlv2;
    	Log::info($xmlv);
    	$json = $this->XMLtoJSONparse($xmlv);
    	
    	return Response::json($json, 200);
    }
    
    
}


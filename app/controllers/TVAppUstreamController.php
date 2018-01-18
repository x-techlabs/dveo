<?php

/**
 * Class PlaylistController
 */
class TVAppUstreamController extends BaseController {

    public function index() {
        if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
            return App::abort(404);
        }
        return $this->render('tvapp/tvapp_index');
    }
   
    public function tvapp_live() {
    	
    	$xml = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');
    	
    	$this->data['tvapp_title'] = $this->getSubstring($xml, 'title="', '"', 0);
    	$this->data['tvapp_description'] = $this->getSubstring($xml, 'description="', '"', 0);
    	$this->data['tvapp_live_stream_url'] = $this->getSubstring($xml, 'stream_url="', '"', 0);
    	
    	return $this->render('tvapp/tvapp_live');
    }
    
    
// http://dev2.hexagonsoftware.com/roku/ustream/ustream_interface.php?action=channelInfo&cid=20488137
// http://dev2.hexagonsoftware.com/roku/ustream/ustream_interface.php?action=channelVideos&cid=20488137
    
//     [8/29/16, 11:06:46 PM] Vinay Chitale: vidArray = xml.videos[0].array
    
//     kids = createObject("roArray", 1, true)
//     for t = 0 to vidArray.count()-1
    
//     item = init_show_feed_item()
//     curShow = vidArray[t]
    
//     item.ContentId        = validstr(curShow.id.GetText())
//     item.Title            = validstr(curShow.title.GetText())
//     item.Description      = validstr(curShow.description.GetText())
//     item.hdImg            = validstr(curShow.thumbnail[0].default.getText())
//     item.sdImg            = validstr(curShow.thumbnail[0].default.getText())
//     item.Runtime          = validstr(curShow.length.GetText())
    
//     item.ContentType      = ""
//     		item.ContentQuality   = ""
//     				item.Synopsis         = ""
//     						item.Genre            = ""
//     								item.HDBifUrl         = ""
//     										item.SDBifUrl         = ""
//     												item.StreamFormat     = ""
//     														if item.StreamFormat = "" then  'set default streamFormat to mp4 if doesn't exist in xml
//     														item.StreamFormat = "mp4"
//     																endif
    
//     																item.StarRating    = ""
//     																		item.Rating        = ""
    
//     																				'map xml attributes into screen specific variables
//         item.ShortDescriptionLine1 = item.Title
//         item.ShortDescriptionLine2 = item.Description
//         item.HDPosterUrl           = item.hdImg
//         item.SDPosterUrl           = item.sdImg
    
//         item.Length = strtoi(item.Runtime)
//         item.Categories = CreateObject("roArray", 5, true)
//         item.Actors = CreateObject("roArray", 5, true)
//         item.Description = item.Synopsis
    
//         'Set Default screen values for items not in feed
//             item.HDBranded = false
//             item.IsHD = false
//             item.StarRating = "90"
//             		item.ContentType = "episode"
    
//             				mu = curShow.media_urls
//             				'media may be at multiple bitrates, so parse an build arrays
//         for idx = 0 to mu.count()-1
//             e = mu[idx]
//             if e  <> invalid then
//                 item.StreamBitrates.Push("1000")
//                 item.StreamQualities.Push("SD")
//                 item.StreamUrls.Push(validstr(e.flv.GetText()))
//             endif
//         next idx
    
//         kids.Push(item)
//     next
// [8/29/16, 11:07:05 PM] Vinay Chitale: This reads contents under channel
// [8/29/16, 11:07:44 PM] Vinay Chitale: This reads channel
//     o = init_category_item()
//     o.Id = xml.channel[0].id.getText()
//     o.Type = "normal"
//     o.Title = xml.channel[0].title.getText()
//     o.Description = xml.channel[0].Description.getText()
//     o.ShortDescriptionLine1 = xml.channel[0].Title.getText()
//     o.ShortDescriptionLine2 = xml.channel[0].Description.getText()
//     o.Description = xml.channel[0].Description.getText()
    
//     o.SDPosterURL = xml.channel[0].thumbnail[0].live.getText()
//     o.HDPosterURL = o.SDPosterURL
//     o.Feed = ""
//     o.StreamUrl = ""
//     o.playlists_count = ""
//     o.layout = "linear"
//     o.kids = []
//     'o.kids = UStream_GetChannelVideos(o.Id)
//         return o
    
    
    
    
    
    
    public function tvapp_playlists() {
    	if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
    		return App::abort(404);
    	}
    
    	$playlists = self::get_tvapp_playlists();
    
    	foreach($playlists as $playlist) {
    		$video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $playlist->id)->get();
    
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
    
    	$this->data['tvapp_playlists'] = $playlists;
    
    	return $this->render('tvapp/tvapp_manage_videos');
    }
    
    public function tvapp_about_us() {
    	
    	$xml = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');
    	
    	$intend = strpos($xml, 'title="About Us"');
    	$this->data['tvapp_about_us'] = $this->getSubstring($xml, 'description="', '"', $intend);
    	 
    	return $this->render('tvapp/tvapp_about_us');
    }
    
    ///////////////////////
        
    public function tvapp_live_update() {
    	 
    	$title = trim(Input::get('tvapp_title'));
    	$description = trim(Input::get('tvapp_description'));
    	$liveStreamURL = trim(Input::get('tvapp_live_stream_url'));
    	
    	
    	
    	$title = str_replace('&','and',$title);
    	$title = str_replace('"','',$title);
    	$title = str_replace("'","",$title);
    	$title = str_replace('\'','',$title);
    	
    	$description = str_replace('&','and',$description);
    	$description = str_replace('"','',$description);
    	$description = str_replace('\'','',$description);
    	
    	
    	//&lt; represents "<"
    	//&gt; represents ">"
    	//&amp; represents "&"
    	//&apos; represents '
        //&quot; represents "
    	$liveStreamURL = str_replace('&','&amp;',$liveStreamURL);
    	//$liveStreamURL = str_replace('&','&#038;',$liveStreamURL);
    	
    	
    	
    	$xml = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

     	//xml($string)
    	$xml = $this->injectStr($xml, 'title="', '"', $title, 0);
    	$xml = $this->injectStr($xml, 'description="', '"', $description, 0);
    	$xml = $this->injectStr($xml, 'stream_url="', '"', $liveStreamURL, 0);
    	
    	$path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';
    	 
    	File::put($path.'/categories_linear.xml', $xml);
    	
    	//////////////////////
    	
    	$xml2 = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/first.xml');
    	
    	//xml($string)
    	$xml2 = $this->injectStr($xml2, 'title="', '"', $title, 0);
    	$xml2 = $this->injectStr($xml2, 'description="', '"', $description, 0);
    	$xml2 = $this->injectStr($xml2, 'stream_url="', '"', $liveStreamURL, 0);
    	 
    	$path2 = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';
    	
    	File::put($path2.'/first.xml', $xml2);
    	
    	    	
    	$this->data['tvapp_title'] = $title;
    	$this->data['tvapp_description'] = $description;
    	$this->data['tvapp_live_stream_url'] = $liveStreamURL;
    	
    	$channel_id = BaseController::get_channel_id();
    	
    	
    	
    	$liveInfo = Live_info::where('channel_id', '=', $channel_id)->get();
    	if(count($liveInfo)==0){
	    	$liveInfo = new Live_info;
	    	$liveInfo->channel_id = BaseController::get_channel_id();
	    	$liveInfo->title = $title;
	    	$liveInfo->description = $description;
	    	$liveInfo->live_url = $liveStreamURL;
	    	$liveInfo->details= '';
	    	$liveInfo->save();
    	}else{
    		$status = Live_info::where('channel_id', '=', $channel_id)->update(array(
    				'title'=>$title,
    				'description'=>$description,
    				'live_url'=>$liveStreamURL,
    				'details'=>''
    		));
    	}
    	
    	
    	return $this->render('tvapp/tvapp_live');
    }

    public function tvapp_about_us_update() {
    
    	$tvapp_about_us = trim(Input::get('about_us'));
    	
    	$tvapp_about_us = str_replace('&','and',$tvapp_about_us);
    	$tvapp_about_us = str_replace('"','',$tvapp_about_us);
    	$tvapp_about_us = str_replace('\'','',$tvapp_about_us);
    	
    	$xml = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

    	$intend = strpos($xml, 'title="About Us"');
    	$xml = $this->injectStr($xml, 'description="', '"', $tvapp_about_us, $intend);
    	if(strlen($xml)>30){
    		$path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';
    		File::put($path.'/categories_linear.xml', $xml);
    	}
    
    	/////////////////
    	
        $xml2 = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/first.xml');
    	$intend2 = strpos($xml2, 'title="About Us"');
    	$xml2 = $this->injectStr($xml2, 'description="', '"', $tvapp_about_us, $intend2);
    	if(strlen($xml2)>30){
    		$path2 = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';
    		File::put($path2.'/first.xml', $xml2);
    	}
    	    	
    	$this->data['tvapp_about_us'] = $tvapp_about_us;

    	return $this->render('tvapp/tvapp_about_us');
    }
    
    
    //////////////////////
        
    //set tvapp playlists order using master_looped field (using master_looped in another context with tvapps)
    public function tvapp_order_playlist() {

    	$count = 1;
    	foreach($_POST['item'] as $tpid) {
    		Log::error("Zencoder: {$tpid}");
    		
    		$status = TvappPlaylist::where('id', '=', $tpid)->update(array(
    				'master_looped'=> $count
    		));
    		$count++;
    	}
     	
    	return $this->render('tvapp/tvapp_manage_videos');
    }
    
    
    public static function get_tvapp_playlists() {
    	
    	$playlists = TvappPlaylist::where("channel_id", "=", BaseController::get_channel_id())->orderBy('master_looped', 'asc')->get();
    	//        $playlists = Playlist::all();
    	//        $timeline = Timeline::get(['percentage', 'classColor']);
    
    	return Time::change_to_human_data_in_array($playlists);
    }
    
    public function tvapp_add_to_playlist() {
    
    	$title = trim(Input::get('title'));
    	$description = trim(Input::get('description'));
    	$type = trim(Input::get('type'));
    	
    	if (empty($title)) {
    
    		return Response::json([
    				'status' => false,
    				'message' => 'Wrong data'.$title
    		], 200);
    		//            return View::make('error')->with(array('message' => 'Wrong data'));
    	}
    
    	$playlist = new TvappPlaylist;
    	$playlist->title = $title;
    	$playlist->description = $description;
    	$playlist->type = $type;
    	$playlist->channel_id = BaseController::get_channel_id(); // This must by changed
    
    	if ($playlist->save()) {
    
    		return Response::json([
    				'status' => true,
    				'tvapp_playlist_id' => $playlist->id
    		], 200);
    	}
    }
    
    
    public function tvapp_edit_playlist() {
    	$id = trim(Input::get('id'));
    	$title = trim(Input::get('title'));
    	$description = trim(Input::get('description'));
    	$type = trim(Input::get('type'));
    	
    	if (empty($id) || empty($title)) {
    		return Response::json([
    				'status' => false,
    				'message' => 'Wrong data'
    		], 200);
    	}
    
    	$playlist = TvappPlaylist::find($id);
    	$playlist->title = $title;
    	$playlist->description = $description;
    	$playlist->type = $type;
    	//        $playlist->status = 0;
    	$playlist->channel_id = BaseController::get_channel_id();
    
    	
    	/// update categories.xml
    	
 	
    	
    	/// update categories.xml
    	 
    	$xmlv = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories.xml');
    	
    	//xml($string)
    	$x1 = strpos($xmlv, '<categories>');
    	$x2 = strpos($xmlv, '</categories>');
    	 
    	$xmlv1 = substr($xmlv, 0, $x1+strlen('<categories>'))."\n\n";
    	$xmlv2 = "\n".substr($xmlv, $x2, strlen($xmlv)-$x2);
    	 
    	$xmlv = '';
    	 
    	//$playlists = TvappPlaylist::all();
    	$playlists = self::get_tvapp_playlists();
    	 
    	 
    	$count = 0;
    	foreach($playlists as $pl) {
    		++$count;
    	}
    	 
    	$channel_id = BaseController::get_channel_id();
    	foreach($playlists as $pl) {
    		$tlt = $this->xtrim($pl->title);
    	
    		$plimg = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'_'.'tvapp_playlist_'.$pl->id;
    	
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
    		'feed="http://1stud.io/tvapp/channel_'.$channel_id.'/roku/xml/playlists/'.$tlt.'.xml" '."\n".
    		'playlists_count="'.$count.'" '."\n".
    		'/>'."\n";
    	}
    	
    	$xmlv = $xmlv1.$xmlv.$xmlv2;
    	 
    	$path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';
    	 
    	File::put($path.'/categories.xml', $xmlv);
    	 
    	 
    	
    	
    	    	
    	

    	/// update categories_linear.xml
    	
    	$xmlv = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');
    	 
    	//xml($string)
    	$x1 = strpos($xmlv, '<!-- content start -->');
    	$x2 = strpos($xmlv, '<!-- content end -->');
    	
    	$xmlv1 = substr($xmlv, 0, $x1+strlen('<!-- content start -->'))."\n\n";
    	$xmlv2 = "\n".substr($xmlv, $x2, strlen($xmlv)-$x2);
    	
    	$xmlv = '';
    	
    	//$playlists = TvappPlaylist::all();
    	$playlists = self::get_tvapp_playlists();
    	
    	
    	$count = 0;
    	foreach($playlists as $pl) {
    		++$count;
    	}
    	
    	$channel_id = BaseController::get_channel_id();
    	foreach($playlists as $pl) {
    		$tlt = $this->xtrim($pl->title);
    		
    		$plimg = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'_'.'tvapp_playlist_'.$pl->id;
    		
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
    		'feed="http://1stud.io/tvapp/channel_'.$channel_id.'/roku/xml/playlists/'.$tlt.'.xml" '."\n".
    		'playlists_count="'.$count.'" '."\n".
    		'/>'."\n";
    	}

    	$xmlv = $xmlv1.$xmlv.$xmlv2;
    	
    	$path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';
    	
    	File::put($path.'/categories_linear.xml', $xmlv);
    	

    	
    	
    	
    	
    	
    	///update tvapp xml files
    	
    	$video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)->get();
    	
    	$xmlv = 
    	'<?xml version="1.0" encoding="UTF-8"?>'."\n".
        '<feed>'."\n";
        //'<resultLength>1</resultLength>'."\n".
        //'<endIndex>1</endIndex>'."\n";
    	
    	foreach($video_ids as $video_id) {
    		$video = Video::find($video_id['video_id']);
    		
    		//if (empty($video)) continue;
    		//if (!$video) continue;
    		
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
    		
    		//$sdimg = str_replace('https://s3.amazonaws.com/aceplayout/','https://onestudio.imgix.net/',$video->thumbnail_name);
    		//$sdimg .= '?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60';
    		//$sdimg .= '?w=138&h=77&fit=crop&crop=entropy&auto=format,enhance&q=40';
    		//$sdimg = 'https://goo.gl/3CbmY2';
    		
    		//since 1stud db updated already with imgix
    		//hd 385x218
    		//sd 285x145
    		
    		$sdimg = $video->thumbnail_name;
    		
    		$hd_img = 'https://onestudio.imgix.net/'.$video->file_name.'_1.jpg?'.
    		          'w=385&h=218&fit=crop&crop=entropy&auto=format,enhance&q=60';
    		$sd_img = 'https://onestudio.imgix.net/'.$video->file_name.'_1.jpg?'.
    				  'w=285&h=145&fit=crop&crop=entropy&auto=format,enhance&q=60';
    		
    		$hd_img = htmlspecialchars($hd_img, ENT_XML1, 'UTF-8');
    		$sd_img = htmlspecialchars($sd_img, ENT_XML1, 'UTF-8');
    		///// alternative: http://dev.bitly.com/ 
    		//https://www.youtube.com/watch?v=zMXsK6hMlbw
    		//http://code.google.com/apis/console
    		//google shortener key:AIzaSyC_ukHQPxEHSXKhJuf42NtJqwHAyOoMDRw
    		    
//     		// google url shortening
//     		$json = $this->get_short($hd_img);
//     		//requests per 100 seconds per user	100
//     		if(!isset($json)){
//     			sleep(1);
//     			$json = $this->get_short($hd_img);
//     		}
//     		if(!isset($json)){
//     			sleep(1);
//     			$json = $this->get_short($hd_img);
//     		}
//     		$hd_img = $json->id;
//     		////////////////////////
    		
//     		// google url shortening
//     		$json = $this->get_short($sd_img);
//     		//requests per 100 seconds per user	100
//     		if(!isset($json)){
//     			sleep(1);
//     			$json = $this->get_short($sd_img);
//     		}
//     		if(!isset($json)){
//     			sleep(1);
//     			$json = $this->get_short($sd_img);
//     		}
//     		$sd_img = $json->id;
//     		////////////////////////
    		
    		
			$xmlv .= 
			'<item sdImg="'.$hd_img.'" hdImg="'.$sd_img.'">'."\n".
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
			'<synopsis>'.$desc.'</synopsis>'."\n".
			'<genres>Clip</genres>'."\n".
			'<runlength>'.$video->duration.'</runlength>'."\n".
			'<starrating>75</starrating>'."\n".
			'<Rating>NR</Rating>'."\n".
			'</item>'."\n\n";
    		
    	}
    	
    	$xmlv .= '</feed>'."\n";
    	
    	$path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/playlists';
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
    
    public function tvapp_get_playlist_by_id() {
    	$id = trim(Input::get('id'));
    	$playlist = TvappPlaylist::find($id);
    
    	$video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', (int) $playlist->id)->get();
    
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
    
    	return View::make('tvapp/tvapp_edit_playlist')->with('playlist', $playlist)->with('videos', $videos)->with('channel', $channel);
    	//->with('title', $channel['title'])->with('timestamp', $channel['timestamp'])->with('status', $channel['timestamp']);
    }
    
    public function tvapp_insert_video_in_playlist() {
    
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
    						'tvapp_playlist_id' => $playlist['playlist_id'],
    						'video_id' => $video_id
    				);
    
    				$thumbnail_name = $videos->thumbnail_name;
    			}
    		} else {
    			return Response::json([
    					'status' => true
    			], 200);
    		}
    
    		$playlist = TvappPlaylist::find($playlist['playlist_id']);
    		$playlist->duration = $duration;
    		$playlist->thumbnail_name = $thumbnail_name;
    
    		$videos_in_playlists = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $playlist['id'])->get();
    
    		foreach($videos_in_playlists as $videos_in_playlist) {
    			$videos_in_playlist->delete();
    		}
    
    		TvappVideo_in_playlist::Insert($insert);
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
    
    public function tvapp_delete_playlist() {
    	$tvapp_playlist_id = Input::get('tvapp_playlistId');
    	$tvappl = TvappPlaylist::find($tvapp_playlist_id);
    	TvappPlaylist::find($tvapp_playlist_id)->delete();
    	//Schedule::where('tvapp_playlist_id', '=', $playlist_id)->delete();
    	TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $tvapp_playlist_id)->delete();
    	File::delete(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/playlists/'.$this->xtrim($tvappl->title).'.xml');
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
//     public function tvapp_update_files() {
//     	$id = trim(Input::get('id'));
//     	$playlist = TvappPlaylist::find($id);
    
//     	$videos_in_playlist = TvappVideo_in_playlist::where('playlist_id', '=', $id)->get();
    
//     	$dveo = BaseController::get_dveo();
    
//     	if($playlist->type != 2) {
//     		$allPlaylists = TvappPlaylist::where('channel_id', '=', BaseController::get_channel_id())->get();
    
//     		foreach($allPlaylists as $one) {
//     			$one->master_looped = 0;
//     			$one->type = 0;
//     			$one->save();
//     		}
    
//     		// Change playlist on DVEO
//     		$dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());
//     		$dveo->restart_stream(BaseController::get_channel()->stream);
    
//     		$playlist->master_looped = Carbon::now()->getTimestamp();
//     		$playlist->type = 2;
//     		$playlist->save();
    
//     		return Response::json([
//     				'status' => true,
//     				'loopOff' => true
//     		], 200);
//     	} else {
//     		$playlist->master_looped = 0;
//     		$playlist->type = 0;
//     		$playlist->save();
    
//     		$dveo->stop_stream(BaseController::get_channel()->stream);
    
//     		return Response::json([
//     				'status' => true,
//     				'loopOff' => false
//     		], 200);
//     	}
//     }
    
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
    
    ///TVAPP REST
    
    public function tvapp_get_categories() {
    	$channel_id = '38';
    	//$channel_id = trim(Input::get('channel_id')); // to be implemented
    	$url = public_path().'/tvapp/channel_'.$channel_id.'/roku/xml/categories.xml';
        
    	$json = $this->XMLFiletoJSONparse($url);
                
    	return Response::json($json, 200);
    	
    	return Response::json([
    		'status' => true,
    		'playlist_id' => 'ok'
    	], 200);
    }
    
    
    
    
    ///TVAPP REST FOR VIDEO WEBSITE
    
    public function tvapp_get_section() {
    	$key = trim(Input::get('key'));
    	$channel_id = trim(Input::get('channel_id'));
    	$section_title = trim(Input::get('section_title'));
    	 
    	//key to check: sdf88po234pl3zxPP9
    	
    	//if($section_title == 'Watch Live')
    	//$playlists = TvwebPlaylist::where('channel_id', '=', $channel_id)->
    	//                               where('title', '=', $section_title)->get();
    	
//     	$liveInfo = new Live_info;
//     	$liveInfo->channel_id = BaseController::get_channel_id();
//     	$liveInfo->title = $title;
//     	$liveInfo->description = $description;
//     	$liveInfo->live_url = $liveStreamURL;
//     	$liveInfo->details= '';
//     	$liveInfo->save();
    	
    	$playlists = DB::table('live_info')->where('channel_id', '=', $channel_id)
    	//->where('title', '=', $section_title)
    	->get();
    	 
    	$json = json_encode($playlists[0]);
    	return Response::json($json, 200);
    }    
    
    public function tvapp_get_playlists() {
    	$key = trim(Input::get('api_key'));
    	$channel_id = trim(Input::get('channel_id'));
    
    	//key to check: sdf88po234pl3zxPP9
    
    	$playlists = DB::table('tvapp_playlist')->where('channel_id', '=', $channel_id)
    	//->where('type', '=', '0')
    	->get();
    	    	 
    	$json = json_encode($playlists);
    	return Response::json($json, 200);
    }
    
    public function tvapp_get_videos() {
    	$key = trim(Input::get('api_key'));
    	$channel_id = trim(Input::get('channel_id'));
    	$playlist_title = trim(Input::get('playlist_title'));
    	$tvapp_playlist_id = trim(Input::get('tvapp_playlist_id'));
    
    	$playlists = DB::table('tvapp_playlist')->where('channel_id', '=', $channel_id)
    	//->where('title', '=', $playlist_title)
    	->where('id', '=', $tvapp_playlist_id)
    	->get();
    
    	$ar = array();
    	 
    	foreach($playlists as $pl) {
    		$id = $pl->id;
    		$ttl = $pl->title;
    		$desc = $pl->description;
    
    		$video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)->get();
    		 
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
   		 
    			//$hd_img = htmlspecialchars($hd_img, ENT_XML1, 'UTF-8');
    			$sdimg = htmlspecialchars($sdimg, ENT_XML1, 'UTF-8');
    			
//     			// google url shortening
//     			$json = $this->get_short($sdimg);
//     			//requests per 100 seconds per user	100
//     			if(!isset($json)){
//     				sleep(1);
//     				$json = $this->get_short($sdimg);
//     			}
//     			if(!isset($json)){
//     				sleep(1);
//     				$json = $this->get_short($sdimg);
//     			}
//     			$sdimg = $json->id;
//     			////////////////////////
    			 
    			
    			$ar[] = array($playlist_title => array('tvapp_playlist_id'=>$tvapp_playlist_id,
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
    
   
    function array_push_assoc1($array, $key, $value){
    	$array[$key] = $value;
    	return $array;
    }
    
    function array_push_assoc2($array, $key, $value){
    	$array->$key = $value;
    	return $array;
    }
   
    public function tvapp_get_playlists_with_videos() {
    	$key = trim(Input::get('api_key'));
    	$channel_id = trim(Input::get('channel_id'));
    
    	//key to check: sdf88po234pl3zxPP9
    
    	$playlists = DB::table('tvapp_playlist')->where('channel_id', '=', $channel_id)
    	//->where('type', '=', '0')
    	->get();
    	
    	$playlists_with_videos_arr = array();
    	
    	foreach($playlists as $pl) {
    		$id = $pl->id;
    		$ttl = $pl->title;
    		$desc = $pl->description;
    	
    		$video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)->get();
    		
    		$videos_arr = array();
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
    			
    			$sdimg = htmlspecialchars($sdimg, ENT_XML1, 'UTF-8');
    			
//     			// google url shortening
//     			$json = $this->get_short($sdimg);
//     			//requests per 100 seconds per user	100
//     			if(!isset($json)){
//     				sleep(1);
//     				$json = $this->get_short($sdimg);
//     			}
//     			if(!isset($json)){
//     				sleep(1);
//     				$json = $this->get_short($sdimg);
//     			}
//     			$sdimg = $json->id;
//     			////////////////////////
    			
    			$videos_arr[] = array('tvapp_playlist_id'=>$pl->id,
    					'title'=>$ttl,
    					'description'=>$desc,
    					'url'=>$fname,
    					'duration'=>$video->duration,
    					'img'=>$sdimg);
    		}
    		
    		//insert videos into playlist array
    		$pl_arr = $this->array_push_assoc2($pl, 'videos', $videos_arr);
    		
    		$playlists_with_videos_arr[] = array('playlist'=>$pl_arr);
    		
    	}
    	
    	$json = json_encode($playlists_with_videos_arr);
    	return Response::json($json, 200);
    }
    
    public function tvapp_get_channel_live_url() {
    	$channel_id = trim(Input::get('channel_id'));
    	
    	$liveInfo = Live_info::where('channel_id', '=', $channel_id)->get();
    	
    	$json = json_encode($liveInfo);
    	//Log::info($json);
    	return Response::json($json, 200);
    	
    }
    
    public function get_short($sdimg){
    	$longUrl = $sdimg."&t=".time();
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
    
    	//Log::info($response);
    	//echo 'Shortened URL ->'.$json->id;
    	return $json;
    }

    
}



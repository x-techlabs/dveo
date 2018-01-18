<?php
use File;
	class YoutubeImporterController extends BaseController {
	   public function site_index() 
	   {
		$data['facebook'] = 'https://www.facebook.com/envato/';
		$data['twitter'] = 'https://twitter.com/envato';
		$data['use_mp3'] = 1; //1 enabled / 0 disabled
		$data['use_external_js'] = 1; //1 enabled / 0 disabled
		
		$this->load->view("site-front-end", $data);
	    }

		// safe values
		private function safe($value) {
			return trim(htmlspecialchars($value));
		}

	    public function retrieveJsonInfo($channel,$video_id)
	    {
			$info_arr =  App::make('YTChannel')->get_video_info($video_id);
			$json_arr = array();

//			echo "<pre>";
			if(count($info_arr) < 7)
				die("INVALID VIDEO");

			if(isset($info_arr['url_encoded_fmt_stream_map']))
			{

				$formats_arr = explode(',', $info_arr['url_encoded_fmt_stream_map']);
				$avail_formats = array();
				$i = 0;
				$ipbits = $ip = $itag = $sig = $quality = '';
				foreach($formats_arr as $format)
				{
					parse_str($format);
					parse_str(urldecode($url));
//					var_dump($url);
					if($itag == 13 || $itag == 17 || $itag == 36) continue;
					$signature = '';
					if(isset($s)) $signature = $this->decodeSig($s);

					$itag_info = $this->getItagInfo($itag);
					$json_arr[$i]['video_id'] = $video_id;
					$json_arr[$i]['mime'] = $type;
					$json_arr[$i]['title'] = $info_arr['title'];
					$json_arr[$i]['author'] = $info_arr['author'];
					$json_arr[$i]['thumbnail_url'] = isset($info_arr['iurlsd']) ? $info_arr['iurlsd'] : $info_arr['thumbnail_url'];
					$json_arr[$i]['duration'] = $info_arr['length_seconds'];
					$json_arr[$i]['is_listed'] = $info_arr['is_listed'];
					$json_arr[$i]['cr'] = $info_arr['cr'];
					$json_arr[$i]['view_count'] = $info_arr['view_count'];
					$json_arr[$i]['quality'] = ucfirst(str_replace('d7', 'D7', $quality));
					$json_arr[$i]['download_url'] = urldecode($url);
					$json_arr[$i]['signature_encoded'] = isset($s) ? $s : '';
					$json_arr[$i]['format'] = $itag_info[1];
					$json_arr[$i]['res'] = $itag_info[2];
					$json_arr[$i]['php_decoded_signature'] = $signature;

					$i++;
				}

//				die;
				echo json_encode($json_arr);
			}
	    }
	    
	    public function getVideoData($channel,$video_id){

			$info = file_get_contents('http://www.youtube.com/get_video_info?&video_id='.$video_id.'&el=detailpage');
//    		$info = file_get_contents('http://www.youtube.com/get_video_info?&video_id='.$video_id.'&asv=3&el=detailpage&hl=en_US');
			$info_arr = array();
			$json_arr = array();
			parse_str($info, $info_arr);
			if(count($info_arr) < 7) 
				die("INVALID VIDEO");

			if(isset($info_arr['url_encoded_fmt_stream_map']))
			{
				$json_arr['author'] = $info_arr['author'];
				echo json_encode($json_arr);
			}
	    
	    }
	    

		private function timeToSecond($time)
    	{
        	sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);

			$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;

			return $time_seconds;
    	}

	    public function download() 
	    {
			set_time_limit(0);
			ini_set('memory_limit', '1G');
			$mime = base64_decode($_GET['mime']);
			$mime = current(explode(";", $mime));
			$ext  = str_replace(array('/', 'x-'), '', strstr($mime, '/'));
			$ext  = current(explode(";", $ext));
			$url  = base64_decode($_GET['url']);
			$name = urldecode(base64_decode($_GET['title'])). '.' .$ext;
			$size = $this->getSize($url);
			$color = (isset($_GET['color'])) ? urldecode(base64_decode($_GET['color'])) : '';
			$videoUsername = (isset($_GET['name'])) ? urldecode(base64_decode($_GET['name'])) : '';
			$fontsize = (!empty($_GET['fontsize']) && $_GET['fontsize'] != 0) ? $_GET['fontsize'] : 34;
			$display_time = (!empty($_GET['display_time']) && $_GET['display_time'] != 0) ? $_GET['display_time'] : 10;
			$duration = (!empty($_GET['duration']) && $_GET['duration'] != '') ? $_GET['duration'] : 0;
			$start_time = (!empty($_GET['start_time']) && $_GET['start_time'] != '') ? $_GET['start_time'] : '';
			$duration_seconds = $this->timeToSecond($duration);
//			var_dump($url);die;
			//var_dump($at_start, $at_end);die;
			$file_headers = @get_headers($url);
			
			if(!$file_headers || $file_headers[0] == 'HTTP/1.1 403 Forbidden') {
				$exists = false;
			}
			else {
				$exists = true;
			}

			if(isset($_GET['name']) && (!empty($videoUsername) || $videoUsername != 0)){
				$file_time = time();
			
				if($exists){
					$fadeOut = $display_time/2;
					if($start_time != '' && $start_time == 'end'){
						$start = $duration_seconds-$display_time;
						$end_time = $duration_seconds;
					}
					else if($start_time != '' && $start_time == 'start'){
						$start = 1;
						$end_time = $display_time;
					}
					else{
						$start = 1;
						$end_time = $display_time;
					}


					copy($url, public_path().'/youtube_importer/files/'.$file_time.'.mp4');
					//var_dump('ffmpeg -y -i '.public_path("youtube_importer/files/".$file_time.".mp4").' -filter_complex "[0:v] drawtext=fontfile='.public_path("fonts/arial.ttf").':text='.$videoUsername.':x=50:y=50:enable='."'between(t,$start,$end_time)'".':fontcolor='.$color.':fontsize='.$fontsize.' [subtitles]; [subtitles][0:v] blend=all_expr='."'A*(if(between(T,$start,$end_time),(T-$start)/($end_time-$fadeOut),1))+B*(1-(if(between(T,$start,$end_time),(T-$start)/($end_time-$fadeOut),1)))'".' [out]" -map "[out]" -map 0:a '.public_path("youtube_importer/files/".$file_time."_video.mp4").'');die;
					
					exec('ffmpeg -y -i '.public_path("youtube_importer/files/".$file_time.".mp4").' -filter_complex "[0:v] drawtext=fontfile='.public_path("fonts/arial.ttf").':text='.$videoUsername.':x=50:y=50:enable='."'between(t,$start,$end_time)'".':fontcolor='.$color.':fontsize='.$fontsize.' [subtitles]; [subtitles][0:v] blend=all_expr='."'A*(if(between(T,$start,$end_time),(T-$start)/($end_time-$fadeOut),1))+B*(1-(if(between(T,$start,$end_time),(T-$start)/($end_time-$fadeOut),1)))'".' [out]" -map "[out]" -map 0:a '.public_path("youtube_importer/files/".$file_time."_video.mp4").'');


					//exec('ffmpeg -i '.public_path("youtube_importer/files/".$file_time.".mp4").' -c:v libx264 -preset ultrafast -vf drawtext="fontfile='.public_path("fonts/arial.ttf").': \ text='.$videoUsername.':enable='."'between(t,$start,$end_time)'".':fontcolor='.$color.':fontsize='.$fontsize.': box=1: boxcolor=black@0.5: \ boxborderw=5: x=50: y=50" -codec:a copy '.public_path("youtube_importer/files/".$file_time."_video.mp4").'');
				}else{
					return Redirect::back()->with('message','Failed to download file. Please try again!');
				}
			}
			
			
			if(isset($_GET['mp3']) && $_GET['mp3']==1) {
				
				$time_name = time();
            	copy($url, public_path().'/youtube_importer/files/'.$time_name.'.mp4');
				exec("ffmpeg -i ".public_path()."/youtube_importer/files/".$time_name.".mp4 -b:a 160K -vn ".public_path()."/youtube_importer/files/".$time_name.".mp3");
				header('Content-Type: audio/mpeg');
				header('Content-Disposition: attachment; filename="' .urldecode(base64_decode($_GET['title'])).'.mp3"');
				header("Content-Transfer-Encoding: binary");
				readfile(public_path()."/youtube_importer/files/".$time_name.".mp3");
				unlink(public_path()."/youtube_importer/files/".$time_name.".mp4");
				unlink(public_path()."/youtube_importer/files/".$time_name.".mp3");
				exit;
			}
			header('Content-Type: "' . $mime . '"');
			header('Content-Disposition: attachment; filename="' . $name . '"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			
			if(isset($_GET['name']) && (!empty($videoUsername) || $videoUsername != 0)){
				readfile(public_path("youtube_importer/files/".$file_time."_video.mp4"));
				unlink(public_path("youtube_importer/files/".$file_time."_video.mp4"));
				unlink(public_path("youtube_importer/files/".$file_time.".mp4"));
			}else{
				if($exists){
					readfile($url);
				}else{
					return Redirect::back()->with('message','Failed to download file. Please try again!');
				}			
			}
			
			exit;
	    }
	    
	    private function getItagInfo($itag)
	    {
		$typeMap = array();
		$typeMap[13] = array("13", "3GP", "176x144");
		$typeMap[17] = array("17", "3GP", "176x144");
		$typeMap[36] = array("36", "3GP", "320x240");
		$typeMap[5]  = array("5", "FLV", "400x226");
		$typeMap[6]  = array("6", "FLV", "640x360");
		$typeMap[34] = array("34", "FLV", "640x360");
		$typeMap[35] = array("35", "FLV", "854x480");
		$typeMap[120] = array("120", "FLV", "1280x720");
		$typeMap[43] = array("43", "WEBM", "640x360");
		$typeMap[44] = array("44", "WEBM", "854x480");
		$typeMap[45] = array("45", "WEBM", "1280x720");
		$typeMap[18] = array("18", "MP4", "480x360");
		$typeMap[22] = array("22", "MP4", "1280x720");
		$typeMap[37] = array("37", "MP4", "1920x1080");
		$typeMap[38] = array("38", "MP4", "4096x230");
		return $typeMap[$itag];
	    }
	    
	    private function getSize($url) 
	    {
		$my_ch = curl_init();
		curl_setopt($my_ch, CURLOPT_URL,$url);
		curl_setopt($my_ch, CURLOPT_HEADER, true);
		curl_setopt($my_ch, CURLOPT_NOBODY, true);
		curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($my_ch, CURLOPT_TIMEOUT, 10);
		$r = curl_exec($my_ch);
		foreach(explode("\n", $r) as $header) 
		{
		    if(strpos($header, 'Content-Length:') === 0) 
		    {
		        return trim(substr($header, 16)); 
		    }
		}
		return false;
	    }
	    
	    private function decodeSigChild1(&$sig , $b)
	    {
		$sig = array_splice($sig , $b);
		return $sig;
	    }
	    
	    private function decodeSigChild2(&$sig , $b)
	    {
		$c = &$sig [0];
		$sig [0] = $sig[$b % count($sig)];
		$sig [$b] = $c;
		return $sig;
	    }
	    
	    private function decodeSigChild3(&$sig )
	    {
		return $sig = array_reverse($sig );
	    }
	    
	    private function decodeSig($sig)
	    {
		$sig = str_split($sig);
		$this->decodeSigChild1($sig, 2);
		$this->decodeSigChild2($sig, 64);
		$this->decodeSigChild3($sig);
		$this->decodeSigChild1($sig, 3);

		$final_product = implode($sig);
		return $final_product;
	    }

	}

?>

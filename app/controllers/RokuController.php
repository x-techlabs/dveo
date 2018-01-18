<?php
    /**
 * Created by Sublime.
 * User: user
 * Date: 8/27/16
 * Time: 11:00 AM
 */


class RokuController extends BaseController
{
    public function CallOtherServerByCurl($cRequestUrl)
    {
        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL, $cRequestUrl);
        curl_setopt($crl, CURLOPT_HEADER, 0);
        curl_setopt($crl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
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

    public function CallServerByCurl($param)
    {
        $channel_id = Input::get('channel_id');
        $c = Channel::find($channel_id);
        $set = explode('|', $c->storage);
        $url = $set[5].$param;
        return $this->CallOtherServerByCurl($url);
    }

	public function IsRegistered() 
	{
        $ans = $this->CallServerByCurl('/isRegistered?deviceid='.Input::get('deviceID'));
        print $ans;
        exit();
	}

    public function GetActivationCode()
    {
        $ans = $this->CallServerByCurl('/getActivationCode');
        print $ans;
        exit();
    }

    public function RegisterDevice()
    {
        $ans = $this->CallServerByCurl('/registerDevice?deviceid='.Input::get('deviceid').'&activationCode='.Input::get('activationCode'));
        print $ans;
        exit();
    }

//==============================================================================

    public function tvapp_get_root_level_records($playlists, $ret='records')
    {
        $tree = array();
        foreach($playlists as $pl)
        {
            if ($pl->level > 0) continue;
            $rootNode = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', '0')
                                              ->where('video_id', '=', $pl->id)
                                              ->where('type', '=', '1')
                                              ->first();

            if (!is_object($rootNode)) continue;
            $tree[] = $rootNode->id;
        }
        if ($ret=='ids') return $tree;

        $video_ids = TvappVideo_in_playlist::whereIn('id', $tree)
                                           ->orderBy('sort_order', 'ASC')
                                           ->get();
        return $video_ids;
    }

    public static function get_tvapp_playlists($channelID) {

    	$playlists = TvappPlaylist::where("channel_id", "=", $channelID)->orderBy('title', 'asc')->get();
        return $playlists;
    }

    public function GetViewingType($viewing, $myname, $pid)
    {
        if ($viewing=='paid' || $viewing == ' free') return array($viewing, str_replace(' ', '_', $myname));
        if ($pid==0) return array('free', '');

   		$video = TvappPlaylist::find($pid);
        $record = TvappVideo_in_playlist::where('video_id', '=', $pid)->first();
        return $this->GetViewingType($video->viewing, $video->title, $record->tvapp_playlist_id);
    }

    public function GetData()
    {
        $id = Input::get('id');
        $channel_id = Input::get('channel_id');
        $c = Channel::find($channel_id);

        if ($c->source=='ustream')
        {
            return $this->UStream_getSnapShot($id);
        }

        if ($id==0)
        {
        	$playlists = $this->get_tvapp_playlists($channel_id);
            $video_ids = $this->tvapp_get_root_level_records($playlists);
        }
        else $video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)
                                                ->orderBy('sort_order', 'ASC')
                                                ->get();

        $category = array();    
    	foreach($video_ids as $video_id) 
    	{
            if ($video_id->type==0)
            {
        		$video = Video::find($video_id['video_id']);
                if (!is_object($video)) continue;

                $info = $video->GetInfo($channel_id);
                list($vType, $pkg) = $this->GetViewingType($video_id->viewing, $video->title, $id);

                $xmlv  = "contentId|".$video_id['video_id'];
                $xmlv .= "|title|".$info['title'];
                $xmlv .= "|description|".$info['description'];
                $xmlv .= "|sd_img|".$info['hd_img'];
                $xmlv .= "|feed|".$info['url'];
                $xmlv .= "|layout|video";
                $xmlv .= "|runlength|".$video->duration;
                $xmlv .= "|viewing|$vType";
                $xmlv .= "|subscription|$pkg";
                $category[] = $xmlv;
    	    }
            else if ($video_id->type==1)
            {
        		$video = TvappPlaylist::find($video_id['video_id']);
                if (!is_object($video)) continue;

        		$plimg = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'_'.'tvapp_playlist_'.$video_id['video_id'];
                if ($video->thumbnail_name != '') $plimg = $video->thumbnail_name;
    		                 
                $xmlv  = "contentId|".$video_id['video_id'];
                $xmlv .= "|title|".$video->title;
                $xmlv .= "|description|".$video->description;
                $xmlv .= "|sd_img|$plimg";
                if ($video['layout']==0 || $video['layout']==1) 
                {
                    $p1 = strpos($video['stream_url'], 'clienttype=mrss');
                    if ($p1===false) $p1 = strpos($video['stream_url'], 'clienttype&amp;eqmrss');
                    if ($p1 !== false)
                    {
                        $feed = $video['stream_url'];
                        $xmlv .= "|feed|".str_replace('clienttype&amp;eqmrss', 'clienttype=mrss', $feed);
                    }
                    else $xmlv .= "|feed|getdatafromurl";
                    $xmlv .= "|layout|linear";
                }
                else if ($video['layout']==2) 
                {
                    $xmlv .= "|feed|".$video['stream_url'];
                    $xmlv .= "|layout|video";
                }
                else if ($video['layout']==3) 
                {
                    $xmlv .= "|feed|$plimg";
                    $xmlv .= "|layout|paragraph";
                }
                list($vType, $pkg) = $this->GetViewingType($video_id->viewing, $video->title, $id);
                $xmlv .= "|viewing|$vType";
                $xmlv .= "|subscription|$pkg";
                $category[] = $xmlv;
    	    }
    	}
        
        print implode('^', $category);
        exit();
    }
}

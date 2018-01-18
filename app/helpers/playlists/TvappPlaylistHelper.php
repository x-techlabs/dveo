<?php

namespace App\Helpers\Playlists;

use \Channel;
use \Video_show;
use \Video_tags;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use \TvappVideo_in_playlist;
use \Video;
use \TvappPlaylist;
use \File;
use \Videos_in_collections;
use \Collections;

class TvappPlaylistHelper {

    /**
     * @var integer
     */
    private $channel_id;

    public function __construct($channel_id) {
        $this->channel_id = $channel_id;
    }

    public function UStream_getSnapShot($id)
    {
        if ($id==0) return 0;

        $video = TvappPlaylist::find($id);
        if (is_object($video))
        {

            $fname = public_path().'/tvapp/channel_'.$this->channel_id.'/roku/xml/'.$video->title.".xml";
            $out = $this->CallOtherServerByCurl("https://api.ustream.tv/channels/".$video->title.".xml");
            file_put_contents($fname, $out);

            $fname = public_path().'/tvapp/channel_'.$this->channel_id.'/roku/xml/'.$video->title."_videos.xml";
            $out = $this->GetAllVideos($video->title);
            file_put_contents($fname, $out);
            return 1;
        }
        return 0;
    }


    function GetAllVideos($channelID)
    {
        $items = array();
        $page = 1;
        while ($page > 0)
        {
            $out = $this->CallOtherServerByCurl("https://api.ustream.tv/channels/$channelID/videos.xml?page=".$page);

            $pos = strpos($out, "<paging>");
            if ($pos === false)
            {
                $items[] = $out;
                $page = 0;
                continue;
            }

            $items[] = substr($out, 0, $pos);
            if ( $this->UStream_IsNextPage(substr($out, $pos)) ) $page++;
            else $page = 0;
        }

        $from = 0;
        for ($i = 0 ; $i < count($items) ; $i++)
            $items[$i] = $this->UStream_AdjustItems($items[$i], $from);

        $out  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<xml>\n<videos>\n";
        $out .= implode('', $items);
        $out .= "</videos>\n</xml>\n";
        return $out;
    }


    function CallOtherServerByCurl($cRequestUrl)
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

    public function BuildChannelRootXml() {
		$video = Video::all();
        $playlists = TvappPlaylist::with([])
            ->with([
                'children'
            ])
            ->where('channel_id', $this->channel_id)
            ->where('parent_id', null)
            ->orderBy('sort_order','asc')
            ->get();

        $title = "categories_linear.xml";
        $path = public_path().'/tvapp/channel_'.$this->channel_id.'/roku/xml/';

        $view = View::make('tvapp.roku_xml.channel', [
            'playlists' => $playlists,
			'video' =>$video,
        ]);

        File::put($path.$title, $view->render());
    }

    public function BuildFeedXML($id)
    {
        $c = Channel::find($this->channel_id);
        if ($c->source=='ustream')
        {
            return $this->UStream_getSnapShot($id);
        }

        $this->BuildPlaylistRokuXml($id);
        $this->BuildPlaylistMrss($id);

        return 1;
    }

    public function __get_video_path($v)
    {
        if ($v->source == '0' || $v->source == 'internal')
        {
            $fname = 'http://'.$this->channel_id.'.1studio.tv.global.prod.fastly.net/'.$v->file_name.'.mp4';
            return $fname;
        }
        return $v->file_name;
    }

    public function BuildPlaylistRokuXml($id) {

        $playlist = TvappPlaylist::with([
            'videos',
        ])->find($id);

        $title = 'playlists/'.static::xtrim($playlist->title).'.xml';
        $path = public_path().'/tvapp/channel_'.$this->channel_id.'/roku/xml/';

        if(isset($playlist->videos) && count($playlist->videos) > 0){
            foreach ($playlist->videos as $key => $video) {
				$video['shows'] = Video_show::where('video_id',$video->id)->with('show_names')->get();
				$video['tags'] = Video_tags::where('video_id',$video->id)->with('tag_names')->get();
                $vid = $video->id;
                $videoCollection = Videos_in_collections::where('video_id', '=', $vid)->get();
                if(count($videoCollection) > 0 && !empty($videoCollection)){
                    $collection_id = $videoCollection[0]->collection_id;
                    $collection = Collections::find($collection_id);
                    $channel = Channel::find($this->channel_id);
                    if(!empty($collection->pre_roll) && $collection->pre_roll != 'no' && $channel['prerolls'] != '0'){
                        $videosArray = Videos_in_collections::where('collection_id', '=', $collection_id)
                            ->select('video_id')
                            ->get();
                        $videoIDs = array();
                        if(count($videosArray) > 0){
                            foreach ($videosArray as $key => $value) {
                                array_push($videoIDs, $value->video_id);
                            }
                            $random_item=array_rand($videoIDs,1);
                            $preroll_item =  Video::find($videoIDs[$random_item]);
                            $prerollUrl = $this->__get_video_path($preroll_item);
                        }
                        else{
                            $prerollUrl = '';
                        }
                    }
                    else{
                        $prerollUrl = '';
                    }
                }
                else{
                    $prerollUrl = '';
                }
                $video->prerollUrl = $prerollUrl;

            }
        }
		$channel = Channel::find($this->channel_id);
        if (!File::isDirectory($path))
            mkdir($path, 0777);

        $view = View::make('tvapp.roku_xml.playlist', [
            'playlist' => $playlist,
			'channel' => $channel
        ]);

        File::put($path.$title, $view->render());
    }

    public function BuildPlaylistMrss($id) {
        $path = public_path().'/tvapp/channel_'.$this->channel_id.'/roku/mrss';
        if (!File::isDirectory($path))
            mkdir($path, 0777);

        $playlist = TvappPlaylist::find($id);
        $title = '/'.static::xtrim($playlist->title).'.mrss';

        if(isset($playlist->videos) && count($playlist->videos) > 0){
            foreach ($playlist->videos as $key => $video) {
				$video['shows'] = Video_show::where('video_id',$video->id)->with('show_names')->get();
				$video['tags'] = Video_tags::where('video_id',$video->id)->with('tag_names')->get();
                $vid = $video->id;
                $videoCollection = Videos_in_collections::where('video_id', '=', $vid)->get();
                if(count($videoCollection) > 0 && !empty($videoCollection)){
                    $collection_id = $videoCollection[0]->collection_id;
                    $collection = Collections::find($collection_id);
                    $channel = Channel::find($this->channel_id);
                    if(!empty($collection->pre_roll) && $collection->pre_roll != 'no' && $channel['prerolls'] != '0'){
                        $videosArray = Videos_in_collections::where('collection_id', '=', $collection_id)
                            ->select('video_id')
                            ->get();
                        $videoIDs = array();
                        if(count($videosArray) > 0){
                            foreach ($videosArray as $key => $value) {
                                array_push($videoIDs, $value->video_id);
                            }
                            $random_item=array_rand($videoIDs,1);
                            $preroll_item =  Video::find($videoIDs[$random_item]);
                            $prerollUrl = $this->__get_video_path($preroll_item);
                        }
                        else{
                            $prerollUrl = '';
                        }
                    }
                    else{
                        $prerollUrl = '';
                    }
                }
                else{
                    $prerollUrl = '';
                }
                $video->prerollUrl = $prerollUrl;

            }
        }
		$channel = Channel::find($this->channel_id);
        $view = View::make('tvapp.mrss.playlist', [
            'playlist' => $playlist,
			'channel' => $channel
        ]);

        File::put($path.$title, $view->render());
    }

    public static function get_tvapp_playlists($channel_id) {

        $playlists = \TvappPlaylist::with([])
            ->where("channel_id", "=", $channel_id)
            ->where('parent_id', null)
            ->orderBy('sort_order', 'asc')
            ->get();
        //        $playlists = Playlist::all();
        //        $timeline = Timeline::get(['percentage', 'classColor']);

        return \Time::change_to_human_data_in_array($playlists);
    }

    public function tvapp_get_root_level_records($playlists, $ret='records')
    {
        $tree = array();
        foreach($playlists as $pl)
        {
            if ($pl->level > 0) continue;
            $rootNode = \TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $pl->id)
//                ->where('video_id', '=', $pl->id)
//                ->where('type', '=', '1')
                ->first();
            if (!is_object($rootNode)) continue;
            $tree[] = $rootNode->id;
        }
        if ($ret=='ids') return $tree;

        $video_ids = \TvappVideo_in_playlist::whereIn('tvapp_video_in_playlist.id', $tree)
            ->join('tvapp_playlist', 'tvapp_playlist.id', '=', 'tvapp_video_in_playlist.tvapp_playlist_id')
            ->orderBy('tvapp_playlist.sort_order', 'asc')
            ->orderBy('tvapp_video_in_playlist.sort_order', 'ASC');
//            ->get();

        return $video_ids->get();
    }


    public function Q($ttl, $keepQuotes=0)
    {
        if ($keepQuotes==0)
        {
            return htmlspecialchars($ttl, ENT_XML1);
            //$ttl = str_replace("&","and",$ttl);
            //$ttl = str_replace('"','',$ttl);
            //$ttl = str_replace("'","",$ttl);
            //$ttl = str_replace('\'','',$ttl);
        }
        return $ttl;
    }


    public static function EscapeStreamUrl($in)
    {
        $in = str_replace( array("=",   "<",    ">",    '"',      "'"),
            array('&eq;', "&lt;", "&gt;", "&quot;", "&apos;"),
            $in);

        return htmlspecialchars($in, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }


    public function mrssForSchedule()
    {
        $sid = \Input::get('scheduleId');
        $schedule = \ManageScheduleModel::where('id', $sid)->first();
        $channel_id = $schedule->channel_id;
        $time = $schedule->start_date;
        $endDate = $schedule->end_date;
        $videoList = explode(',', $schedule->video_id_list);
        $schedule->description = "";
        $schedule->title = $schedule->name;

        $mrssFeed = array();
        $channel_id = $this->channel_id;

        $videos = [];
        foreach($videoList as $video_id)
        {
            if ($video_id > 0)
            {
                $video = Video::find($video_id);
                if (!is_object($video)) continue;
                $videos[] = $video;
            }
            else if ($video_id < 0)
            {
                $videos_in_collections = \Videos_in_collections::where('collection_id', '=', -$video_id)->get();
                foreach ($videos_in_collections as $vc)
                {
                    $video = Video::find($vc['video_id']);
                    if (!is_object($video)) continue;
                    $videos[] = $video;
                }
            }
        }

        if (count($videos) > 0)
        {
            $path = public_path().'/tvapp/channel_'.$channel_id.'/schedule';
            if (!\File::isDirectory($path))
                mkdir($path, 0777);

            $title = '/'.static::xtrim($schedule->name).'.mrss';

            $view = View::make('tvapp.mrss.schedule', [
                'playlist' => $schedule,
                'items' => $videos,
            ]);

            \File::put($path.$title, $view->render());

            print str_replace(public_path(), \URL::to('/'), $path).$title;
        }
        else print "error";
    }

    public static function xtrim($title) {

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

    public function normalizeVideoOrder($playlist_id, $appended_video_id = NULL) {
        $playlist = TvappPlaylist::find($playlist_id);

        $query = $playlist->videos()->orderBy('tvapp_video_in_playlist.sort_order');
        if(!is_null($appended_video_id)) {
            $query->orderBy(DB::raw('(video.id <> '.$appended_video_id.')'));
        }
        $videos = $query->get();

        $sort_order_iterator = 0;
        foreach ($videos as $video) {
            $sort_order_iterator++;

            $playlist->videos()->updateExistingPivot($video->id, [
                'sort_order' => $sort_order_iterator,
            ]);
        }

        return true;
    }

    public function normalizePlaylistOrder($parent_playlist_id = NULL, $playlist_id = NULL, $position_id = NULL) {
        $playlists = TvappPlaylist::where('parent_id', $parent_playlist_id)
            ->where('channel_id', $this->channel_id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('title', 'asc')
            ->get();

        $sort_order_iterator = 0;
        foreach ($playlists as $playlist) {
            $sort_order_iterator++;

            if(!is_null($playlist_id) && $sort_order_iterator == $position_id){
                $moved_playlist = TvappPlaylist::find($playlist_id);
                $moved_playlist->sort_order = $sort_order_iterator;
                $sort_order_iterator++;
            }

            if($playlist_id == $playlist->id) {
                continue;
            }
            $playlist->sort_order = $sort_order_iterator;
            $playlist->save();
        }

        return true;
    }
}
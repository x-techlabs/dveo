<?php

namespace App\Helpers\Playlists;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use \Videos_in_collections;
use \Collections;
use \Channel;
use \Video;

class MRssPlaylistParser {
    /**
     * Parse playlist's children from external MRss feed and get result as array
     *
     * @param $playlists
     * @return array
     */

    private $channel_id;
    
    private $playlist;

    public function __construct($playlist,$channel_id)
    {
        $this->playlist = $playlist;
        $this->channel_id = $channel_id;
    }

    public static function playlistsToArrayWithMRssChildren($playlists,$channel_id){
        $data = [];
        foreach ($playlists as $playlist) {
//            if($playlist->type != 6){
                $helper = new MRssPlaylistParser($playlist,$channel_id);
                $data[] = $helper->toArray();
//            }
        }

        return $data;
    }

    /**
     * @var \TvappPlaylist
     */


    /**
     * Load MRss feed
     *
     * @return null|array
     */
    private function loadMRss() {
        try {
			if($this->playlist->type != 6) {
				$mrss_xml = null;
//				$mrss_xml = simplexml_load_file($this->playlist->stream_url);
			}
			else{
				$mrss_xml = null;
			}
		} catch (\Error $error) {
			return null;
		}
//			var_dump($this->playlist->type,$mrss_xml);die;
		return $mrss_xml;
    }

    /**
     * Parse Rss item to Video object
     *
     * @param $item
     * @return mixed
     * @throws \Error
     */
    private function parseXmlItem($item) {
        $namespaces = $item->getNamespaces(true);
        $media_namespace = isset($namespaces['media']) ? 'media' : key($namespaces);
        $media = $item->children($media_namespace, true);

        // find video
        if($media->content->count() > 0) {
            $content_node = $media->content;
            $content_node_attributes = $content_node->attributes();
        } else if ($media->group->count() > 0){
            $content_node = null;
            foreach ($media->group->content as $child){
                $content_node = $child;
                break;
            }
            $content_node_attributes = $content_node->attributes();
        }else {
            throw new \Error('invalid xml. please check mrss format');
        }

        // find thumbnail
        $thumbnail_attributes = [];
        if ($media->thumbnail->count() > 1) {
            foreach ($media->thumbnail as $child){
                $thumbnail_attributes = $child->attributes();
                break;
            }
        } else if ($media->thumbnail->count() > 0) {
            $thumbnail_attributes = $media->thumbnail->attributes();
        } else {
            $thumbnail_attributes = [
                'url'  => null,
            ];
        }


        $video = new \Video();
        $video->title = (string)$media->title;
        $video->description = (string)$media->description;
        $video->thumbnail_name = (string)$thumbnail_attributes['url'];
        $video->start_time = null;
        $video->duration = null;
        $video->video_format = (string)$content_node_attributes['type'];

        $video_data = $video->toArray();
        $video_data['video_path'] = (string)$content_node_attributes['url'];

        return $video_data;
    }

    /*
    {
        "id": 1681,
        "playlist_id": 0,
        "channel_id": 35,
        "title": "A FRESH TAKE With Chef Stevie \"Sweet & Sour Chicken\"",
        "description": "",
        "thumbnail_name": "https://onestudio.imgix.net/ed8ff5598734d3a1d6ef0c31aa84fbbd_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60",
        "start_time": "2015-06-28 21:35:41",
        "duration": 6,
        "file_name": "ed8ff5598734d3a1d6ef0c31aa84fbbd",
        "video_format": "mp4",
        "job_id": "0",
        "encode_status": 2,
        "type": 0,
        "hd_width": 1280,
        "hd_height": 720,
        "hd_file_size": 119262856,
        "hd_video_bitrate": 2363,
        "hd_audio_codec": "",
        "hd_video_codec": "h264",
        "hd_mime_type": "video/mp4",
        "sd_file_name": "",
        "sd_duration": 0,
        "sd_width": 0,
        "sd_height": 0,
        "sd_file_size": 0,
        "sd_video_bitrate": 0,
        "sd_audio_codec": "",
        "sd_video_codec": "",
        "sd_mime_type": "",
        "mb_file_name": "",
        "mb_duration": 0,
        "mb_width": 0,
        "mb_height": 0,
        "mb_file_size": 0,
        "mb_video_bitrate": 0,
        "mb_audio_codec": "",
        "mb_video_codec": "",
        "mb_mime_type": "",
        "created_at": "2016-04-17 01:11:33",
        "updated_at": "2016-09-12 21:55:39",
        "storage": "2016/08/15 - 119263kb, 1280X720, video/mp4, h264",
        "source": "internal",
        "video_path": "http://35.1studio.tv.global.prod.fastly.net/ed8ff5598734d3a1d6ef0c31aa84fbbd.mp4",
        "pivot": {
          "tvapp_playlist_id": 5,
          "video_id": 1681,
          "type": 0,
          "sort_order": 1,
          "created_at": "2017-02-12 12:36:21",
          "updated_at": "2017-03-01 21:44:44"
        }
      },
    */

    /**
     * Get playlist's videos from MRss or cache
     *
     * @return array
     */
    public function getVideos() {

        $data = $this->getVideosFromCache();
        if (!is_null($data)) {
            return $data;
        }

        $mrss_xml = $this->loadMRss();
        if(is_null($mrss_xml)) {
            return [];
        }

        $data = [];
        foreach ($mrss_xml->channel->item as $item) {
            $data[] = $this->parseXmlItem($item);
        };

        $this->setVideosCache($data);
        return $data;
    }

    public function getVideosFromCache() {
        return Cache::get($this->getCacheKey());
    }

    private function setVideosCache($data) {
        $expiresAt = Carbon::now()->addMinutes(60);
        Cache::put($this->getCacheKey(), $data, $expiresAt);
    }

    private function getCacheKey() {
        return 'tvapp_mrss_videos:'.$this->playlist->id;
    }
    /**
     * return playlist as array with videos
     *
     * @return mixed
     */

    public function __get_video_path($v)
    {
        if ($v->source == '0' || $v->source == 'internal')
        {
            $fname = 'http://'.$this->channel_id.'.1studio.tv.global.prod.fastly.net/'.$v->file_name.'.mp4';
            return $fname;
        }
        return $v->file_name;
    }

    public function toArray() {
        $allowed_layouts = [2, 3]; // streams and texts
        // $allowed_layouts = [4, 5]; // streams and texts
        $playlist_data = $this->playlist->toArray();

        if(isset($playlist_data['videos']) && count($playlist_data['videos']) > 0){
            for($i = 0; $i < count($playlist_data['videos']);$i++){
                $vid = $playlist_data['videos'][$i]['id'];
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
                $playlist_data['videos'][$i]['prerollUrl'] = $prerollUrl;
            }
        }

        if(!in_array($this->playlist->layout, $allowed_layouts) ||  $this->playlist->stream_url == '') {
            return $playlist_data;
        }

        $playlist_data['videos'] = $this->getVideos();

        return $playlist_data;
    }
}
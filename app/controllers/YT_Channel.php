<?php

class YTChannel extends BaseController {

    public $Key;
    public $Error;
    public $appKey = 'AIzaSyCIuVnDuCtVW6IPvOKEC59Wxwn829mqpEk';
//    public $appKey = 'AIzaSyBGOziBGwcHZXzjoHwC_5z2yZE8on55kvk';

    public function __construct() {
        $this->Key = $this->appKey;
    }

    // Setup API key from your google app
    public function API($query) {
        if ($query) {
            return ('https://www.googleapis.com/youtube/v3/' . $query);
        } else {
            $this->Error = 'Must be enter your api query';
            $this->error();
        }
    }

    // Get channel info by ID
    public function channel_info($channel_id) {
        $result = array();
        $url = $this->API("channels?part=brandingSettings,snippet&id=" . $this->safe($channel_id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['name'] = $data->items[0]->snippet->title;
                $result['description'] = $data->items[0]->snippet->description;
                $result['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[0]->snippet->publishedAt));
                $result['thumbnails']['default'] = $data->items[0]->snippet->thumbnails->default->url;
                $result['thumbnails']['medium'] = $data->items[0]->snippet->thumbnails->medium->url;
                $result['thumbnails']['high'] = $data->items[0]->snippet->thumbnails->high->url;
                $result['banners'] = $data->items[0]->brandingSettings->image;
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get channel statistics by ID
    public function channel_statistics($channel_id) {
        $result = array();
        $url = $this->API("channels?part=statistics&id=" . $this->safe($channel_id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['videos_count'] = $data->items[0]->statistics->videoCount;
                $result['comments_count'] = $data->items[0]->statistics->commentCount;
                $result['views_count'] = $data->items[0]->statistics->viewCount;
                $result['subscribers_count'] = $data->items[0]->statistics->subscriberCount;
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get channel playlists by ID
    public function channel_playlists($channel_id, $count = 2) {
        $result = array();
        $url = $this->API("playlists?part=snippet%2CcontentDetails&channelId=" . $this->safe($channel_id) . "&maxResults=$count&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['playlists_count'] = $data->pageInfo->totalResults;
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result['playlists'][$x]['id'] = $data->items[$x]->id;
                        $result['playlists'][$x]['title'] = $data->items[$x]->snippet->title;
                        $result['playlists'][$x]['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->publishedAt));
                        $result['playlists'][$x]['video_count'] = $data->items[$x]->contentDetails->itemCount;
                        $result['playlists'][$x]['url'] = 'https://www.youtube.com/playlist?list=' . $data->items[$x]->id;
                        $result['playlists'][$x]['description'] = $data->items[$x]->snippet->description;
                        $result['playlists'][$x]['thumbnails']['default'] = $data->items[$x]->snippet->thumbnails->default->url;
                        $result['playlists'][$x]['thumbnails']['medium'] = $data->items[$x]->snippet->thumbnails->medium->url;
                        $result['playlists'][$x]['thumbnails']['high'] = $data->items[$x]->snippet->thumbnails->high->url;
                    }
                }
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get channel videos by ID
    public function channel_videos($channel_id, $count = 30) {
        $result = array();
        $url = $this->API("channels?id=" . $this->safe($channel_id) . "&part=snippet,contentDetails,statistics&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                foreach ($data->items as $id) {
                    $ID = $id->contentDetails->relatedPlaylists->uploads;
                    $result = $this->playlist_videos($ID, $count);
                }
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get playlists video by ID
    public function playlist_videos($playlist_id, $count = 30) {
        $result = array();
        $url = $this->API("playlistItems?part=snippet%2CcontentDetails&maxResults=$count&playlistId=" . $this->safe($playlist_id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['videos_count'] = $data->pageInfo->totalResults;
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $video_data = @file_get_contents("http://www.youtube.com/get_video_info?video_id=" . $data->items[$x]->contentDetails->videoId);
                        if ($video_data) {
                            parse_str($video_data);
                        }
                        $result['videos'][$x] = $this->video_info($data->items[$x]->contentDetails->videoId);
                    }
                }
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get Search on Channel By ID
    public function channel_search($channel_id, $keyword = '', $count = 2) {
        $result = array();
        $keyword = str_replace(' ', '+', $keyword);
        $url = $this->API("search?channelId=" . $this->safe($channel_id) . "&part=id&order=date&maxResults=$count&q=$keyword&type=video&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result[$x] = $this->video_info($data->items[$x]->id->videoId);
                    }
                }
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get Search on Youtube Videos
    public function public_search($keyword = '', $count = 2) {
        $result = array();
        $keyword = str_replace(' ', '+', $keyword);
        $url = $this->API("search?part=id&order=date&maxResults=$count&q=$keyword&type=video&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result[$x] = $this->video_info($data->items[$x]->id->videoId);
                    }
                }
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get Populars on Channel By ID
    public function channel_popular($channel_id, $count = 2) {
        $result = array();
        $url = $this->API("search?channelId=" . $this->safe($channel_id) . "&part=id&order=viewCount&maxResults=$count&type=video&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result[$x] = $this->video_info($data->items[$x]->id->videoId);
                    }
                }
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get Realted Videos By Video ID
    public function related_video($id, $count = 2) {
        $result = array();
        $url = $this->API("search?part=snippet&relatedToVideoId=" . $this->safe($id) . "&type=video&maxResults=$count&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result[$x] = $this->video_info($data->items[$x]->id->videoId);
                    }
                }
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get Comments By Video ID
    public function video_comments($id, $count = 2) {
        $result = array();
        $url = $this->API("commentThreads?part=snippet%2Creplies&maxResults=$count&videoId=" . $this->safe($id) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result['comments_count'] = $data->pageInfo->totalResults;
                for ($x = 0; $x < $count; $x++) {
                    if (!empty($data->items[$x])) {
                        $result['comments'][$x]['id'] = $data->items[$x]->id;
                        $result['comments'][$x]['text'] = $data->items[$x]->snippet->topLevelComment->snippet->textDisplay;
                        $result['comments'][$x]['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->topLevelComment->snippet->publishedAt));
                        $result['comments'][$x]['updated_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->topLevelComment->snippet->updatedAt));
                        $result['comments'][$x]['author']['name'] = $data->items[$x]->snippet->topLevelComment->snippet->authorDisplayName;
                        $result['comments'][$x]['author']['image'] = $data->items[$x]->snippet->topLevelComment->snippet->authorProfileImageUrl;
                        $result['comments'][$x]['author']['channel_url'] = $data->items[$x]->snippet->topLevelComment->snippet->authorChannelUrl;
                        $result['comments'][$x]['author']['googleplus_url'] = $data->items[$x]->snippet->topLevelComment->snippet->authorGoogleplusProfileUrl;
                        $result['comments'][$x]['like_count'] = $data->items[$x]->snippet->topLevelComment->snippet->likeCount;
                        if (!empty($data->items[$x]->replies->comments)) {
                            for ($i = 0; $i <= $data->items[$x]->snippet->totalReplyCount; $i++) {
                                $result['comments'][$x]['replies'][$i]['id'] = $data->items[$x]->id;
                                $result['comments'][$x]['replies'][$i]['text'] = $data->items[$x]->snippet->topLevelComment->snippet->textDisplay;
                                $result['comments'][$x]['replies'][$i]['published_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->topLevelComment->snippet->publishedAt));
                                $result['comments'][$x]['replies'][$i]['updated_date'] = date('d/m/Y H:i:s', strtotime((string) $data->items[$x]->snippet->topLevelComment->snippet->updatedAt));
                                $result['comments'][$x]['replies'][$i]['author']['name'] = $data->items[$x]->snippet->topLevelComment->snippet->authorDisplayName;
                                $result['comments'][$x]['replies'][$i]['author']['image'] = $data->items[$x]->snippet->topLevelComment->snippet->authorProfileImageUrl;
                                $result['comments'][$x]['replies'][$i]['author']['channel_url'] = $data->items[$x]->snippet->topLevelComment->snippet->authorChannelUrl;
                                $result['comments'][$x]['replies'][$i]['author']['googleplus_url'] = $data->items[$x]->snippet->topLevelComment->snippet->authorGoogleplusProfileUrl;
                                $result['comments'][$x]['replies'][$i]['like_count'] = $data->items[$x]->snippet->topLevelComment->snippet->likeCount;
                            }
                        }
                    }
                }
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

	public function get_video_info($video_id){
		$info = file_get_contents("http://www.youtube.com/get_video_info?video_id=" . $this->safe($video_id) . "&el=detailpage&asv=3");
//		$info = file_get_contents('http://www.youtube.com/get_video_info?&video_id='.$video_id.'&asv=3&el=detailpage&hl=en_US');
		$info_arr = array();
		parse_str($info, $info_arr);
		return $info_arr;

	}

    // Get video Data by ID
    public function video_info($id) {
        $url = $this->API("videos?id=" . $this->safe($id) . "&key=" . $this->Key . "&part=snippet,contentDetails,statistics,status");
        $result = array();
        $json = @file_get_contents("http://www.youtube.com/get_video_info?video_id=" . $this->safe($id) . "");
        $video_json = @file_get_contents($url);
        $data = json_decode($video_json);
        //die($this->dumb_array($data));
        if ($json and $data) {
			$video_id = $data->items[0]->id;
			$title = $data->items[0]->snippet->title;
			$view_count = $data->items[0]->statistics->viewCount;
			$author = $data->items[0]->snippet->channelTitle;
            parse_str($json);
            $result['id'] = isset($video_id) ? $video_id : '';
            $result['channel_id'] = $data->items[0]->snippet->channelId;
            $result['category_id'] = $data->items[0]->snippet->categoryId;
            $result['title'] = isset($title) ? $title : '';
            $result['url'] = isset($video_id) ? 'https://www.youtube.com/watch?v=' . $video_id : '';
            $result['published_date'] = date('d/m/Y H:i:s', isset($timestamp) ? $timestamp : 0);
            $result['view_count'] = isset($view_count) ? $view_count : '';
            $result['rating_count'] = isset($avg_rating) ? $avg_rating : '';
            $result['like_count'] = $data->items[0]->statistics->likeCount;
            $result['dislike_count'] = $data->items[0]->statistics->dislikeCount;
            $result['favorite_count'] = $data->items[0]->statistics->favoriteCount;
            $result['comment_count'] = $data->items[0]->statistics->commentCount;
            $result['duration'] = $this->getDurationSeconds($data->items[0]->contentDetails->duration);
            $result['privacy_status'] = $data->items[0]->status->privacyStatus;
            $result['author'] = isset($author) ? $author : '';
            $result['keywords'] = isset($keywords) ? $keywords : '';
            $result['description'] = $data->items[0]->snippet->description;
            $result['tags'] = (isset($data->items[0]->snippet->tags)) ? $data->items[0]->snippet->tags : array();
            $result['thumbnails']['default'] = isset($data->items[0]->snippet->thumbnails->medium->url) ? $data->items[0]->snippet->thumbnails->medium->url : '';
            $result['thumbnails']['medium'] = isset($data->items[0]->snippet->thumbnails->high->url) ? $data->items[0]->snippet->thumbnails->high->url : '';
            $result['thumbnails']['high'] = isset($data->items[0]->snippet->thumbnails->standard->url) ? $data->items[0]->snippet->thumbnails->standard->url : '';
            $result['thumbnails']['hd'] = isset($data->items[0]->snippet->thumbnails->maxres->url) ? $data->items[0]->snippet->thumbnails->maxres->url : '';
            if (isset($url_encoded_fmt_stream_map)) {
                $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
                if (count($my_formats_array) != 0) {
                    $avail_formats[] = '';
                    $i = 0;
                    $ipbits = $ip = $itag = $sig = $quality = '';
                    $expire = time();
                    foreach ($my_formats_array as $format) {
                        parse_str($format);
                        $avail_formats[$i]['itag'] = $itag;
                        $avail_formats[$i]['quality'] = $quality;
                        $type = explode(';', $type);
                        $avail_formats[$i]['type'] = $type[0];
                        $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
                        parse_str(urldecode($url));
                        $avail_formats[$i]['expire'] = date("G:i:s T", $expire);
                        $avail_formats[$i]['ipbits'] = $ipbits;
                        $avail_formats[$i]['ip'] = $ip;
                        $i++;
                    }
                    for ($i = 0; $i < count($avail_formats); $i++) {
                        $result['formats'][$avail_formats[$i]['quality'] . '-' . str_replace('video/', '', $avail_formats[$i]['type'])]['itag'] = $avail_formats[$i]['itag'];
                        $result['formats'][$avail_formats[$i]['quality'] . '-' . str_replace('video/', '', $avail_formats[$i]['type'])]['quality'] = $avail_formats[$i]['quality'];
                        $result['formats'][$avail_formats[$i]['quality'] . '-' . str_replace('video/', '', $avail_formats[$i]['type'])]['type'] = $avail_formats[$i]['type'];
                        $result['formats'][$avail_formats[$i]['quality'] . '-' . str_replace('video/', '', $avail_formats[$i]['type'])]['url'] = $avail_formats[$i]['url'];
                    }
                }
            }
            $data = explode('&', $json);
            return($result);
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Download video by ID
    public function download_video($id) {
        $result = array();
        $json = @file_get_contents("http://www.youtube.com/get_video_info?video_id=" . $this->safe($id) . "");
        if ($json) {
            parse_str($json);
            if (isset($url_encoded_fmt_stream_map)) {
                $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
                if (count($my_formats_array) != 0) {
                    $avail_formats = [];
                    $i = 0;
                    $ipbits = $ip = $itag = $sig = $quality = '';
                    $expire = time();
                    foreach ($my_formats_array as $format) {
                        parse_str($format);
                        $type = explode(';', $type);
                        $avail_formats[$quality . '-' . str_replace('video/', '', $type[0])]['itag'] = $itag;
                        $avail_formats[$quality . '-' . str_replace('video/', '', $type[0])]['quality'] = $quality;
                        $avail_formats[$quality . '-' . str_replace('video/', '', $type[0])]['type'] = $type[0];
                        $avail_formats[$quality . '-' . str_replace('video/', '', $type[0])]['url'] = urldecode($url) . '&signature=' . $sig;
                        parse_str(urldecode($url));
                        $avail_formats[$quality . '-' . str_replace('video/', '', $type[0])]['expire'] = date("G:i:s T", $expire);
                        $avail_formats[$quality . '-' . str_replace('video/', '', $type[0])]['ipbits'] = $ipbits;
                        $avail_formats[$quality . '-' . str_replace('video/', '', $type[0])]['ip'] = $ip;
                        $i++;
                    }

                    foreach ($avail_formats as $key => $value) {
                        unset(
                                $avail_formats[$key]['itag'], $avail_formats[$key]['expire'], $avail_formats[$key]['ipbits'], $avail_formats[$key]['ip']
                        );
                    }
                    $avail_formats['title'] = $title;
                }
            }
            return($avail_formats);
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Video Player
    public function iframe_video_player($url, $width = '550', $height = '300') {
        $url = trim($url);
        $player = '';
        if ($this->checkServer(array("youtube.com", "youtu.be"), $url)) {
            $player = '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $this->videoID_byUrl($url) . '" frameborder="0" allowfullscreen></iframe>';
        } else {
            $player = '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $url . '" frameborder="0" allowfullscreen></iframe>';
        }
        return $player;
    }

    // Video Player
    public function html_video_player($url, $width = '550', $height = '300') {
        $url = trim($url);
        $player = '';
        if ($this->checkServer(array("youtube.com", "youtu.be"), $url)) {
            $video = $this->video_info($this->videoID_byUrl($url));
            $player = '<video width="' . $width . '" height="' . $height . '" poster="' . $video['thumbnails']['medium'] . '" controls>
                          <source src="' . $video['formats']['medium-mp4']['url'] . '" type="video/mp4">
                          Your browser does not support the video tag.
                      </video>';
        } else {
            $video = $this->video_info($url);
            $player = '<video width="' . $width . '" height="' . $height . '" poster="' . $video['thumbnails']['medium'] . '" controls>
                          <source src="' . $video['formats']['medium-mp4']['url'] . '" type="video/mp4">
                          Your browser does not support the video tag.
                      </video>';
        }
        return $player;
    }

    // Get channel ID by user name
    public function channelID_byUsername($username) {
        $result = '';
        $url = $this->API("channels?part=snippet&forUsername=" . $this->safe($username) . "&key=" . $this->Key);
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json);
            if ($data->items) {
                $result = $data->items[0]->id;
                return($result);
            } else {
                $this->Error = 'Empty data';
                $this->error();
            }
        } else {
            //debug_backtrace(); 
            $this->Error = 'Error in get data';
            $this->error();
        }
    }

    // Get playlist ID by URL
    public function playlistID_byUrl($url) {
        $query = parse_url($url);
        parse_str($query['query'], $id);
        return (isset($id['list'])) ? $id['list'] : NULL;
    }

    // Get video ID by URL
    public function videoID_byUrl($url) {
        parse_str(parse_url($url, PHP_URL_QUERY), $id);
        return (isset($id['v'])) ? $id['v'] : NULL;
    }

    // Duration Seconds
    public function getDurationSeconds($duration) {
        preg_match_all('/(\d+)/', $duration, $parts);
        // Put in zeros if we have less than 3 numbers.
        if (count($parts[0]) == 1) {
            array_unshift($parts[0], "0", "0");
        } elseif (count($parts[0]) == 2) {
            array_unshift($parts[0], "0");
        }
        $sec_init = $parts[0][2];
        $seconds = $sec_init % 60;
        $seconds_overflow = floor($sec_init / 60);
        $min_init = $parts[0][1] + $seconds_overflow;
        $minutes = ($min_init) % 60;
        $minutes_overflow = floor(($min_init) / 60);
        $hours = $parts[0][0] + $minutes_overflow;
        if ($hours != 0)
            return $hours . ':' . $minutes . ':' . $seconds;
        else
            return $minutes . ':' . $seconds;
    }

    // Dumb array
    public function dumb_array($array) {
        echo '<pre style="overflow:auto; width:100%;">';
        print_r($array);
        echo '</pre>';
    }

    // safe values
    private function safe($value) {
        return trim(htmlspecialchars($value));
    }

    // check server name
    private function checkServer($domains = array(), $url) {
        foreach ($domains as $domain) {
            if (strpos($url, $domain) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Show errors when function can't get data
    public function error() {
        if ($this->Error)
            echo('<div class="yt-error" style="padding:15px;color:red;margin:10px;border:1px solid red;border-radius:2px;">' . $this->Error . '</div>');
    }

}

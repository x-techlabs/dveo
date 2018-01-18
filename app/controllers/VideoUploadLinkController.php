<?php
require public_path().'/aws_sdk/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class VideoUploadLinkController extends BaseController {

    public function getAwsBuckets()
    {
        $client = S3Client::factory(array(
            'key'    => Input::get('key'),
            'secret' => Input::get('secret')
        ));

        $result = $client->listBuckets();
        $out = array();
        foreach($result['Buckets'] as $bucket) $out[] = $bucket['Name'];
        print implode(',', $out);
    }

    public function getDacastBuckets()
    {
        $out = array();
        // http://api.dacast.com/v2/vod?apikey=81410_4ac9282ddbcd4d172f31&_format=json
        $jsonData = json_decode(file_get_contents("http://api.dacast.com/v2/vod?apikey=".Input::get('key')."&_format=json"));
        if (is_array($jsonData->data))
        {
            foreach($jsonData->data as $record) { $out[]=$record->id;  $out[]=$record->title; }
        }
        print implode('^', $out);
    }

    public function getWistiaBuckets()
    {
        $out = array();
        $url = "https://api.wistia.com/v1/projects.json?api_password=".Input::get('key')."&per_page=100&sort_by=name";
        $jsonData = json_decode( file_get_contents($url) );
        if (is_array($jsonData))
        {
            foreach($jsonData as $record) { $out[]=$record->id;  $out[]=$record->name; }
        }
        print implode('^', $out);
    }

    public function getFilesFromBucket()
    {
        $client = S3Client::factory(array(
            'key'    => Input::get('key'),
            'secret' => Input::get('secret')
        ));

        $iterator = $client->getIterator('ListObjects', array('Bucket' => Input::get('bucketname')) );

        $out2 = array();
        $out = array();
        foreach($iterator as $obj) 
        {
            $ext = pathinfo($obj['Key'], PATHINFO_EXTENSION);
            if ($ext == 'mp4') 
            {
                $out[] = $obj['Key'];
                if (count($out) >= 50) {  $out2[] = implode(',', $out);  $out = array();  }
            }
        }
        if (count($out) > 0) $out2[] = implode(',', $out);

        if (Input::get('createCat')=='1')
        {
            $collection = new Collections;
            $collection->title = Input::get('bucketname');
            $collection->channel_id = BaseController::get_channel_id();
            $collection->save();
        }
        print implode('^', $out2);
    }

    public function HMSToSec($duration)
    {
        $tparts = explode(':', $duration);
        $seconds = round($tparts[0] * 3600 + $tparts[1] * 60 + $tparts[2]);
        return $seconds;
    }

    public function createVideosFromWistia($channelID, $dacastRec)
    {
        /*
    [0] => stdClass Object
        (
            [id] => 18038858
            [name] => 0001_ID_TUYO_RAQ-C_SPA
            [type] => Video
            [created] => 2016-02-04T04:10:20+00:00
            [updated] => 2016-09-25T04:32:29+00:00
            [duration] => 10.097
            [hashed_id] => mtz84j6ait
            [description] => 
            [progress] => 1
            [status] => ready
            [thumbnail] => stdClass Object
                (
                    [url] => https://embed-ssl.wistia.com/deliveries/bca07442728b5702ab2edc737d6b0adbe7f46ec9.jpg?image_crop_resized=200x120
                    [width] => 200
                    [height] => 120
                )

            [section] => Tuyo IDs 
            [project] => stdClass Object
                (
                    [id] => 1806869
                    [name] => TuYo TV
                    [hashed_id] => mcy1cnmtjh
                )

            [assets] => Array
                (
                    [0] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/02e1d0edf690a615e4f6a84ba66fd6fa2c7bbcce.bin
                            [width] => 1280
                            [height] => 720
                            [fileSize] => 3815564
                            [contentType] => video/mp4
                            [type] => OriginalFile
                        )

                    [1] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/d6d55e450153833aabcaff57c200ea90221376e9.bin
                            [width] => 640
                            [height] => 360
                            [fileSize] => 1015555
                            [contentType] => video/mp4
                            [type] => IphoneVideoFile
                        )

                    [2] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/e4db12177846016a3aa495111c7ff3a8fdb8aa8b.bin
                            [width] => 640
                            [height] => 360
                            [fileSize] => 1020004
                            [contentType] => video/x-flv
                            [type] => FlashVideoFile
                        )

                    [3] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/4cf873de19cfdc16644a9ded0bf941e5afc0d00d.bin
                            [width] => 400
                            [height] => 224
                            [fileSize] => 423475
                            [contentType] => video/x-flv
                            [type] => FlashVideoFile
                        )

                    [4] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/b6bb079f50a0368a9ff92b4b2170598eaa1a94dd.bin
                            [width] => 400
                            [height] => 224
                            [fileSize] => 421418
                            [contentType] => video/mp4
                            [type] => Mp4VideoFile
                        )

                    [5] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/438249507bde0cbce453c5463dd3a386a63fbe1f.bin
                            [width] => 960
                            [height] => 540
                            [fileSize] => 1616136
                            [contentType] => video/x-flv
                            [type] => MdFlashVideoFile
                        )

                    [6] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/80bba9d2b92e2769576a11a06bc7177f6ff042d3.bin
                            [width] => 960
                            [height] => 540
                            [fileSize] => 1614119
                            [contentType] => video/mp4
                            [type] => MdMp4VideoFile
                        )

                    [7] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/dad694638ff93f412103097c494a9f5efd679c46.bin
                            [width] => 1280
                            [height] => 720
                            [fileSize] => 3278004
                            [contentType] => video/x-flv
                            [type] => HdFlashVideoFile
                        )

                    [8] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/5480ced8ff30f13d7bd206f6d2b0756db06cd9a0.bin
                            [width] => 1280
                            [height] => 720
                            [fileSize] => 3275971
                            [contentType] => video/mp4
                            [type] => HdMp4VideoFile
                        )

                    [9] => stdClass Object
                        (
                            [url] => http://embed.wistia.com/deliveries/bca07442728b5702ab2edc737d6b0adbe7f46ec9.bin
                            [width] => 1280
                            [height] => 720
                            [fileSize] => 58674
                            [contentType] => image/jpeg
                            [type] => StillImageFile
                        )

                )

            [embedCode] => <object id="wistia_18038858" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="960" height="540"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="wmode" value="opaque" /><param name="flashvars" value="videoUrl=http://embed.wistia.com/deliveries/438249507bde0cbce453c5463dd3a386a63fbe1f.bin&stillUrl=http://embed.wistia.com/deliveries/bca07442728b5702ab2edc737d6b0adbe7f46ec9.bin&playButtonVisible=true&controlsVisibleOnLoad=false&unbufferedSeek=true&autoLoad=false&autoPlay=false&endVideoBehavior=default&embedServiceURL=http://distillery.wistia.com/x&accountKey=wistia-production_358152&mediaID=wistia-production_18038858&mediaDuration=10.097&hdUrl=http://embed.wistia.com/deliveries/dad694638ff93f412103097c494a9f5efd679c46.bin" /><param name="movie" value="http://embed.wistia.com/flash/embed_player_v2.0.swf" /><embed src="http://embed.wistia.com/flash/embed_player_v2.0.swf" name="wistia_18038858" type="application/x-shockwave-flash" width="960" height="540" allowfullscreen="true" allowscriptaccess="always" wmode="opaque" flashvars="videoUrl=http://embed.wistia.com/deliveries/438249507bde0cbce453c5463dd3a386a63fbe1f.bin&stillUrl=http://embed.wistia.com/deliveries/bca07442728b5702ab2edc737d6b0adbe7f46ec9.bin&playButtonVisible=true&controlsVisibleOnLoad=false&unbufferedSeek=true&autoLoad=false&autoPlay=false&endVideoBehavior=default&embedServiceURL=http://distillery.wistia.com/x&accountKey=wistia-production_358152&mediaID=wistia-production_18038858&mediaDuration=10.097&hdUrl=http://embed.wistia.com/deliveries/dad694638ff93f412103097c494a9f5efd679c46.bin"></embed></object><script src="http://embed.wistia.com/embeds/v.js"></script><script>if(!navigator.mimeTypes['application/x-shockwave-flash'] || navigator.userAgent.match(/Android/i)!==null)Wistia.VideoEmbed('wistia_18038858','960','540',{videoUrl:'http://embed.wistia.com/deliveries/b6bb079f50a0368a9ff92b4b2170598eaa1a94dd.bin',stillUrl:'http://embed.wistia.com/deliveries/bca07442728b5702ab2edc737d6b0adbe7f46ec9.bin',distilleryUrl:'http://distillery.wistia.com/x',accountKey:'wistia-production_358152',mediaId:'wistia-production_18038858',mediaDuration:10.097})</script>
        )
        */
        $video = new Video;
        $video->title = $dacastRec->name;
        $video->description = $dacastRec->description;
        foreach($dacastRec->assets as $a)
        {
            if ($a->type == 'HdMp4VideoFile' || $a->type == 'MdMp4VideoFile')
            {
                $video->file_name = str_replace(".bin", "/my_file.mp4", $a->url);
                $video->hd_width = $a->width;
                $video->hd_height = $a->height;
            }
        }

        $video->job_id = $dacastRec->id;
        $video->video_format = "MP4";
        $video->channel_id = $channelID;
        $video->storage = '';
        $video->source = "wistia";
        $video->duration = $dacastRec->duration;
        $video->thumbnail_name = $dacastRec->thumbnail->url;

        $video->save();
        return $video->id;
    }

    public function createVideosFromDacast($channelID, $dacastRec)
    {
        /*
        {"abitrate":256000,
        "acodec":"AAC LC",
        "associated_packages":"",
        "category_id":20,
        "container":"MPEG-4",
        "countries_id":0,
        "creation_date":"2016-12-27 10:08:43",
        "custom_data":null,
        "noframe_security":0,
        "description":"",
        "disk_usage":"587994247",
        "duration":"00:28:30",
        "autoplay":true,
        "enable_coupon":false,
        "online":true,
        "enable_payperview":false,
        "publish_on_dacast":true,
        "enable_subscription":false,
        "external_video_page":"http:\/\/",
        "filename":"81410_358783.raw",
        "filesize":587994247,
        "google_analytics":0,
        "group_id":0,
        "hds":"http:\/\/vod-04.dacast.com\/z\/secure\/81410\/81410_,358783.raw,.csmil\/manifest.f4m?hdcore=2.11.3",
        "hls":"http:\/\/vod-04.dacast.com\/i\/secure\/81410\/81410_,358783.raw,.csmil\/master.m3u8",
        "id":358783,
        "is_secured":true,
        "original_id":358783,
        "password":null,
        "pictures":{"thumbnail":["https:\/\/images.dacast.com\/81410\/tf-358783-1.png?1483005984","https:\/\/images.dacast.com\/81410\/tf-358783-2.png?1483005984","https:\/\/images.dacast.com\/81410\/tf-358783-3.png?1483005984","https:\/\/images.dacast.com\/81410\/tf-358783-4.png?1483005984"],"splashscreen":["https:\/\/images.dacast.com\/81410\/sf-358783-1.png?1483005984","https:\/\/images.dacast.com\/81410\/sf-358783-2.png?1483005984","https:\/\/images.dacast.com\/81410\/sf-358783-3.png?1483005984","https:\/\/images.dacast.com\/81410\/sf-358783-4.png?1483005984"]},
        "player_height":0,
        "player_width":0,
        "player_size_id":23,
        "referers_id":0,
        "save_date":"2016-12-27 10:08:43",
        "share_code":{"facebook":"https:\/\/iframe.dacast.com\/b\/81410\/f\/358783","twitter":"https:\/\/iframe.dacast.com\/b\/81410\/f\/358783","gplus":"https:\/\/iframe.dacast.com\/b\/81410\/f\/358783"},
        "splashscreen_id":1,
        "streamable":0,
        "subtitles":null,
        "template_id":0,
        "theme_id":1,
        "thumbnail_id":1,
        "title":"The Great Outdoors - 2015 Canadian Bears Episode _",
        "vbitrate":2500000,
        "vcodec":"AVC",
        "video_height":720,
        "video_width":1280},
        */


        $video = new Video;
        $video->title = $dacastRec->title;
        $video->description = $dacastRec->description;
        $video->file_name = $dacastRec->share_code->facebook;
//        $video->file_name = $dacastRec->hls;
//        $video->job_id = $dacastRec->id;
        $video->video_format = "MP4";
        $video->channel_id = $channelID;
        $video->storage = '';
        $video->source = "dacast";
        $video->duration = $this->HMSToSec($dacastRec->duration);
        $video->hd_width = $dacastRec->video_width;
        $video->hd_height = $dacastRec->video_height;
        $video->thumbnail_name = $dacastRec->pictures->thumbnail[0];

        $video->save();
    }

    public function createVideosFromAws($channelID, $bucketname, $f)
    {
        $video = new Video;
        $video->title = basename($f, '.mp4');
        $video->description = '';
        $video->file_name = "https://".$bucketname.".s3.amazonaws.com/".$f;
        $video->job_id = '';
        $video->video_format = "MP4";
        $video->channel_id = $channelID;
        $video->storage = '';
        $video->source = "aws";
        //$metadata = $this->GetMetaDataOfVideo($video->file_name);
        //$video->duration = $metadata;
        //$video->hd_width = $vimeo['width'];
        //$video->hd_height = $vimeo['height'];
        //$video->job_id = $vimeo['id'];
        $video->thumbnail_name = '';

        $video->save();
    }

    public function awsCreateVideos()
    {
        $channelID = BaseController::get_channel_id();
        $files = explode(',', Input::get('filelist'));

        $bucketname = Input::get('bucketname');
        foreach($files as $f)
        {
            $this->createVideosFromAws($channelID, $bucketname, $f);

            if (Input::get('createCat') != '1') continue;

            $parts = explode('/', $f);
            if (count($parts) <= 1) continue;

            array_pop($parts);  // filename is removed
            $foldername = array_pop($parts);  // folder name is category name

            $pid = 0;
            $coll = Collections::where('title','=',$foldername)->where('channel_id','=',$channelID)->first();
            if (!is_object($coll))
            {
                $collection = new Collections;
                $collection->title = $foldername;
                $collection->channel_id = BaseController::get_channel_id();
                if ($collection->save()) $pid = $collection->id;
            }
            else $pid = $coll->id;

            if ($pid > 0)
            {
                $vc = new Videos_in_collections;
                $vc->video_id = $video->id;
                $vc->collection_id = $pid;
                $vc->save();
            }
        }
    }

    public function dacastCreateVideos()
    {
        $channelID = BaseController::get_channel_id();
        $files = explode(',', Input::get('filelist'));

        $jsonData = json_decode(file_get_contents("http://api.dacast.com/v2/vod?apikey=".Input::get('key')."&_format=json"));
        if (is_array($jsonData->data))
        {
            foreach($files as $did)
            {
                foreach($jsonData->data as $record) 
                { 
                    if ($did != $record->id) continue;
                    $this->createVideosFromDacast($channelID, $record);
                    break;
                }
            }
        }
    }

    public function GetLastSortOrder($channelID)
    {
    	$nodes = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', '0')
    	                              ->where('type','=','1')
                                      ->orderBy('sort_order', 'desc')
    	                              ->get();

        //Log::info("nodes => ".print_r($nodes, true));  
        foreach($nodes as $node)
        {
            $app = TvappPlaylist::find($node->video_id);
            if (!is_object($app)) continue;
            if ($app->channel_id != $channelID) continue;
            return $node->sort_order;
        }
        return 0;
    }


    public function wistiaCreateVideos()
    {
        $channelID = BaseController::get_channel_id();
        $files = explode(',', Input::get('filelist'));
        $createCat = Input::get('createCat');

        $catArray = array();

        foreach($files as $project_id)
        {
            $url = "https://api.wistia.com/v1/medias.json?api_password=".Input::get('key')."&per_page=100&sort_by=name&type=video&project_id=".$project_id;
            $jsonData = json_decode(file_get_contents($url));
            if (is_array($jsonData))
            {
                foreach($jsonData as $record) 
                { 
                    if ($record->status != 'ready') continue;

                    $mySection = -1;
                    if ($createCat==1 && $record->section != '')
                    {
                        for ($k = 0 ; $k < count($catArray) ; $k++) 
                            if ($catArray[$k][0] == $record->section) { $mySection = $k; break; }

                        if ($mySection==-1)
                        {
                            $tvapp = new TvappPlaylist();
                            $tvapp->title = $record->section;
                            $tvapp->channel_id = $channelID;
                            $tvapp->thumbnail_name = "http://prolivestream.s3.amazonaws.com/logos/channel_".$channelID;

                            $tvapp->save();

                            $mySection = count($catArray);
                            $catArray[] = array($record->section, $tvapp->id, array());
                        }
                    }

                    $vid = $this->createVideosFromWistia($channelID, $record);
                    if ($mySection != -1) $catArray[$mySection][2][] = $vid;
                }
            }
        }

        if (Input::get('createTree')=='1')
        {
            $so = $this->GetLastSortOrder($channelID);

            // Save all categories in tree
            foreach($catArray as $x => $cat)
            {
                $t = new TvappVideo_in_playlist();
                $t->video_id = $cat[1];
                $t->tvapp_playlist_id = 0;
                $t->sort_order = $so + $x + 1;
                $t->type = 1;
                $t->save();
            }

            foreach($catArray as $cat)
            {
                foreach($cat[2] as $x => $vid)
                {
                    $t = new TvappVideo_in_playlist();
                    $t->video_id = $vid;
                    $t->tvapp_playlist_id = $cat[1];
                    $t->sort_order = $x + 1;
                    $t->type = 0;
                    $t->save();
                }
            }
        }
    }
}
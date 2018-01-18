<?php
require public_path().'/aws_sdk/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class AwsController extends BaseController {


    public function index() 
    {
        $channel_id = BaseController::get_channel_id();
        $client = S3Client::factory(array(
            'key'    => 'AKIAIMCQJSKDT4QOHJVA',
            'secret' => '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'
        ));

        $result = $client->listBuckets();
        $this->data['buckets'] = $result['Buckets'];

        $objects = array();
        foreach($result['Buckets'] as $bucket)
        {
            $iterator = $client->getIterator('ListObjects', array('Bucket' => $bucket['Name']) );
            $objects[] = $iterator;

            foreach($iterator as $it)
                $keys = array_keys($it);
        }
        $this->data['bucketObjects'] = $objects;
        $this->data['keys'] = $keys;

        return $this->render('analytics/aws');
    }

    public function getBuckets()
    {
        if (Input::get('source')=='aws')
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
        else if (Input::get('source')=='dacast')
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

    public function GetVideoDuration($url)
    {
        $command = escapeshellcmd("avconv -i $url");
        $out = shell_exec($command." 2>&1");
        //print "[$out]";
        $pos = strpos($out, 'Duration:');
        if ($pos !== false)
        {
            $duration = substr($out, $pos+9);
            $pos = strpos($duration, ',');
            $duration = substr($duration, 0, $pos);
            return $this->HMSToSec($duration);
        }
        return 0;
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
        $video->file_name = $dacastRec->hls;
        $video->job_id = $dacastRec->id;
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

    public function createVideos()
    {
        $channelID = BaseController::get_channel_id();
        $files = explode(',', Input::get('filelist'));

        if (Input::get('source')=='aws')
        {
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
        else if (Input::get('source')=='dacast')
        {
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
    }
}
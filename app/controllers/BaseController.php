<?php

class BaseController extends Controller
{

    /**
     * Current channel
     *
     * @var Channel
     */
    private static $channel = null;

    /**
     * Current DVEO
     *
     * @var DVEO
     */
    private static $dveo = null;

    /**
     * Data to send to view
     *
     * @var array
     */
    protected $data = [
        'channel' => [],
        'title' => 'Dveo',
    ];

    /**
     * Setting channel id
     *
     * @param $channel_id
     */
    public static function set_channel_id($channel_id)
    {

        // Get channel info
        self::$channel = Channel::find($channel_id);

        //
        Config::set('app.timezone', 'Asia/Yerevan');
//        echo Config::get('app.timezone');
//        exit;

        // If channel does exist
        if (is_null(self::$channel)) App::abort(404);
    }

    /**
     * Setting channel
     *
     * @param Channel $channel
     */
    public static function set_channel($channel)
    {

        // Get channel info
        self::$channel = $channel;

        // If channel does exist
        if (is_null(self::$channel)) App::abort(404);
    }

    /**
     * Getting channel id
     *
     * @return int
     */
    public static function get_channel_id()
    {
        return self::$channel->id;
    }

    /**
     * Getting channel
     *
     * @return Channel
     */
    public static function get_channel()
    {
        return self::$channel;
    }

    /**
     * Getting dveo ip
     *
     * @return string
     */
    public static function get_dveo_ip()
    {
        return self::get_dveo()->get_ip_address();
    }

    /**
     * Return DVEO object of current channel
     *
     * @return DVEO
     */
    public static function get_dveo()
    {

        if (self::$dveo === null) {

            // Save instance of DVEO
            self::$dveo = DVEO::getInstance(DveoModel::find(self::$channel->dveo_id)->ip, 25599, 'apiuser', 'Hn7P67583N9m5sS');
        }

        return self::$dveo;
    }

    /**
     * Getting dveo ip
     *
     * @return int
     */
    public static function get_file_stream()
    {
        return self::$channel->stream;
    }

    /**
     * Rendering of view
     *
     * @param string $view
     *
     * @return $this
     */


    public function getAudience($token){
        $client = new GuzzleHttp\Client();
        $startTime = date('H:i:s', strtotime('-15 minutes'));;
        $endTime = date("H:i:s");
        $date = date("Y-m-d");
        $res = $client->get("https://api.streamlyzer.com:8060/rest/0.9/audience?type=active&timeunit=all&from=".$date."T".$startTime.".000Z&to=".$date."T".$endTime.".285Z&groupBy=gender&token=".$token."&timezone=America/Los_Angeles");
        $status = $res->getStatusCode();
        $resBody = json_decode($res->getBody());
        // echo "<pre>";
        if(isset($resBody->data)){
            $data = (isset($resBody->data->Female)) ? $resBody->data->Female->active[0] : $resBody->data->Male->active[0];
        }
        else{
            $data = 0;
        }
        return $data;
        // var_dump($data);die;
    }
    public function getVideoView($token){
        $client = new GuzzleHttp\Client();
        $startTime = date('H:i:s', strtotime('-15 minutes'));;
        $endTime = date("H:i:s");
        $date = date("Y-m-d");
        $res = $client->get("https://api.streamlyzer.com:8060/rest/0.9/audience?type=all&timeunit=all&from=".$date."T".$startTime.".000Z&to=".$date."T".$endTime.".285Z&groupBy=gender&token=".$token."&timezone=America/Los_Angeles");
        $status = $res->getStatusCode();
        $resBody = json_decode($res->getBody());
        // echo "<pre>";
        if(isset($resBody->data)){
            $data = (isset($resBody->data->Female)) ? $resBody->data->Female->all[0] : $resBody->data->Male->all[0];
        }
        else{
            $data = 0;
        }
        // var_dump($data);die;
        return $data;
    }
    public function getBounceRate($token){
        $client = new GuzzleHttp\Client();
        $yesterday = date("Y-m-d", strtotime("- 1 day"));
        $date = date("Y-m-d");
        // var_dump($date);die;
        $res = $client->get("https://api.streamlyzer.com:8060/rest/0.9/audience?type=bounceRate&timeunit=day&from=".$yesterday."T01:00:00.000Z&to=".$date."T13:07:38.285Z&groupBy=gender&token=".$token."&timezone=America/Los_Angeles");
        $status = $res->getStatusCode();
        $resBody = json_decode($res->getBody());
        // echo "<pre>";
        if(isset($resBody->data)){
            $data = (isset($resBody->data->Female)) ? $resBody->data->Female->bounceRate : $resBody->data->Male->bounceRate;
        }
        else{
            $data = 0;
        }
        // var_dump($data);die;
        return $data;
    }
    public function getViewHour($token){
        $client = new GuzzleHttp\Client();
        $date = date("Y-m-d");
        // $yesterday = date("Y-m-d", strtotime("- 1 day"));
        $res = $client->get("https://api.streamlyzer.com:8060/rest/0.9/engagement?type=viewhour&timeunit=day&from=".$date."T01:00:00.000Z&to=".$date."T13:12:48.780Z&token=".$token."&timezone=America/Los_Angeles");
        $status = $res->getStatusCode();
        $resBody = json_decode($res->getBody());
        // echo "<pre>";
        // var_dump($resBody);die;
        if(isset($resBody->data)){
            $data = $resBody->data->viewhour[0];
        }
        else{
            $data = 0;
        }
        return $data;
    }
    public function getComplRate($token){
        $client = new GuzzleHttp\Client();
        $date = date("Y-m-d");
        $res = $client->get("https://api.streamlyzer.com:8060/rest/0.9/engagement?type=completionRate&timeunit=day&from=".$date."T01:00:00.000Z&to=".$date."T13:10:48.780Z&token=".$token."&timezone=America/Los_Angeles");
        $status = $res->getStatusCode();
        $resBody = json_decode($res->getBody());
        $data = (isset($resBody->data)) ? $resBody->data->completionRate[0] : 0;
        // echo "<pre>";
        // var_dump($resBody);die;
        return $data;
    }

    public function render($view)
    {

        $channel = self::get_channel();
        if($channel != null){
            $streamlyzer_token = $channel->streamlyzer_token;
        }
        else{
            $streamlyzer_token = null;
        }
        if(!empty($streamlyzer_token) && isset($streamlyzer_token)){
            $audience = $this->getAudience($streamlyzer_token);
            $videoView = $this->getVideoView($streamlyzer_token);
            $bounceRate = $this->getBounceRate($streamlyzer_token);
            $viewHour = $this->getViewHour($streamlyzer_token);
            $complRate = $this->getComplRate($streamlyzer_token);
                // var_dump($audience);die;
        }
        else{
            $audience = '';
            $videoView = '';
            $bounceRate = '';
            $viewHour = '';
            $complRate = '';
        }

        // If channel exists
        if ($channel) {

            // Setting channel
            $this->data['channel'] = $channel->toArray();

            $this->data['complRate'] = $complRate;
            $this->data['viewHour'] = $viewHour;
            $this->data['bounceRate'] = $bounceRate;
            $this->data['videoView'] = $videoView;
            $this->data['audience'] = $audience;

            // Setting title of page
            $this->data['title'] = "{$this->data['channel']['title']} ― {$this->data['title']}";

            //$dveo = new DVEO(self::get_dveo_ip(), 25599, 'Hn7P67583N9m5sS');
            // $dveo = DVEO::getInstance(self::get_dveo_ip(), 25599, 'Hn7P67583N9m5sS');

            // $status = $dveo->get_stream_status(self::get_file_stream());

            //if (explode(' ', $status->GetStreamStatusResult)[1] == 'up') {
            //    $this->data['status'] = 1;
            //} else {
                $this->data['status'] = 0;
            //}

            $carbon = Carbon::now($channel['timezone']);

            $this->data['timestamp'] = $carbon->getTimestamp();
        }

        if (!is_null(self::$channel)) {
            $this->data['master_loop_playlist'] = [];

            $masterLoopPlaylist = Playlist::where('channel_id', '=', self::$channel->id)->where('type', '=', 2)->first();

            if (!is_null($masterLoopPlaylist)) {
                $this->data['master_loop_playlist'] = $masterLoopPlaylist->toArray();
            }
        }

        return View::make($view)->with($this->data);
    }
}

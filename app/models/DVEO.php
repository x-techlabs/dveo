<?php

/**
 * Class DVEO
 */
class DVEO {

    /**
     * Instances of DVEO class
     *
     * @var self[]
     */
    private static $instances = [];

    /**
     * WSDL to make requests
     *
     * @var string
     */
    private $wsdl = "";

    /**
     * IP address
     *
     * @var string
     */
    private $ip = '';

    /**
     * Password
     *
     * @var string
     */
    private $password = "";

    /**
     * Soap Client
     *
     * @var SoapClient
     */
    private $client;

    /**
     * Access key to make requests
     *
     * @var string
     */
    private $access_key = '';

    /**
     * Cache for requests
     *
     * @var array
     */
    private $cache = [];

    /**
     * FTP connection to DVEO
     *
     * @var resource|null
     */
    private $ftp_connection = null;

    /**
     * Constructor of DVEO class
     *
     * @param string $ip
     * @param int    $port
     * @param string $password
     */
    private function __construct($ip, $port, $password) {

        // Generating WSDL
        $this->wsdl = "http://{$ip}:{$port}/soap/service.asmx?wsdl";

        // Saving fields
        $this->ip = $ip;
        $this->password = $password;

        // Creating new SOAP client
        $this->client = new SoapClient($this->wsdl);

        // Getting access key
        $this->access_key = $this->get_access_key();
    }

    /**
     * Get already cached DVEO instance or add it to cache.
     * This give us a singleton like syntax for current class
     *
     * @param string $ip
     * @param int    $port
     * @param string $password
     *
     * @return DVEO
     */
    public static function getInstance($ip, $port, $password) {

        // Generating checksum of needed DVEO
        $checksum = md5("{$ip} {$port} {$password}");

        // If instance of DVEO not created yet
        if (empty(self::$instances[$checksum])) {

            // Create instance of DVEO and save in cache
            self::$instances[$checksum] = new DVEO($ip, $port, $password);
        }

        // Return already created instance of DVEO class
        return self::$instances[$checksum];
    }

    /**
     * Getting token to generate access key
     *
     * @return string
     */
    private function get_access_key() {

        // If we have access key saved in cache
//        if (Cache::has('dveo_access_key')) {
//            $access_key = Cache::get('dveo_access_key');
//
//            // Check if access key is valid
//            if ($this->client->ValidKey($access_key)->ValidKeyResult) {
//                return $access_key;
//            }
//        }

        // Get token
        $token = $this->client->HttpGetToken()->HttpGetTokenResult;

        // Generate access key
        $access_key = md5(strtoupper('apiuser') . "|{$this->password}|{$token}") . "|apiuser";

        // Saving to cache
//        Cache::add('dveo_access_key', $access_key, 4);

        return $access_key;
    }

    /**
     * Return IP address of current DVEO instance
     *
     * @return string
     */
    public function get_ip_address() {

        return $this->ip;
    }

    /**
     * Getting all streams
     *
     * @return array
     */
    public function get_all_streams() {

        // If streams is not in cache
        if (empty($this->cache['streams'])) {
            $this->cache['streams'] = $this->client->GetInputConfigList($this->access_key, '*')->GetInputConfigListResult->configItems->string;
        }

        return $this->cache['streams'];
    }

    /**
     * Getting file streams
     *
     * @return array
     */
    public function get_file_streams() {

        return array_filter(

            $this->get_all_streams(),

            function ($stream_name) {

                return substr($stream_name, 0, 4) == 'file';
            });
    }

    /**
     * Getting net streams
     *
     * @return array
     */
    public function get_net_streams() {

        return array_filter(

            $this->get_all_streams(),

            function ($stream_name) {

                return substr($stream_name, 0, 3) == 'net';
            });
    }

    /**
     * Starting stream
     *
     * @param string $stream_name
     */
    public function start_stream($stream_name) {

        $this->client->StartStream([
            'streamName' => $stream_name,
            'key' => $this->access_key
        ]);
    }

    /**
     * Starting stream
     *
     * @param string $stream_name
     */
    public function restart_stream($stream_name) {

        $this->client->RestartStream([
            'streamName' => $stream_name,
            'key' => $this->access_key
        ]);
    }

    /**
     * Stopping stream
     *
     * @param string $stream_name
     */
    public function stop_stream($stream_name) {

        $this->client->StopStream([
            'streamName' => $stream_name,
            'key' => $this->access_key
        ]);
    }

    /**
     * Restarting all streams
     *
     * @param string $stream_name
     */
    public function restart_all_streams($stream_name) {

        $this->client->RestartAllStreams([
            'streamName' => $stream_name,
            'key' => $this->access_key
        ]);
    }

    /**
     * Getting Service Status
     *
     * @param string $stream_name
     *
     * @return string
     */
    public function get_stream_status($stream_name) {

        $stream_status = $this->client->GetStreamStatus([
            'key' => $this->access_key,
            'streamName' => $stream_name
        ]);

        return $stream_status;
    }


    /**
     * Upload videos file via URL or local path
     *
     * @param int    $channel_id
     * @param string $video_path
     */
    public function upload_videos_by_group($group, $video_path, $video_name) {

        // Upload directory
        $directory = "{$group}";


        // Get the current working directory
        $origin = ftp_pwd($this->get_ftp_connection());

        // Attempt to change directory, suppress errors
        if (@ftp_chdir($this->get_ftp_connection(), $directory))
        {
            // If the directory exists, set back to origin
            //ftp_chdir($this->get_ftp_connection(), $origin);
        }else{
            // Create upload directory
            //ftp_mkdir($this->get_ftp_connection(), $directory);
        }

        // Opening video file
        $video = fopen($video_path, 'r');

        //ftp_delete($this->get_ftp_connection(), "{$directory}/{$video_name}.mp4");

        // Upload file
        //ftp_fput($this->get_ftp_connection(), "{$directory}/{$video_name}.mp4", $video, FTP_BINARY);
        ftp_fput($this->get_ftp_connection(), "{$video_name}.mp4", $video, FTP_ASCII);



        //ftp_put($this->get_ftp_connection(), "{$directory}/{$video_name}.mp4", $video_path, FTP_BINARY);

        // Closing video
        $this->close_ftp_connection();
        fclose($video);
    }

    public function upload_video_array($directory, $video_names){
        $count=0;
        $mode = FTP_ASCII; //FTP_BINARY; // or FTP_ASCII


        $conn_id = ftp_connect($this->get_ip_address());
        ftp_login($conn_id, 'ftpuser', $this->password);
        ftp_pasv($conn_id, true);
//
//        //$origin = ftp_pwd($conn_id);
        ftp_chdir($conn_id, $directory);

        //define('BUFSIZ', 1500111000);

        $count = 0;
        foreach($video_names as $video_name) {

            $url = 'https://s3-us-west-2.amazonaws.com/prolivestream/videos/' . $video_name . '.mp4';
            //$from = fopen('https://prolivestream.s3.amazonaws.com/videos/' . $video_name . '.mp4', 'r');

            $fp = fopen($url, 'rb');


//            //http://stackoverflow.com/questions/9906555/ftp-upload-file-to-distant-server-with-curl-and-php-uploads-a-blank-file
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, 'ftp://'.$this->get_ip_address().'/'.$directory.'/'.$video_name. '.mp4');
//            curl_setopt($ch, CURLOPT_USERPWD, "ftpuser:Hn7P67583N9m5sS");
//            curl_setopt($ch, CURLOPT_UPLOAD, 1);
//            curl_setopt($ch, CURLOPT_INFILE, $fp);
//            //$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
//            //curl_setopt($ch, CURLOPT_INFILESIZE, $size);//filesize($localfile));
//            curl_exec ($ch);
//            $error_no = curl_errno($ch);
//            curl_close ($ch);

            ftp_fput($conn_id, $video_name.'.mp4', $fp, $mode);

            fclose ($fp);

            //if($count >1) break;
            $count++;

        }
        //ftp_close($conn_id);
    }

    /**
     * Upload video file via URL or local path
     *
     * @param int    $channel_id
     * @param string $video_path
     */
    public function upload_video($channel_id, $video_path, $video_name) {

        // Upload directory
        $directory = $this->get_channel_path($channel_id);


        // Get the current working directory
        $origin = ftp_pwd($this->get_ftp_connection());

        // Attempt to change directory, suppress errors
        if (@ftp_chdir($this->get_ftp_connection(), $directory))
        {
            // If the directory exists, set back to origin
            ftp_chdir($this->get_ftp_connection(), $origin);
        }else{
            // Create upload directory
            ftp_mkdir($this->get_ftp_connection(), $directory);
        }

        // Opening video file
        $video = fopen($video_path, 'r');

        // Upload file
        ftp_fput($this->get_ftp_connection(), "{$directory}/{$video_name}.mp4", $video, FTP_ASCII);

        // Closing video
        fclose($video);

    }

    /**
     * Returns the current playlist
     *
     * @param int $channel_id
     *
     * @return array
     */
    public function get_playlist($channel_id) {

        // Playlist file
        $playlist_file = "{$this->get_channel_path($channel_id)}/channel_{$channel_id}.m3u8";

        // Start output buffer
        ob_start();

        $out = fopen('php://output', 'w');

        // Try to open the file
        if (!@ftp_fget($this->get_ftp_connection(), $out, $playlist_file, FTP_ASCII)) {

            // Set empty playlist
            $this->set_playlist($channel_id, []);
        }
        fclose($out);

        // Get text in playlist file and clean the buffer
        $playlist_rows = explode("\n", ob_get_clean());

        $playlist = [];

        // For each row...
        foreach ($playlist_rows as $row) {
            $row = trim($row);

            if (empty($row)) continue;

            //$video = explode(' ', $row);
            //$playlist[trim($video[0])] = str_replace("/data/public{$this->get_channel_path($channel_id)}/", '', trim($video[3]));

            $playlist[] = str_replace("{$this->get_channel_path($channel_id)}/", '', $row);
        }

        // Sorting playlist by schedule date
        //ksort($playlist);

        return $playlist;
    }

    /**
     * Changes the playlist
     *
     * @param int $channel_id
     * @param array $playlist
     */
    public function set_playlist($channel_id, $playlist) {

        // Playlist file
        $playlist_file = "{$this->get_channel_path($channel_id)}/playlist.m3u8";

        // Sorting playlist array by schedule date
        ksort($playlist);

        // Generating playlist
        $playlist_rows = [];

        foreach ($playlist as $time => $video) {
            //$playlist_rows[] = "{$time} 0 0 /data/public{$this->get_channel_path($channel_id)}/{$video}";
            $playlist_rows[] = "{$video}";
        }

        // Creating tmp file
        $tmp_file_path = "/tmp/channel_{$channel_id}.m3u8";
        $tmp_file = fopen($tmp_file_path, 'wr');
        fwrite($tmp_file, implode("\n", $playlist_rows));
        fclose($tmp_file);

        // Reopening to send by FTP
        $tmp_file = fopen($tmp_file_path, 'r');

        // If file does not exist, then create it
        ftp_fput($this->get_ftp_connection(), $playlist_file, $tmp_file, FTP_ASCII);

        fclose($tmp_file);
        $this->close_ftp_connection();
    }

    /**
     * Schedules videos on DVEO
     *
     * @param int $channel_id
     * @param array $videos
     */
    public function schedule_videos($channel_id, $videos) {

        // Change and save playlist
        $this->set_playlist($channel_id, $this->get_playlist($channel_id) + $videos);
    }

    /**
     * Schedules a single video on DVEO
     *
     * @param int    $channel_id
     * @param int
     * @param string $video
     */
    public function schedule_video($channel_id, $time, $video) {
        $this->schedule_videos($channel_id, [$time => $video]);
    }

    /**
     * Getting FTP connection resource
     *
     * @return null|resource
     */
    private function get_ftp_connection() {

        if (is_null($this->ftp_connection)) {

            // Creating a new connection
            $this->ftp_connection = ftp_connect($this->get_ip_address());

            // Logging in to server
            ftp_login($this->ftp_connection, 'ftpuser', $this->password);

            // enable passive mode
            ftp_pasv($this->ftp_connection, true);
        }

        return $this->ftp_connection;
    }

    /**
     * Close FTP connection resource
     */
    public function close_ftp_connection() {
        // close the connection
        ftp_close($this->ftp_connection);
        $this->ftp_connection = null;
    }

    /**
     * Returns channel path by channel id
     *
     * @param int $channel_id
     *
     * @return string
     */
    private function get_channel_path($channel_id) {

        //return $directory = "group2/channel_{$channel_id}";
        return $directory = "group3/tv";
    }

    //old, temp
    /**
     * Upload playlist to DVEO
     *
     * @param array $videos
     * @param int $channel_id
     */
    public function change_playlist($videos, $channel_id)
    {

        $content = '';
        foreach ($videos as $video_in_playlist) {
            $video = Video::find($video_in_playlist['video_id']);
            $content .= $video['file_name'] . "." . $video['video_format'] . "\n";
        }

        $file = "channel_{$channel_id}.m3u8";
        $fileCreate = fopen('/tmp/' . $file, 'wr');
        fwrite($fileCreate, $content);

        $fileOpen = fopen('/tmp/' . $file, 'r');

        // set up basic connection
        $conn_id = ftp_connect($this->ip);

        // login with username and password
        ftp_login($conn_id, 'ftpuser', 'Hn7P67583N9m5sS');

        //ftp_fput($conn_id, "group2/channel_{$channel_id}/$file", $fileOpen, FTP_ASCII);
        ftp_fput($conn_id, "group3/tv/$file", $fileOpen, FTP_ASCII);

        unlink('/tmp/' . $file);

        // close the connection and the file handler
        ftp_close($conn_id);
        fclose($fileCreate);
        fclose($fileOpen);
    }
    
    //to implement
//     public function uploadFromDveoToS3($from, $to, $bucket) {
    
//     	$s3 = new AmazonS3();
    
//     	AmazonLib::useSSL($s3);
    
//     	$file_upload_response= $s3->create_object($bucket, $to, array(
//     			"fileUpload" => $from,
//     			"acl" => AmazonS3::ACL_PUBLIC
//     	));
    
//     	return $file_upload_response->isOK();
    
//     }

}
<?php

class VodTranscoderManager {

		private $profile_hd1 = null;
		private $profile_sd1 = null;
		private $profile_sd2 = null;

		public function __construct($args) {   
			$known_options = array(
							'id' => null,
							'keyframeInterval' => 0,
							'presetName' => null,
							'createdAt' => null,
							'updatedAt' => null,
							'width' => 0,
	    						'height' => 0,
	    						'upscale' => null,
	    						'fps' => 0.0,
	    						'name' => null,
	    						'title' => null,
	    						'videoBitrate' => 0,
	    						'audioBitrate' => 0,
	    						'x264Options' => null,
	    						'aspectMode' => null,
	    						'audioSampleRate' => null,
	    						'priority' => 0,
	    						'timeCode' => null,
	    						'extname' => null);
				foreach ($known_options as $option => $default) {
            	$this->$option = isset($args[$option]) ? $args[$option] : $default;
        	}
        	$this->api_version = 1;
    	}

    	//
    // REST client
    //
    
     public function get($request_path, $params = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function post($request_path, $params = array()) {
        $data_json = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function put($request_path, $params = array()) {
        $data_json = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function delete($request_path, $params = array()) {
       $data_json = json_encode($params);
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
       curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $response  = curl_exec($ch);
       curl_close($ch);
       return $response;
    }

    public function set_profile_hd1($value)
    {
        $profile_hd1  = $value;
    }

    public function get_profile_hd1()
    {
        return $profile_hd1;
    }

    public function set_profile_sd1($value)
    {
        $profile_sd1  = $value;
    }

    public function get_profile_sd1()
    {
        return $profile_sd1;
    }

    public function set_profile_sd2($value)
    {
        $profile_sd2  = $value;
    }

    public function get_profile_sd2()
    {
        return $profile_hd2;
    }

}

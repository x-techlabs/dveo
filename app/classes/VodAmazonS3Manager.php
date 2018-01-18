<?php

class VodAmazonS3Manager {



    private   $accessKey = null;
    private   $secretKey = null;
    private   $hostname = null;
    private   $session  = null;

	public function __construct($args) {   
			$known_options = array(
				'accessKey'  => null,
				'secretKey' => null,
				'hostname' => null);
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

    
    public function set_accessKey($value)
    {
        $accessKey = $value;
    }

    public function get_accessKey()
    {
        return $accessKey;
    }

    public function set_secretKey($value)
    {
        $secretKey = $value;
    }

    public function get_secretKey()
    {
        return $secretKey;
    }	

    public function set_hostname($value)
    {
        $hostname = $value;
    }

    public function get_hostname()
    {
    	return $hostname;
    }

    public function set_session($value)
    {
    	$session = $value;
    }

    public function get_session()
    {
    	return $session;
    }

}
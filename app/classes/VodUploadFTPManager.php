<?php

class VodUploadFTPManager {

	private $ipaddress = null;
	private $port = null;
	private $username = null;
	private $password = null;

	public function __construct($args) {   
			$known_options = array(
				'ipaddress'  => null,
				'port' => null,
				'username' => null,
				'password' => null);
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

    public function set_ftpserverip($value)
    {
    	$ftpserverip = $value;
    }

    public function get_ftpserverip()
    {
    	return $ftpserverip;
    }

	public function set_port($value)
    {
    	$port = $value;
    }

    public function get_port()
    {
    	return $port;
    }

    public function set_username($value)
    {
    	$username = $value;
    }

    public function get_username()
    {
    	return $username;
    }

    public function set_password($value)
    {
    	$password = $value;
    }

    public function get_password()
    {
    	return $password;
    }
}



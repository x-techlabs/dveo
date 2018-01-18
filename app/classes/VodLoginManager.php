<?php

class VodLoginManager {

    private   $token = null;
    private   $id = null;

	public function __construct($args) {   
			$known_options = array(
				'loginid'  => null,
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

    private function http_request($verb, $path, $query = null, $data = null ) {
        $verb = strtoupper($verb);
        $path = self::canonical_path($path);
        $suffix = '';
        $signed_data = $data;

        //if ($verb == 'POST' || $verb == 'PUT') {
        //    $signed_data = $this->signed_params($verb, $path, $data);
        //    if(isset($data["file"])) {
        //        $signed_data["file"] = "@". $data["file"];
        //    }
        //}
        //else {
        //    $signed_query_string = $this->signed_query($verb, $path, $query);
        //    $suffix = '?' . $signed_query_string;
       // }

        $url = $path . $suffix;
        
        $curl = curl_init($url);
         curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
//        if ($signed_data) {
            curl_setopt($curl, CURLOPT_POST, $signed_data);
  //      }
  //      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $verb);
   //     curl_setopt($curl, CURLOPT_PORT, '8080');
   //     if (defined('CURLOPT_PROTOCOLS')) {
   //         curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS|CURLPROTO_HTTP);
   //     }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_VERBOSE, 1);

        $response = curl_exec($curl);
        $error_code = curl_errno($curl);
        $error_message = curl_error($curl);
        curl_close($curl);

        if ( $error_code != CURLE_OK ) {
            return json_encode(array(
                'error' => 'CURL_' . $error_code,
                'message' => $error_message,
            ));
        }

        return $response;
    }

    //
    // Authentication
    //

    public function signed_query($verb, $request_path, $params = array(), $timestamp = null) {
        return self::array2query($this->signed_params($verb, $request_path, $params, $timestamp));
    }
    
    public function signed_params($verb, $request_path, $params = array(), $timestamp = null) {
        date_default_timezone_set('UTC');

        $auth_params = $params;
        unset($auth_params["file"]);

        $auth_params['cloud_id'] = $this->cloud_id;
        $auth_params['access_key'] = $this->access_key;
        $auth_params['timestamp'] = $timestamp ? $timestamp : date('c');
        $auth_params['signature'] = $this->generate_signature($verb, $request_path, $auth_params);
        return $auth_params;
    }
    
    public static function signature_generator($verb, $request_path, $host, $secret_key, $params = array()) {
        $string_to_sign = self::string_to_sign($verb, $request_path, $host, $params);
        $context = hash_init('sha256', HASH_HMAC, $secret_key);
        hash_update($context, $string_to_sign);
        return base64_encode(hash_final($context, true));
    }
    
    public function generate_signature($verb, $request_path, $params = array()) {
        return self::signature_generator($verb, $request_path, $this->api_host, $this->secret_key, $params);
    }
    
    public static function string_to_sign($verb, $request_path, $host, $params = array()) {
        $request_path = self::canonical_path($request_path);
        $query_string = self::canonical_querystring($params);
        $_verb = strtoupper($verb);
        $_host = strtolower($host);
        $string_to_sign = "$_verb\n$_host\n$request_path\n$query_string";
        return $string_to_sign;
    }
    
    //
    // Misc
    //

    private static function canonical_path($path) {
        return '/' . trim($path, " \t\n\r\0\x0B/");
    }
    
    private static function canonical_querystring($params = array()) {
        ksort($params, SORT_STRING);
        return self::array2query($params);
    }

    private static function urlencode($str) {
        $ret = urlencode($str);
        $ret = str_replace("%7E", "~", $ret);
        $ret = str_replace("+", "%20", $ret);
        return $ret;
    }
    
    private static function array2query($array) {
        $pairs = array();
        foreach ($array as $key => $value) {
            $pairs[] = self::urlencode($key) . '=' . self::urlencode($value);
        }
        return join('&', $pairs);
    }

    public function set_token($value)
    {
        $token = $value;
    }

    public function get_token()
    {
        return $token;
    }

    public function set_id($value)
    {
        $id = $value;
    }

    public function get_id()
    {
        return $id;
    }	

}

<?php
    /**
 * Created by Sublime.
 * User: user
 * Date: 8/27/16
 * Time: 11:00 AM
 */


class VodLoginController extends BaseController
{

	private $vodlogin;
	private $vodregister;

	public function __construct()
	{
		$vod_login = array(
			'loginid'  => 'robinr.rao@gmail.com',
			'password' => 'sec3rt');
		$this->vodlogin = new VodLoginManager($vod_login);
		

	}


	public function vodLogin() {

		$path = 'http://138.197.211.60:8080/LoginManager/services/LoginManager/Login';
		$vod_login = array(
			'loginid'  => 'robinr.rao@gmail.com',
			'password' => 'sec3rt');

		$login = json_decode($this->vodlogin->post($path,$vod_login));

		// $this->vodlogin->set_token($login->$token);    // this token value has to be utilized for all calls coming on.
		// $this->vodlogin->set_id($login->$id);          // this id value has to be recorded and utilzied for AmazonS3Manager/TranscodeManager/UploadFTPManager

 	        
                header('Content-Type: application/json');
                return Response::json($login, 200);
	}

	public function vodRegister() {
		$path = 'http://138.197.211.60:8080/LoginManager/services/LoginManager/Register';

//                $data = Input::all();

		$register = Input::all();


		$name = $register['name'];
                $loginid = $register['loginid'];
                $passwd = $register['password'];
                $repasswd = $register['repassowd'];
                $id = $register['id'];
                $role = $register['role'];

		$vodregister = array(
					'name'      => $name,
					'loginid'   => $loginid,
					'password'  => $passwd,
					'repassowd' => $repasswd,
					'id' => $id,     // this is a dummy value internally if signed a value will be provided during login
					'role' => $role);
        
                $result = json_decode($this->vodlogin->post($path,$vodregister));
                header('Content-Type: application/json');
                return Response::json($result, 200);
	}

	public function vodLogout() {
		$path = 'http://138.197.211.60:8080/LoginManager/services/LoginManager/LogOut/1?token=ffffffff-ffff-ffff-ffff-ffffffffffff';
		$finalpath = $path;

		$response = json_decode($this->vodlogin->get($finalpath));
                header('Content-Type: application/json');
                return Response::json($response, 200);
	}
}

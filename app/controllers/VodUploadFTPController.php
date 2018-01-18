<?php
    /**
 * Created by Sublime.
 * User: user
 * Date: 11/10/16
 * Time: 11:00 AM
 */
class VodUploadFTPController extends BaseController
{
   private $srcftpuser  = null;
   private $destftpuser = null;

   public function __construct()
	{
		$access_dest = array(
							 'ipaddress' => '198.241.44.164',
							 'port' => '22',
							 'username' => 'ftpuser',
							 'password' => 'Hn7P67583N9m5sS');
		$access_src	=  array(
							 'ipaddress' => null,
							 'port' => null,
							 'username' => null,
							 'password' => null);

		$this->destftpuser = new VodUploadFTPManager($access_dest);
		$this->srcftpuser  = new VodUploadFTPManager($access_src);
	}

	public function accessftpserver()
	{
		$path='http://138.197.211.60:8080/UploadFTPManager/services/UploadFTPManager/AccessFTPServer/1';
		$token = Input::get('token');
		$payload = Input::all();
		$response = null;

		$ipaddress = $payload['ipaddress'];
		$port      = $payload['port'];
		$username  = $payload['username'];
		$password  = $payload['password'];

		$path = $path . '?token=' . $token;

		$post_data = array(
							'ipaddress' => $ipaddress,
							'port' => $port,
							'username' => $username,
							'password' => $password);


		
			$response = json_decode($this->destftpuser->post($path,$post_data));
	
		//Log::info('This is the accessftpserver() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function displayallfiles()
	{
		$path='http://138.197.211.60:8080/UploadFTPManager/services/UploadFTPManager/DisplayAllFiles/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;

		$response = $this->destftpuser->get($path,null);

		//Log::info('This is the displayallfiles() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function downloadrequestedftpmedia()
	{
		$path='http://138.197.211.60:8080/UploadFTPManager/services/UploadFTPManager/DownloadRequestedFTPMedia/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$payload = Input::all();
		$response = null;

		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;

		$response = $this->destftpuser->post($path,$payload);

		//Log::info('This is the downloadrequestedftpmedia() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}

	public function getftpmediadownloadstatus()
	{
		$path='http://138.197.211.60:8080/UploadFTPManager/services/UploadFTPManager/GetFTPMediaDownloadStatus/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$response = null;

		$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;
		$response = $this->destftpuser->get($path,null);

		//Log::info('This is the getftpmediadownloadstatus() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}		

	public function uploadmediaintoftpserver()
	{
		$path='http://138.197.211.60:8080/UploadFTPManager/services/UploadFTPManager/UploadMediaIntoFTPServer/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$payloads =  Input::all();
		$response = null;

		$filenamea = $payloads['filenamea'];
		$filenameb = $payloads['filenameb'];
		$filenamec = $payloads['filenamec'];
		$filenamed = $payloads['filenamed'];
		$filenamee = $payloads['filenamee'];

		$filenamef = $payloads['filenamef'];
		$filenameg = $payloads['filenameg'];
		$filenameh = $payloads['filenameh'];
		$filenamei = $payloads['filenamei'];
		$filenamej = $payloads['filenamej'];

		$ftpkeya = array(
							'folder' => ' ',
							'id' => 1,
							'mediafile' => $filenamea);

		$ftpkeyb = array(
							'folder' => ' ',
							'id' => 2,
							'mediafile' => $filenameb);

		$ftpkeyc = array(
							'folder' => ' ',
							'id' => 3,
							'mediafile' => $filenamec);

		$ftpkeyd = array(
							'folder' => ' ',
							'id' => 4,
							'mediafile' => $filenamed);

		$ftpkeye = array(
							'folder' => ' ',
							'id' => 5,
							'mediafile' => $filenamee);

		$ftpkeyf = array(
							'folder' => ' ',
							'id' => 6,
							'mediafile' => $filenamef);

		$ftpkeyg = array(
							'folder' => ' ',
							'id' => 7,
							'mediafile' => $filenameg);

		$ftpkeyh = array(
							'folder' => ' ',
							'id' => 8,
							'mediafile' => $filenameh);

		$ftpkeyi = array(
					
							'folder' => ' ',
							'id' => 9,
							'mediafile' => $filenamei);

		$ftpkeyj = array(
					
							'folder' => ' ',
							'id' => 10,
							'mediafile' => $filenamej);

		$post_data = array(
			               'ftpfiles' => array($ftpkeya, $ftpkeyb, $ftpkeyc, $ftpkeyd, $ftpkeye, $ftpkeyf, $ftpkeyg, $ftpkeyh, $ftpkeyi, $ftpkeyj));


		$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;


		$response = json_decode($this->destftpuser->post($path,$post_data));

		//Log::info('This is the uploadmediaintoftpserver() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
	}
   
	public function uploadmediasingleintoftpserver()
	{
		$path='http://138.197.211.60:8080/UploadFTPManager/services/UploadFTPManager/UploadMediaIntoFTPServer/1';
		$token = Input::get('token');
		$sessionid = Input::get('sessionid');
		$payloads =  Input::all();
		$response = null;

		$filenamea = $payloads['filenamea'];
		$filenameb = $payloads['filenameb'];
		$filenamec = $payloads['filenamec'];
		$filenamef = $payloads['filenamef'];

		$ftpkeya = array(
							'folder' => ' ',
							'id' => 1,
							'mediafile' => $filenamea);

		$ftpkeyb = array(
							'folder' => ' ',
							'id' => 2,
							'mediafile' => $filenameb);

		$ftpkeyc = array(
							'folder' => ' ',
							'id' => 3,
							'mediafile' => $filenamec);

		$ftpkeyf = array(
							'folder' => ' ',
							'id' => 4,
							'mediafile' => $filenamef);

		$post_data = array(
			               'ftpfiles' => array($ftpkeya, $ftpkeyb, $ftpkeyc,  $ftpkeyf));

		$path = $path . '?token=' . $token . '&sessionid=' . $sessionid;


		$response = json_decode($this->destftpuser->post($path,$post_data));

		//Log::info('This is the uploadmediaintoftpserver() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);

	}

    public function getftpmediauploadstatus()
    {
    	$path='http://138.197.211.60:8080/UploadFTPManager/services/UploadFTPManager/GetFTPMediaUploadStatus/1';
    	$token = Input::get('token');
		$sessionid = Input::get('sessionid');

		$response = null;

		$path = $path . '&token=' . $token . '&sessionid=' . $sessionid;
		$response = $this->destftpuser->get($path,null);

		//Log::info('This is the getftpmediauploadstatus() output'+$response);
		header('Content-Type: application/json');
        return Response::json($response, 200);
    }
}

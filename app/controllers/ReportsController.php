<?php

use Guzzle\Http\Exception\ClientErrorResponseException;
use Input;
class ReportsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $client = new \GuzzleHttp\Client();
        $client_id = '6eb75994-edd4-4130-ba3d-d6ee6fa91d0e';
        $slice_id = '591b4c6f796db4444a00066a';
        $api_key = '49fa0ca24053537aafdb6c6f87b78d4b';
        $statsUrl = "https://api.wmspanel.com/v1/daily_stats?client_id=$client_id&api_key=$api_key&from=2016-01-01&to=2017-07-20&data_slice=591b4c6f796db4444a00066a";

        $streamsUrl = "https://api.wmspanel.com/v1/server/591b7256934ccdb70c00007c/live/streams?client_id=$client_id&api_key=$api_key";
        /*
            Server IDs
            591b5aebf5aef02c2000006b
            591b4d37934ccdffdd000043
            591b7256934ccdb70c00007c
        */

        // $url = "https://api.wmspanel.com/v1/server?client_id=$client_id&api_key=$api_key"; //Ger servers
        // $url = "https://api.wmspanel.com/v1/daily_stats?client_id=$client_id&api_key=$api_key&from=2016-01-01&to=2017-07-20&data_slice=591b4c6f796db4444a00066a ";
        // $url = "https://api.wmspanel.com/v1/server/591b7256934ccdb70c00007c/live/streams?client_id=$client_id&api_key=$api_key"; //Get stream list
        
        // $url = "https://api.wmspanel.com/v1/data_slices?client_id=$client_id&api_key=$api_key"; // Get slices
        $ch = curl_init();  
     
        curl_setopt($ch,CURLOPT_URL,$streamsUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
     
        $output=curl_exec($ch);
        if($output === false)
        {
            echo "Error Number:".curl_errno($ch)."<br>";
            echo "Error String:".curl_error($ch);
        }
        // var_dump($output);die;
        $streams = json_decode($output)->streams;
        curl_close($ch);
        $stats = $this->getStats($statsUrl);

        $this->data['streams'] = $streams;
        $this->data['stats'] = $stats;
        return $this->render('reports/reports');
	}


	public function getStatsByDate(){
		$fromDate = Input::get('fromDate');
		$toDate = Input::get('toDate');

		$client = new \GuzzleHttp\Client();
        $client_id = '6eb75994-edd4-4130-ba3d-d6ee6fa91d0e';
        $slice_id = '591b4c6f796db4444a00066a';
        $api_key = '49fa0ca24053537aafdb6c6f87b78d4b';

		$statsUrl = "https://api.wmspanel.com/v1/daily_stats?client_id=$client_id&api_key=$api_key&from=$fromDate&to=$toDate&data_slice=591b4c6f796db4444a00066a";
		$ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$statsUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        if($output === false)
        {
            echo "Error Number:".curl_errno($ch)."<br>";
            echo "Error String:".curl_error($ch);
        }
        $stats = json_decode($output)->stats;

        curl_close($ch);
        if(!empty($stats) && count($stats) > 0){
			echo json_encode(array(
				'success' => true,
				'data' => $stats
			));
			die;
        }
        else{
			echo json_encode(array(
				'success' => false,
				'data' => $stats
			));
			die;
        }
	}

	public function getStats($url)
	{
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        if($output === false)
        {
            echo "Error Number:".curl_errno($ch)."<br>";
            echo "Error String:".curl_error($ch);
        }
        $stats = json_decode($output)->stats;

        curl_close($ch);

        return $stats;
	}

}

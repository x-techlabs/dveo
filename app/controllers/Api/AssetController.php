<?php namespace App\Controllers\Api;

use BaseController as Controller;
//use Illuminate\Http\Response;
use Response;
class AssetController extends Controller {

	/**
	 * Display a listing of $COLLECTION$
	 *
	 * @return Response
	 */
	public function index($channel_id)
	{

	}

	/**
	 * Show the form for creating a new $RESOURCE$
	 *
	 * @return Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created $RESOURCE$ in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

	}

	/**
	 * Display the specified $RESOURCE$.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($channel_id, $id, $type)
	{

		if($type == 'video'){

			$video = \Video::where("channel_id","=", $channel_id)->find($id);
			$result = $video;

		}
		else if($type == 'playlist'){

			$playlist = \TvappPlaylist::with(['videos'])
				->where("channel_id", "=", $channel_id)
				->find($id);

			if(count($playlist) > 0){
				if(count($playlist->videos) > 0){
					foreach($playlist->videos as $video){
						if ($video->source == '0' || $video->source == 'internal')
						{
							$fname = 'http://'.$channel_id.'.1studio.tv.global.prod.fastly.net/'.$video->file_name.'.mp4';
							$video_path =  $fname;
						}
						else{
							$video_path = $video->file_name;
						}
						$video->video_path = $video_path;
					}
				}
			}

			$result = $playlist;

		}
		else{

			$result = array('error' => 'You have not specified media type or type is wrong');

		}

		return Response::json($result);

	}

	/**
	 * Show the form for editing the specified $RESOURCE$.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified $RESOURCE$ in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

	}

	/**
	 * Remove the specified $RESOURCE$ from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	}

}
<?php

use GuzzleHttp\Client;
/**
 * Class PlaylistController
 */
class PlaylistController extends BaseController {

    public function index() {
        if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
            return App::abort(404);
        }

        $playlists = self::get_playlists();

        foreach($playlists as $playlist) {
            $video_ids = Video_in_playlist::where('playlist_id', '=', $playlist->id)->get();

            foreach($video_ids as $video_id) {
                $video = Video::find($video_id['video_id']);

                if(isset($video['thumbnail_name'])) {
                    $playlist->video_thumb = $video['thumbnail_name'];
                    break;
                } else {
                    $playlist->video_thumb = 'http://speakingagainstabuse.com/wp-content/themes/AiwazMag/images/no-img.png';
                }
            }
        }

        $this->data['playlists'] = $playlists;

        return $this->render('playlist/playlist_index');
    }

    public static function get_playlists() {

        $playlists = Playlist::where("channel_id", "=", BaseController::get_channel_id())->orderBy('id', 'desc')->get();
//        $playlists = Playlist::all();
//        $timeline = Timeline::get(['percentage', 'classColor']);

        return Time::change_to_human_data_in_array($playlists);
    }


	public function get_playlist_info(){
		$id = Input::get('id');
		if ($id > 0)
		{
			$playlist = Playlist::find($id);
			return Response::json([
				'status' => true,
				'playlist' => $playlist
			], 200);
		}
		exit();
	}

    public function add_to_playlist() {

        $title = trim(Input::get('title'));
        $description = trim(Input::get('description'));
        //$file = Input::file('file');
		$user_id = Auth::user()->id;
		$channel_id = BaseController::get_channel_id();

        if (empty($title)) {

            return Response::json([
                'status' => false,
                'message' => 'Wrong data'
            ], 200);
//            return View::make('error')->with(array('message' => 'Wrong data'));
        }

//        $destinationPath = public_path().DIRECTORY_SEPARATOR.'playlists_thumbnails'.DIRECTORY_SEPARATOR;
//        $destinationPath = public_path().'/playlists_thumbnails/';
//        // If the uploads fail due to file system, you can try doing public_path().'/uploads'
//        $filename = str_random(12).'_'.$file->getClientOriginalName();
//        $upload_success = Input::file('file')->move($destinationPath, $filename);

		$main_url = 'https://prolivestream.tv/';
		$end_url = '.m3u8';
		$link_title = str_replace(' ', '_', $title);

		$stream_url = $main_url.$link_title.$end_url;

		// live monitor
		$monitors = new LiveMonitors;
		$monitors->channel_id = $channel_id;
		$monitors->user_id = $user_id;
		$monitors->stream_url = $stream_url;
		$monitors->title = $title;
		$monitors->save();

        $playlist = new Playlist;
        $playlist->title = $title;
        $playlist->description = $description;
		$playlist->stream_url = $stream_url;
        $playlist->channel_id = $channel_id;
//        $playlist->thumbnail_name = $filename;

        if ($playlist->save()) {
            return Response::json([
                'status' => true,
                'playlist_id' => $playlist->id
            ], 200);
        }
    }

    public function edit_playlist() {
        $id = trim(Input::get('id'));
        $title = trim(Input::get('title'));
        $description = trim(Input::get('description'));
		$stream_url = trim(Input::get('stream_url'));


		if (empty($id) || empty($title)) {
            return Response::json([
                'status' => false,
                'message' => 'Wrong data'
            ], 200);
        }

        $playlist = Playlist::find($id);
        $playlist->title = $title;
		$playlist->stream_url = $stream_url;
        $playlist->description = $description;
//        $playlist->status = 0;
        $playlist->channel_id = BaseController::get_channel_id();

        if ($playlist->save()) {
            return Response::json([
                'status' => true,
                'playlist_id' => $playlist->id
            ], 200); 
        }
    }

    public function get_playlist_by_id() {
        $id = trim(Input::get('id'));
        $playlist = Playlist::find($id);

        $video_ids = Video_in_playlist::where('playlist_id', '=', (int) $playlist->id)->get();

        $videos = [];

        foreach ($video_ids as $video_id) {

            $video = Video::find($video_id['video_id']);

            if (!$video) continue;

            $video->time = Time::change_to_human_data_in_object($video);

            //$video = Time::change_to_human_data_in_array([$video->toArray()]);
            $videos[] = $video;
        }

        $playlist->time = Time::change_to_human_data_in_object($playlist);

        return View::make('playlist/edit_playlist')->with('playlist', $playlist)->with('videos', $videos);
    }

    public function insert_video_in_playlist() {

        $playlist = Input::get('playlist');

        //var_dump($playlist); die();
        if (is_array($playlist)) {

            $insert = array();

            $duration = 0;

            $thumbnail_name = '';
            $video_array_for_playlist = array();
	    if(isset($playlist['playlists'])) {
                foreach($playlist['playlists'] as $video_id) {

                    $videos = Video::find((int)$video_id);
		    array_push($video_array_for_playlist,$videos->getVideoPathAttribute());
                    $duration += (int)$videos->duration;

                    $insert[] = array(
                        'playlist_id' => $playlist['playlist_id'],
                        'video_id' => $video_id
                    );

                    $thumbnail_name = $videos->thumbnail_name;
                }
            } else {
                return Response::json([
                    'status' => true
                ], 200);
            }

            $playlist = Playlist::find($playlist['playlist_id']);
            $playlist->duration = $duration;
            $playlist->thumbnail_name = $thumbnail_name;
	    //Integrate Segmenter
	    $client = new Client();
             error_log("ARRAY : " . json_encode($video_array_for_playlist));
             $response = $client->request('POST', 'http://165.227.14.170:3001/playlist', [
                 'form_params' => [
                    'name' => $playlist->title,
                     'duration' => $playlist->duration,
                     'videos' => json_encode($video_array_for_playlist)
                 ]
             ]);

            $videos_in_playlists = Video_in_playlist::where('playlist_id', '=', $playlist['id'])->get();

            foreach($videos_in_playlists as $videos_in_playlist) {
                $videos_in_playlist->delete();
            }

            Video_in_playlist::Insert($insert);
            $playlist->save();

            return Response::json([
                'status' => true
            ], 200);
        } else {
            return Response::json([
                'status' => false
            ], 200);
        }
    }

    public function delete_playlists() {
        $playlist_id = Input::get('playlistId');
	$playlist = Playlist::find($playlist_id);
         error_log("****STEP ! DELETE");
         $client = new Client();
         error_log("****STEP ! DELETE". $playlist->title.$playlist->duration);
         $response = $client->request('POST', 'http://165.227.14.170:3001/playlist', [
             'form_params' => [
                 'option' => "delete",
                 'name' => $playlist->title,
                 'duration' => $playlist->duration
             ]
         ]);

        Playlist::find($playlist_id)->delete();
        Schedule::where('playlist_id', '=', $playlist_id)->delete();
        Video_in_playlist::where('playlist_id', '=', $playlist_id)->delete();
    }

//    public function master_loop() {
//        $id = trim(Input::get('id'));
//        $playlist = Playlist::find($id);
//
//        $videos_in_playlist = Video_in_playlist::where('playlist_id', '=', $id)->get();
//
//        $dveo = BaseController::get_dveo();
//
//        if($playlist->type != 2) {
//            $allPlaylists = Playlist::where('channel_id', '=', BaseController::get_channel_id())->get();
//
//            foreach($allPlaylists as $one) {
//                $one->master_looped = 0;
//                $one->type = 0;
//                $one->save();
//            }
//
//            ###
//            $playlistvideos = [];
//            foreach ($videos_in_playlist as $video_in_playlist) {
//                $video = Video::find($video_in_playlist['video_id']);
//                $playlistvideos[] = $video['file_name'] . "." . $video['video_format'];
//                //$playlistvideos[] .= time() . " " . $video['file_name'] . "." . $video['video_format'] . "\n";
//            }
//
//            // Change playlist on DVEO
//            //$dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());
//
//            // Creating a new DVEO instance
//            //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');
//            $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');
//            $dveo->schedule_videos(BaseController::get_channel_id(),  $playlistvideos);
//            ###
//
//            $dveo->restart_stream(BaseController::get_channel()->stream);
//
//            $playlist->master_looped = Carbon::now()->getTimestamp();
//            $playlist->type = 2;
//            $playlist->save();
//
//            return Response::json([
//                'status' => true,
//                'loopOff' => true
//            ], 200);
//        } else {
//            $playlist->master_looped = 0;
//            $playlist->type = 0;
//            $playlist->save();
//
//            $dveo->stop_stream(BaseController::get_channel()->stream);
//
//            return Response::json([
//                'status' => true,
//                'loopOff' => false
//            ], 200);
//        }
//    }

    //old/temp use
    public function master_loop() {
        $id = trim(Input::get('id'));
        $playlist = Playlist::find($id);

        $videos_in_playlist = Video_in_playlist::where('playlist_id', '=', $id)->get();

        $dveo = BaseController::get_dveo();

        if($playlist->type != 2) {
            $allPlaylists = Playlist::where('channel_id', '=', BaseController::get_channel_id())->get();

            foreach($allPlaylists as $one) {
                $one->master_looped = 0;
                $one->type = 0;
                $one->save();
            }

            // Change playlist on DVEO
            $dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());
            $dveo->restart_stream(BaseController::get_channel()->stream);

            $playlist->master_looped = Carbon::now()->getTimestamp();
            $playlist->type = 2;
            $playlist->save();

            return Response::json([
                'status' => true,
                'loopOff' => true
            ], 200);
        } else {
            $playlist->master_looped = 0;
            $playlist->type = 0;
            $playlist->save();

            $dveo->stop_stream(BaseController::get_channel()->stream);

            return Response::json([
                'status' => true,
                'loopOff' => false
            ], 200);
        }
    }


}

<?php

class HomeController extends BaseController {

    public function detect_page() {

        if(Auth::check()) {
            if(Auth::user()->is(User::USER_MANAGE_PLAYOUT)) {
                return Redirect::to('admin');
            } else if(Auth::user()->is(User::USER_MANAGE_COMPANY)) {
                return Redirect::route('channels');
            } else if(Auth::user()->is(User::USER_MANAGE_MEDIA) || Auth::user()->is(User::USER_MANAGE_CHANNEL)) {
                if(Users_in_channels::where('user_id', '=', Auth::user()->id)->count() == 1) {
                    if(Auth::user()->is(User::USER_MANAGE_MEDIA)) {
                        return Redirect::to('channel_' . Users_in_channels::where('user_id', '=', Auth::user()->id)->first()->channel_id.'/home');
                    } else if(Auth::user()->is(User::USER_MANAGE_CHANNEL)) {
                        return Redirect::to('channel_' . Users_in_channels::where('user_id', '=', Auth::user()->id)->first()->channel_id . '/settings');
                    }
                } else if(Users_in_channels::where('user_id', '=', Auth::user()->id)->count() > 1) {
                    return Redirect::route('channels');
                } else {
                    return 'You have not channel';
                }
            } else {
                return 'You have not permission';
            }
        } else {
            return Redirect::to('login');
        }
    }


    /**
     * This function get the data from playlist table, and send to view index.blade.php
     *
     * @return mixed
     */
    public function showSecret() {

        return View::make('secret');
    }

	public function home(){

		return $this->render('home/index');
	}

    /*
     * This function get the data what have been sent with ajax, and inset in in table timelin
     * Also it make a Queue
     */
    public function getTimelineData() {

        // Get all data sending via GET
        $post = Input::all();
        $status = false;

        foreach ($post as $key => $val) {
            $timeline = new Timeline;
            $timeline->video_id = (int) $post[$key]['video_id'];
            $timeline->status = 0;
            $timeline->start = (int) $post[$key]['start'];
            $timeline->end = (int) $post[$key]['end'];
            $timeline->seconds = (int) $post[$key]['seconds'];
            $timeline->percentage = $post[$key]['percentage'];
            $timeline->classColor = $post[$key]['clBootstrap'];

            $timeline_status = $timeline->save();

            $list = Playlist::find((int) $post[$key]['video_id']);
            $list->percentage = $post[$key]['percentage'];

            $playlist_status = $list->save();

            if (!$timeline_status && !$playlist_status) break;
            else $status = true;

            $time = ($post[$key]['start'] == 0) ? $post[$key]['start'] : $post[$key]['start'] - 1;
            $date = Carbon::now()->addSeconds($time); // make a time now+ $time*/

            Queue::later($date, 'Timeline@play', array('video_id' => (int) $post[$key]['video_id'])); // make a queue
        }

        echo json_encode(array('status' => $status)); // send database inserting status with jsan
    }

    public function removeVideo() {

        $id = trim(Input::get('id'));
        $title = trim(Input::get('title'));
        $description = trim(Input::get('description'));
        $duration = trim(Input::get('duration'));
        $channel_id = trim(Input::get('channel_id'));
        $file_name = trim(Input::get('file_name'));

        $video = Video::find($id);

        if (empty($title) || empty($description) || empty($duration) || empty($channel_id) || empty($file_name)) {
            return Response::json([
                'status' => false,
                'message' => 'Wrong data'
            ], 200);
//            return View::make('error')->with(array('message' => 'Wrong data'));
        }

        if ($video == NULL) {
            return Response::json([
                'status' => false,
                'message' => 'This playlist doesn\'t found'
            ], 200);
//            return View::make('error')->with(array('message' => "This video don't found"));
        }

        $video->title = $title;
        $video->description = $description;
        $video->duration = $duration;
        $video->status = 0;
        $video->channel_id = $channel_id;
        $video->file_name = $file_name;

        if ($video->save()) {
            return Response::json([
                'status' => true
            ], 200);
//            return View::make('success')->with(array('success' => 'You video have updated'));
        }
        //http://162.243.64.44/home/removeVideo?id=3&title=title11&description=desc&duration=3000&channel_id=5&file_name=sdgjiodf25df
    }


    public function removePlaylist() {

        $id = trim(Input::get('id'));
        $title = trim(Input::get('title'));
        $description = trim(Input::get('description'));


        if (empty($title) || empty($description)) {

            return Response::json([
                'status' => false,
                'message' => 'Wrong data'
            ], 200);

//            return View::make('error')->with(array('message' => 'Wrong data'));
        }
        $playlist = Playlist::find($id);
        if ($playlist == NULL) {
            return Response::json([
                'status' => false,
                'message' => 'This playlist doesn\'t found'
            ], 200);
//            return View::make('error')->with(array('message' => "This playlist don't found"));
        }

        $playlist->title = $title;
        $playlist->description = $description;
        $playlist->status = 0;


        if ($playlist->save()) {
            return Response::json([
                'status' => true
            ], 200);
//            return View::make('success')->with(array('success' => 'You video have updated'));
        }
        //http://162.243.64.44/home/removePlaylist?id=3&title=title11&description=desc

    }

    public function getVideo() {

        $id = trim(Input::get('id'));
        if (empty($id)) return Response::json([
            'status' => false,
            'message' => 'id doesn\'t exist'
        ], 200);
        $video = Video::find($id);

        return Response::json([
            'playlists' => $video->toArray()
        ], 200);
    }

    public function getVideos() {

        return Response::json([
            'playlists' => Video::all()->toArray()
        ], 200);
    }

    public function getPlaylist() {

        $id = trim(Input::get('id'));
        if (empty($id)) return Response::json([
            'status' => false,
            'message' => 'id doesn\'t exist'
        ], 200);
        $playlists = Playlist::find($id);

        return Response::json([
            'playlists' => $playlists->toArray(),
        ], 200);

    }

    public function getPlaylists() {

        $playlists = Playlist::orderBy('order', 'asc')->get();

        return Response::json([
            'playlists' => $playlists->toArray(),
        ], 200);
    }

    public function getChannel() {

        $id = trim(Input::get('id'));
        if (empty($id)) return Response::json([
            'status' => false,
            'message' => 'id doesn\'t exist'
        ], 200);
        $channel = Channel::find($id);

        return Response::json([
            'channels' => $channel->toArray(),
        ], 200);


    }

    public function getChannels() {

        // $channels = Channel::orderBy('order', 'asc')->get();
        $channels = Channel::orderBy('title', 'asc')->get();

        return Response::json([
            'channels' => $channels->toArray(),
        ], 200);
    }

    /**
     * order - it is the order number that playlist, which order must been change
     * last_order - it is a new order number that playlist
     */
    public function change_playlists_order() {

        $orders = trim(Input::get('order'));

        if (!is_array($orders)) {
            return Response::json([
                'status' => false,
                'message' => Error::returnError(Error::ERROR_SOME_DATA_IS_EMPTY)
            ], 200);

        }

        foreach ($orders as $order => $playlist_id) {

            $playlist = Playlist::find($playlist_id);
            $playlist->order = $order;
            if (!$playlist->save()) {
                return;
            }
        }

        return Response::json([
            'status' => true,
        ], 200);

        // http://162.243.64.44/home/changePlaylistsOrder
    }

    public function add_to_timeline() {

        $video_id = trim(Input::get('order'));
        $seconds = trim(Input::get('order'));
        $percentage = trim(Input::get('order'));
        $classColor = trim(Input::get('order'));

        if (empty($video_id) || empty($seconds) || empty($percentage) || empty($classColor)) {
            return Response::json([
                'status' => false,
                'message' => Error::returnError(Error::ERROR_SOME_DATA_IS_EMPTY)
            ], 200);
        }

        $timeline = new Timeline;
        $timeline->video_id = $video_id;
        $timeline->status = 0;
        $timeline->seconds = $seconds;
        $timeline->percentage = $percentage;
        $timeline->classColor = $classColor;

        if ($timeline->save()) {
            return Response::json([
                'status' => true
            ], 200);
        }
    }

    public function change_timeline_order() {

        $orders = trim(Input::get('order'));

        if (!is_array($orders)) {
            return Response::json([
                'status' => false,
                'message' => Error::returnError(Error::ERROR_SOME_DATA_IS_EMPTY)
            ], 200);

        }

        foreach ($orders as $order => $timeline_id) {

            $timeline = Timeline::find($timeline_id);
            $timeline->order = $order;
            if (!$timeline->save()) {
                return;
            }
        }

        return Response::json([
            'status' => true,
        ], 200);
    }

    public function get_timeline_by_id() {

        $id = trim(Input::get('id'));
        if (empty($id)) {
            return Response::json([
                'status' => false,
                'message' => Error::returnError(Error::ERROR_ID_DOES_NOT_EXIST)
            ], 200);
        }
        $timeline = Timeline::find($id);

        return Response::json([
            'timeline' => $timeline->toArray(),
        ], 200);
    }
}

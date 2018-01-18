<?php

/**
 * Created by PhpStorm.
 * User: ffffff
 * Date: 23.09.14
 * Time: 19:16
 */
class CollectionsController extends BaseController {

    public function index() {

        $stype = Input::get('stype');
        $search = Input::get('search');

        if ($stype==0) $collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'DESC')->get();
        else if ($stype==2) $collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'asc')->get();
        else if ($stype==1) $collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->orderBy('title', 'asc')->get();

        if ($search != '')
        {
            $fv = array();
            foreach($collections as $v)
            {
                if (stripos($v->title, $search) !== false) $fv[] = $v; 
            }
            $collections = $fv;
        }

        $this->data['collections'] = $collections;
        $this->data['stype'] = $stype;
        $this->data['search'] = $search;

        return $this->render('collections/collections_index');
    }

    public function get_videos_by_collection_id() {

        $collection_id = trim(Input::get('collection_id'));

        if (empty ($collection_id) || !is_int((int) $collection_id)) {
            return Request::json([
                'status' => false
            ]);
        }

        $video_ids = Videos_in_collections::where('collection_id', '=', (int) $collection_id)->get();

        $videos = [];

        foreach ($video_ids as $video_id) {

            $play = Video::find($video_id['video_id']);

            //$play = Time::change_to_human_data_in_array($play);

            $videos[] = $play;
        }

        $videos = Time::change_to_human_data_in_array($videos);
        $this->data['videos'] = $videos;

        return $this->render('collections/videos_for_collection');
    }

    public function playlists_for_collections() {

        $playlists = Playlist::all();
        $playlists = Time::change_to_human_data_in_array($playlists);

        $this->data['playlists'] = $playlists;

        return $this->render('collections/all_playliststs_for_collections');

    }

    public function add_to_collection() {

        $title = trim(Input::get('title'));
        //$description = trim(Input::get('description'));
        $file = Input::file('file');
        $playlist = Input::get('playlist');


        if (empty($title)) {

            return Response::json([
                'status' => false,
                'message' => 'Wrong data'
            ], 200);
        }


        $collection = new Collections;
        $collection->title = $title;
        $collection->channel_id = BaseController::get_channel_id();
//        $collection->description = $description;

        if ($collection->save()) {

            if (is_array($playlist)) {

                $insert = array();
                foreach ($playlist as $vid) {
                    $insert[] = array('video_id' => $vid, 'collection_id' => $collection->id );
                }

                Videos_in_collections::Insert($insert);
            }

            return Response::json([
                'status' => true,
                'collection_id' => $collection->id
            ], 200);
        }
    }

    public function insert_playlist_in_collection() {

        $collection = Input::get('collection');

        if (is_array($collection)) {

            $insert = array();

            foreach ($collection['playlists'] as $playlist) {

                $insert[] = array(
                    'playlist_id' => $playlist,
                    'collection_id' => $collection['collection_id']
                );
            }

            if (Videos_in_collections::Insert($insert)) {
                return Response::json([
                    'status' => true
                ], 200);
            }
            else {
                return Response::json([
                    'status' => false
                ], 200);
            }
        }
    }

    public function edit_collection_post() {
        $id = Input::get('id');
        $title = Input::get('title');
        $playlist = Input::get('playlist');

        $viewing = Input::get('viewing');
        $preroll = Input::get('preroll');
        if ($viewing=='') $viewing = 'free';
        if ($preroll=='') $preroll = 'no';

        $collection = Collections::find($id);
        $collection->title = $title;
        $collection->viewing = $viewing;
        $collection->pre_roll = $preroll;
        $collection->save();

        Videos_in_collections::where('collection_id', '=', $id)->delete();
        if (is_array($playlist)) {

            $insert = array();
            foreach ($playlist as $vid) {
                $insert[] = array('video_id' => $vid, 'collection_id' => $id );
            }

            Videos_in_collections::Insert($insert);
        }

        return Response::json([
            'status' => true
        ], 200);

    }

    public function edit_collection_get() {
        $id = Input::get('id');

        $collection = Collections::find($id);
        $this->data['collection'] = $collection;

        //----------------------------------------------------------------------
        $video_ids = Videos_in_collections::where('collection_id', '=', $id)->get();

        $videos = array();
        $v = array();
        foreach ($video_ids as $video_id) 
        {
            $vObj = Video::find($video_id['video_id']);
            if (!is_object($vObj)) 
            {
                Log::info("[VMC] video record not found => ".$video_id['video_id']);
                continue;
            }

            $videos[] = Video::find($video_id['video_id']);
            $v[] = $video_id['video_id'];
        }
        // Get api_url
        $channel = Channel::find(BaseController::get_channel_id());
        $api_url_exist = '0';
        $p = strpos($channel->storage, 'login_url|');
        if ($p > 0)
        {
            $api_url = substr($channel->storage, $p+10);
            $p = strpos($api_url, '|');
            if ($p > 0) $api_url_exist = '1'; 
        }

        $this->data['videos'] = Time::change_to_human_data_in_array($videos);
        $this->data['vmc'] = implode('|', $v);
        $this->data['api_url_exist'] = $api_url_exist;

        Log::info("vids in collection :".implode('|', $v));

        return $this->render('collections/edit_collection');
    }

    public function delete_collection() {
        $collectionId = Input::get('collectionId');

        $collection = Collections::find($collectionId);
        $collection->delete();

        Videos_in_collections::where('collection_id', '=', $collectionId)->delete();

        return Response::json([
            'status' => true
        ], 200);
    }
} 
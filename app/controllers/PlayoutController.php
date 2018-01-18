<?php

/**
 * Created by PhpStorm.
 * User: Aramayis
 * Date: 9/17/14
 * Time: 5:51 PM
 */
class PlayoutController extends BaseController {

    public function playout() {

        $this->data['playlists'] = PlaylistController::get_playlists();
        $this->data['playout'] = true;

        return $this->render('playout/playout');
    }

    public function get_timeline_data() {

        $playlist_ids = Playlists_in_timeline::all();

        $playlists = [];

        foreach ($playlist_ids as $playlist_id) {

            $playlist = Playlist::find($playlist_id['playlist_id']);

            if ((int) $playlist_id['start'] <= Time::add_seconds(3 * 3600)
                || (int) $playlist_id['start'] >= Time::minus_time(3 * 3600)
            ) {
                $playlists[] = array(
                    'playlist' => $playlist,
                    'start' => (int) $playlist_id['start'],
                    'end' => (int) $playlist_id['start'] + ((int) $playlist['duration'] * 1000)
                );
            }


        }

        return Response::json([
            'playlist' => $playlists
        ]);
    }

    public function insert_in_timeline() {

        $playlist_id = trim(Input::get('playlist_id'));
        $start = trim(Input::get('start'));

        if (
            empty($playlist_id)
            || empty($start)
        ) {

            return Response::json([
                'status' => false,
                'message' => Error::returnError(Error::ERROR_SOME_DATA_IS_EMPTY)
            ], 200);
        }

        $timeline = new Playlists_in_timeline;
        $timeline->playlist_id = $playlist_id;
        $timeline->start = $start;

        if ($timeline->save()) {

            $playlist = Playlist::find($playlist_id);
            $playlist->status = 1;

            if ($playlist->save()) {

                return Response::json([
                    'status' => true
                ], 200);
            }

        }
    }
}
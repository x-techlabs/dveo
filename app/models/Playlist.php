<?php

class Playlist extends BaseModel
{
    protected $table = 'playlist';

    /*
     * get the playlist via id, and add him the video related with him
     */

    public function toArray()
    {
        $playlists = parent::toArray();
        $playlists['videos'] = [];

        $videos_in_playlists =  Video_in_playlist::where('playlist_id', '=', $this->id)->get();

        foreach ($videos_in_playlists as $video) {
            $playlists['videos'][] = Video::find($video->video_id);
        }

        return $playlists;
    }

    public static function changePlaylistsOrder($order, $new_order)
    {


    }

} 
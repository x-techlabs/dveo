<?php

class TvwebPlaylist extends BaseModel
{
    protected $table = 'tvweb_playlist';

    /*
     * get the playlist via id, and add him the video related with him
     */

    public function toArray()
    {
        $playlists = parent::toArray();
        $playlists['videos'] = [];

        $videos_in_playlists =  TvwebVideo_in_playlist::where('tvweb_playlist_id', '=', $this->id)->get();

        foreach ($videos_in_playlists as $video) {
            $playlists['videos'][] = Video::find($video->video_id);
        }

        return $playlists;
    }

    public static function changeTvwebPlaylistsOrder($order, $new_order)
    {


    }

} 
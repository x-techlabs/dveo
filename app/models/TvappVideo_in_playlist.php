<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/21/14
 * Time: 12:41 PM
 */
class TvappVideo_in_playlist extends BaseModel
{
    protected $table = 'tvapp_video_in_playlist';

    public function playlist(){
        return $this->belongsTo('TvappPlaylist', 'tvapp_playlist_id');
    }

    public function videos(){
        return $this->belongsTo('TvappPlaylist');
    }
}
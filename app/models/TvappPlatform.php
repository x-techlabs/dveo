<?php

class TvappPlatform extends BaseModel
{
    protected $table = 'tvapp_platforms';
    public $fillable = ['title', 'slug'];
    public $timestamps = false;

    public function playlists(){
        return $this->belongsToMany(
            TvappPlaylist::class,
            'tvapp_playlist_platforms',
            'tvapp_platform_id',
            'tvapp_playlist_id'
        );
    }
}

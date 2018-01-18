<?php

class TvappPlaylistPlatforms extends BaseModel
{
    protected $table = 'tvapp_playlist_platforms';
    public $fillable = ['tvapp_playlist_id', 'tvapp_platform_id'];
    public $timestamps = false;

}

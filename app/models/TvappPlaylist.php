<?php

class TvappPlaylist extends BaseModel
{
    protected $table = 'tvapp_playlist';
    public $fillable = ['parent_id', 'sort_order', 'shelf','playlist_category'];

    public function videos(){
        return $this->belongsToMany('Video', 'tvapp_video_in_playlist', 'tvapp_playlist_id','video_id')
            ->withPivot('type', 'sort_order')
            ->orderBy('tvapp_video_in_playlist.sort_order')
            ->withTimestamps();
    }

    public function children() {
        return $this->hasMany('TvappPlaylist', 'parent_id')
            ->orderBy('sort_order','asc');
    }

    public function platforms(){
        return $this->belongsToMany(
            TvappPlatform::class,
            'tvapp_playlist_platforms',
            'tvapp_playlist_id',
            'tvapp_platform_id'
        );
    }

    public function getFileNameAttribute() {
        return \App\Helpers\Playlists\TvappPlaylistHelper::xtrim($this->title);
    }
}

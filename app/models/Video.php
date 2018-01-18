<?php

use \App\Helpers\Playlists\TvappPlaylistHelper;

/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/20/14
 * Time: 3:21 PM
 */
class Video extends BaseModel
{
    protected $table = 'video';

    public $appends = ['video_path'];

    public function playlists(){
        return $this->belongsToMany('TvappPlaylist', 'tvapp_video_in_playlist', 'video_id', 'tvapp_playlist_id')
            ->withPivot('type', 'sort_order')
            ->withTimestamps();
    }

    public function collections(){
        return $this->belongsToMany('Collections', 'videos_in_collections', 'video_id', 'collection_id')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function getVideoPathAttribute() {
        if ($this->source == '0' || $this->source == 'internal')
        {
            $fname = 'http://'.$this->channel_id.'.1studio.tv.global.prod.fastly.net/'.$this->file_name.'.mp4';
            return $fname;
        }
        return $this->file_name;
    }

    public function getMrssThumbnailAttribute() {
        if ($this->thumbnail_name != '') {
            return $this->thumbnail_name;
        }
        return TvappPlaylistHelper::EscapeStreamUrl('https://onestudio.imgix.net/'.$this->file_name.'_1.jpg?'.'w=285&h=145&fit=crop&crop=entropy&auto=format,enhance&q=60');
    }

    function getRokuXmlThumbnailSdAttribute() {
        if ($this->thumbnail_name != '') {
            return $this->thumbnail_name;
        }
        return 'https://onestudio.imgix.net/'.$this->file_name.'_1.jpg?'.'w=285&h=145&fit=crop&crop=entropy&auto=format,enhance&q=60';
    }

    function getRokuXmlThumbnailHdAttribute() {
        if ($this->thumbnail_name != '') {
            return $this->thumbnail_name;
        }
        return 'https://onestudio.imgix.net/'.$this->file_name.'_1.jpg?'.'w=385&h=218&fit=crop&crop=entropy&auto=format,enhance&q=60';
    }

    public function collection()
    {
        return $this->hasOne('Videos_in_collections', 'video_id', 'id');
    }

    public function GetInfo($channel_id)
    {
        $fname = 'http://'.$channel_id.'.1studio.tv.global.prod.fastly.net/'.$this->file_name.'.mp4';
        if ($this->source != '0' && $this->source != 'internal') $fname = $this->file_name;

        $ans = array('title' => $this->title,
            'description' => $this->description,
            'url' => $fname,
            'thumbnail' => htmlspecialchars($this->thumbnail_name, ENT_XML1),
            'hd_img' => 'https://onestudio.imgix.net/'.$this->file_name.'_1.jpg?'.'w=385&h=218&fit=crop&crop=entropy&auto=format,enhance&q=60',
            'sd_img' => 'https://onestudio.imgix.net/'.$this->file_name.'_1.jpg?'.'w=285&h=145&fit=crop&crop=entropy&auto=format,enhance&q=60'
        );

        if ($this->thumbnail_source=='1' && $this->thumbnail_name != '')
        {
            $ans['hd_img'] = htmlspecialchars($this->thumbnail_name, ENT_XML1);
            $ans['sd_img'] = htmlspecialchars($this->thumbnail_name, ENT_XML1);
        }
        return $ans;
    }
}

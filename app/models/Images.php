<?php

use \App\Helpers\Playlists\TvappPlaylistHelper;

class Images extends BaseModel
{
    protected $table = 'images';


    public function getVideoPathAttribute() {
        if ($this->source == '0' || $this->source == 'internal')
        {
            $fname = 'https://s3.amazonaws.com/1stud-images/'.$this->file_name;
            return $fname;
        }
        return $this->file_name;
    }

}

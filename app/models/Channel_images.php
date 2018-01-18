<?php

class Channel_images extends BaseModel
{
    protected $table = 'channel_images';

    protected $fillable = array('channel_id', 'focus_hd', 'focus_sd','splash_hd','splash_sd','sides_hd','sides_sd','overhang_hd','overhang_sd');

    public $timestamps = false;
}

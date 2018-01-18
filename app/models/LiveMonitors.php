<?php

class LiveMonitors extends BaseModel
{
    protected $table = 'live_monitors';

    protected $fillable = array('channel_id', 'user_id', 'stream_url', 'title');

    public $timestamps = false;
}

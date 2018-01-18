<?php
/**
 * Created by PhpStorm.
 * User: profit
 * Date: 12/26/17
 * Time: 10:57 AM
 */

class Video_show extends BaseModel
{
	protected $table = 'video_shows';

	protected $fillable = array('video_id', 'show_id');

	public $timestamps = false;

	public function show_names()
	{
		return $this->belongsTo('Channel_show', 'show_id', 'id');
	}
}

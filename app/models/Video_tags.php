<?php
/**
 * Created by PhpStorm.
 * User: profit
 * Date: 12/25/17
 * Time: 12:01 PM
 */


class Video_tags extends BaseModel
{
	protected $table = 'video_tags';

	protected $fillable = array('video_id', 'tag_id');

	public $timestamps = false;

	public function tag_names()
	{
		return $this->belongsTo('Channel_tags', 'tag_id', 'id');
	}
}

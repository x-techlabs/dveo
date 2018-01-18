<?php
/**
 * Created by PhpStorm.
 * User: profit
 * Date: 12/25/17
 * Time: 9:49 AM
 */


class Channel_tags extends BaseModel
{
	protected $table = 'channel_tags';

	protected $fillable = array('channel_id', 'name');

	public $timestamps = false;
}

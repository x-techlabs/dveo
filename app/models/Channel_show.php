<?php
/**
 * Created by PhpStorm.
 * User: profit
 * Date: 12/26/17
 * Time: 10:56 AM
 */

class Channel_show extends BaseModel
{
	protected $table = 'channel_shows';

	protected $fillable = array('channel_id', 'name');

	public $timestamps = false;
}

<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface {

    const USER_MANAGE_PLAYOUT = 2;
    const USER_MANAGE_COMPANY = 4;
    const USER_MANAGE_CHANNEL = 8;
    const USER_MANAGE_MEDIA = 16;
    const USER_MANAGE_PAYMENT = 32;

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'token', 'remember_token');

    /**
     * Check for type
     *
     * @return string
     */
    public function checkPermissions() {
        $permissions = array();
        if($this->is(User::USER_MANAGE_PLAYOUT)) {
            array_push($permissions, '<span class="playoutWarning">Playout</span>');
        } else {
            if($this->is(User::USER_MANAGE_COMPANY)) {
                array_push($permissions, 'Company');
            }
            if($this->is(User::USER_MANAGE_CHANNEL)) {
                array_push($permissions, 'Channel');
            }
            if($this->is(User::USER_MANAGE_MEDIA)) {
                array_push($permissions, 'Media');
            }
            if($this->is(User::USER_MANAGE_PAYMENT)) {
                array_push($permissions, 'Payment');
            }
        }
        return implode(", ", $permissions);
    }

    public function checkChannelIds() {
        $users_in_channels = Users_in_channels::where('user_id', '=', Auth::user()->id)->where('channel_id', '=', BaseController::get_channel_id())->get();
        $channel_ids = array();
        foreach($users_in_channels as $user_in_channel) {
            array_push($channel_ids, $user_in_channel->channel_id);
        }
        return $channel_ids;
    }

    public function delete() {
        Users_in_channels::where('user_id', '=', $this->id)->delete();

        parent::delete();
    }
}

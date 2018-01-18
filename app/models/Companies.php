<?php

class Companies extends BaseModel
{
    protected $table = 'companies';

    public function delete() {
        $users = User::where('company_id', '=', $this->id);
        $userIds = array();
        foreach($users->get() as $user) {
            array_push($userIds, $user->id);
        }
        Users_in_channels::whereIn('user_id', $userIds)->delete();
        $users->delete();

        $channel = Channel::where('company_id', '=', $this->id);
        $channel->onDelete(function () {
            call_user_func_array(['Channel', 'onDeleteCallback'], func_get_args());
        });
        $channel->delete();
        $channel->forceDelete();

        parent::delete();
    }
}
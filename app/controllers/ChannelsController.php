<?php
class ChannelsController extends BaseController {
    public function channels() {
        if(!(Auth::user()->is(User::USER_MANAGE_CHANNEL) || Auth::user()->is(User::USER_MANAGE_MEDIA) || Auth::user()->is(User::USER_MANAGE_COMPANY))) {
            return App::abort(404);
        }
        if(!Auth::user()->is(User::USER_MANAGE_COMPANY) && Users_in_channels::where('user_id', '=', Auth::user()->id)->count() <= 1) {
            return App::abort(404);
        }
        $user = Auth::user();
        $company = Companies::find($user->company_id);
        $users_in_channels = Users_in_channels::where('user_id', '=', $user->id)->get();

        $channels = array();
        foreach($users_in_channels as $user_in_channel) {
            $channel = Channel::find($user_in_channel->channel_id);
            $date = explode(" ", $channel->created_at);
            $channel->date = $date[0];
            array_push($channels, $channel);
        }

        $this->data['user'] = $user;
        $this->data['company'] = $company;
        $this->data['channels'] = $channels;

        return $this->render('channels.channels');
    }
}

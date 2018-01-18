<?php

class AdminController extends BaseController {
    /*
     *| Render admin page
     */
    public function index() {
        if(!Auth::user()->is(User::USER_MANAGE_PLAYOUT)) {
            return App::abort(404);
        }

        return $this->render('admin/admin');
    }

    /*
     *| Get channels page
     */
    public function channels() {
        if(!Request::ajax()) return App::abort(404);

        $channels = Channel::all();
        $channel_model = new Channel;
        foreach($channels as $channel) {
            $company = Companies::find($channel->company_id);
            $channel->company_id = $company->name;

            $dveo = DveoModel::find($channel->dveo_id);
            if (empty($dveo)) continue;


            $channel->dveo_id = $dveo->ip;
            $storageSum = Video::where('channel_id',$channel->id)->sum('storage');
            $storage = $channel_model->getHumanStorage($storageSum);
            $channel->storageSize = $storage;
        }

        $allCompanies = Companies::all();
        $companies = array();
        $companies[0] = 'Select company';
        foreach($allCompanies as $company) {
            $companies[$company['id']] = $company['name'];
        }

        $allDveos = DveoModel::all();
        $dveos = array();
        $dveos[0] = 'Select DVEO';
        foreach($allDveos as $dveo) {
            $dveos[$dveo['id']] = $dveo['ip'];
        }

        $timezones = new Timezones();

        $this->data['channels'] = $channels;
        $this->data['companies'] = $companies;
        $this->data['dveos'] = $dveos;
        $this->data['timezones'] = $timezones->timezones();

        return $this->render('admin/channels');
    }

    /*
     *| Get users page
     */
    public function users() {
        if(!Request::ajax()) return App::abort(404);

        $users = User::all();
        foreach($users as $user) {
            $user->type = $user->checkPermissions();

            $company = Companies::find($user->company_id);
            if (!$company) $user->company_id = '-';
            else $user->company_id = $company->name;

            $users_in_channels = Users_in_channels::where('user_id', '=', $user->id)->get();
            $channels = array();
            foreach($users_in_channels as $user_in_channel) {
                array_push($channels, Channel::find($user_in_channel->channel_id)->title);
            }
            $user->channels = implode(", ", $channels);
        }

        $allCompanies = Companies::all();
        $companies = array();
        $companies[0] = 'Select company';
        foreach($allCompanies as $company) {
            $companies[$company['id']] = $company['name'];
        }

        $channels = Channel::lists('title', 'id');
        array_unshift($channels, "Select company for selecting channel");

        $this->data['users'] = $users;
        $this->data['companies'] = $companies;
        $this->data['channels'] = $channels;

        return $this->render('admin/users');
    }

    /*
     *| Get companies page
     */
    public function companies() {
        if(!Request::ajax()) return App::abort(404);

        $companies = Companies::all();

        $this->data['companies'] = $companies;

        return $this->render('admin/companies');
    }

    /*
     *| Get dveos page
     */
    public function dveos() {
        if(!Request::ajax()) return App::abort(404);

        $dveos = DveoModel::all();

        $this->data['dveos'] = $dveos;

        return $this->render('admin.dveos');
    }

    /*
     *| Edit channel get
     */
    public function editChannelGet() {
        if(!Request::ajax()) return App::abort(404);

        $channel = Channel::find(Input::get('channel_id'));
        // $DVEO = DveoModel::find($channel->dveo_id);
        //$dveo = new DVEO($DVEO->ip, 25599, 'apiuser', 'Hn7P67583N9m5sS');
        //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');
        // $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');

        // $allFileStreams = $dveo->get_file_streams();
        $fileStreams = array();
        // $fileStreams[0] = 'Select stream';
        // foreach($allFileStreams as $fileStream) {
        //     if($fileStream == $channel->stream) {
        //         $fileStreams[$fileStream] = $fileStream;
        //     } else if(is_null(Channel::where('stream', '=', $fileStream)->where('dveo_id', '=', $DVEO->id)->first())) {
        //         $fileStreams[$fileStream] = $fileStream;
        //     }
        // }

        $timezones = new Timezones();

        return Response::json([
            'channel' => $channel,
            'streams' => $fileStreams,
            'timezones' => $timezones->timezones()
        ]);
    }

    /*
     *| Edit channel post
     */
    public function editChannelPost() {
        $channel = Channel::find(Input::get('channel_id'));

        $channel->title = Input::get('title');
        $channel->stream = Input::get('stream');
        $channel->company_id = Input::get('company_id');
        $channel->dveo_id = Input::get('dveo_id');
        $channel->format = Input::get('format');
		$channel->playout_access = Input::get('pl_access');
        $channel->timezone = Input::get('timezone');
        $channel->save();

        $company = Companies::find($channel->company_id);
        $channel->company_id = $company->name;

        $dveo = DveoModel::find($channel->dveo_id);
//        $channel->dveo_id = $dveo->ip;

        return Response::json([
            'status' => true,
            'channel' => $channel
        ]);
    }

    /*
     *| Add channel get
     */
    public function addChannelGet() {
        if(!Request::ajax()) return App::abort(404);

        $fileStreams = array();
        $fileStreams[0] = 'Select DVEO for selecting stream';

        $timezones = new Timezones();

        return Response::json([
            'streams' => $fileStreams,
            'timezones' => $timezones->timezones()
        ]);
    }

    /*
     *| Add channel post
     */
    public function addChannelPost() {
        $channel = new Channel;

        $channel->title = Input::get('title');
        $channel->stream = (null != Input::get('stream') && !empty($Input::get('stream'))) ? Input::get('stream') : '';
        $channel->company_id = Input::get('company_id');
        // $channel->dveo_id = Input::get('dveo_id');
        $channel->format = Input::get('format');
		$channel->playout_access = Input::get('pl_access');
        $channel->timezone = Input::get('timezone');
        $channel->save();

        if ( $channel->id > 0)
        {
            $curDir = getcwd();  

            $path0 = public_path().'/tvapp/channel_'.$channel->id;
            $path1 = $path0.'/roku';
            $path2 = $path1.'/images';
            $path3 = $path1.'/xml';
            $path4 = $path3.'/playlists';

            if (!file_exists($path0)) mkdir($path0);
            if (!file_exists($path1)) mkdir($path1);
            if (!file_exists($path2)) mkdir($path2);
            if (!file_exists($path3)) mkdir($path3);
            if (!file_exists($path4)) mkdir($path4);

            chdir($curDir);
        }
        $company = Companies::find($channel->company_id);
        $channel->company_id = $company->name;

        $dveo = DveoModel::find($channel->dveo_id);
        $channel->dveo_id = (is_object($dveo)) ? $dveo->ip : 0;

        return Response::json([
            'status' => true,
            'channel' => $channel
        ]);
    }

    /*
     *| Delete channel
     */
    public function deleteChannel() {
        if(!Request::ajax()) return App::abort(404);

        $channel = Channel::find(Input::get('channel_id'));

        $channel->delete();

        return Response::json([
            'status' => true
        ]);
    }

    /*
     *| Edit user get
     */
    public function editUserGet() {
        if(!Request::ajax()) return App::abort(404);

        $user = User::find(Input::get('user_id'));

        $channels_for_companies = Channel::where('company_id', '=', $user->company_id)->get();
        $channelsOption = '';
        $channel_ids = array();
        $allChannels = Channel::all();
        foreach($channels_for_companies as $channel_for_company) {
            array_push($channel_ids, $channel_for_company->id );
        }
        foreach ($allChannels as $key => $channel) {
            if(in_array($channel->id, $channel_ids)){
                $channelsOption .= '<option value="' . $channel->id . '" selected>' . $channel->title . '</option>';
            }
            else{
                $channelsOption .= '<option value="' . $channel->id . '">' . $channel->title . '</option>';
            }
        }

        $users_in_channels = Users_in_channels::where('user_id', '=', $user->id)->get();
        $channels = array();
        foreach($users_in_channels as $user_in_channel) {
            $channel = Channel::find($user_in_channel->channel_id);
            $channels[$channel->id] = $channel->title;
        }

        $permissions = '<optgroup label="Administrator">';
        if($user->type & 2) {
            $permissions .= '<option selected value="2">Playout</option>';
        } else {
            $permissions .= '<option value="2">Playout</option>';
        }
        $permissions .= '<optgroup label="User">';
        if($user->type & 4) {
            $permissions .= '<option selected value="4">Company</option>';
        } else {
            $permissions .= '<option value="4">Company</option>';
        }
        if($user->type & 8) {
            $permissions .= '<option selected value="8">Channel</option>';
        } else {
            $permissions .= '<option value="8">Channel</option>';
        }
        if($user->type & 16) {
            $permissions .= '<option selected value="16">Media</option>';
        } else {
            $permissions .= '<option value="16">Media</option>';
        }
        if($user->type & 32) {
            $permissions .= '<option selected value="32">Payment</option>';
        } else {
            $permissions .= '<option value="32">Payment</option>';
        }
        $permissions .= '</optgroup>';

        if(!empty($channels)) {
            return Response::json([
                'user' => $user,
                'channelsOption' => $channelsOption,
                'channels' => $channels,
                'permissions' => $permissions
            ]);
        }

        return Response::json([
            'user' => $user,
            'channelsOption' => $channelsOption,
            'permissions' => $permissions
        ]);
    }

    /*
     *| Edit user post
     */
    public function editUserPost() {
        $user = User::find(Input::get('user_id'));

		$access = Input::get('access');
        $user->username = Input::get('username');
        if(Input::get('password') != '') {
            $user->password = Hash::make(Input::get('password'));
        }
        $user->email = Input::get('email');
        $user->company_id = Input::get('company_id');

        $type = 0;
        foreach(Input::get('permissions') as $permission) {
            $type += $permission;
        }
        $user->type = $type;
		$user->playout_access = $access;

        $user->save();

        $channel_ids = Input::get('channel_ids');
        if(!empty($channel_ids)) {
            Users_in_channels::where('user_id', '=', $user->id)->delete();
            foreach($channel_ids as $channel) {
                $users_in_channels = new Users_in_channels;
                $users_in_channels->user_id = $user->id;
                $users_in_channels->channel_id = $channel;
                $users_in_channels->save();
            }
        } else {
            Users_in_channels::where('user_id', '=', $user->id)->delete();
        }

        $user->company_id = Companies::find($user->company_id)->name;

        $users_in_channels = Users_in_channels::where('user_id', '=', $user->id)->get();
        $channels = array();
        foreach($users_in_channels as $user_in_channel) {
            array_push($channels, Channel::find($user_in_channel->channel_id)->title);
        }
        $channels = implode(", ", $channels);

        $user->type = $user->checkPermissions();

        return Response::json([
            'status' => true,
            'user' => $user,
            'channels' => $channels
        ]);
    }

    /*
     *| Add user post
     */
    // TODO: password changes..
    public function addUserPost() {
        $errors = [];
		$access = Input::get('access');
        if(!is_null(User::where('username', '=', Input::get('username'))->first())) {
            $errors['username'] = '<i class="fa fa-exclamation-triangle"></i> Username already exists.';
        }
        if(!is_null(User::where('email', '=', Input::get('email'))->first())) {
            $errors['email'] = '<i class="fa fa-exclamation-triangle"></i> Email already exists.';
        }
        if(!empty($errors)) {
            return Response::json([
                'status' => false,
                'errors' => $errors
            ]);
        }

        $user = new User;
		$user->playout_access = $access;
        $user->username = Input::get('username');
        $user->password = Hash::make(Input::get('password'));
        $user->email = Input::get('email');
        $user->company_id = Input::get('company_id');

        $type = 0;
        foreach(Input::get('permissions') as $permission) {
            $type += $permission;
        }
        $user->type = $type;

//        $token = str_random(64);
//        $user->token = $token;

        $user->save();

        foreach(Input::get('channel_ids') as $channel) {
            $users_in_channels = new Users_in_channels;
            $users_in_channels->user_id = $user->id;
            $users_in_channels->channel_id = $channel;
            $users_in_channels->save();
        }

//        Mail::send('emails.invite', array('token' => $token, 'username' => $user->username), function($message) use ($user) {
//            $message->to($user->email, $user->username)->subject('Invitation');
//        });

        $user->company_id = Companies::find($user->company_id)->name;

        $users_in_channels = Users_in_channels::where('user_id', '=', $user->id)->get();
        $channels = array();
        foreach($users_in_channels as $user_in_channel) {
            array_push($channels, Channel::find($user_in_channel->channel_id)->title);
        }
        $channels = implode(", ", $channels);

        $user->type = $user->checkPermissions();

        return Response::json([
            'status' => true,
            'user' => $user,
            'channels' => $channels
        ]);
    }

    /*
     *| Delete user
     */
    public function deleteUser() {
        if(!Request::ajax()) return App::abort(404);

        $user = User::find(Input::get('user_id'));

        $user->delete();

        return Response::json([
            'status' => true
        ]);
    }

    /*
     *| Restore password
     */
    public function restore() {
        if(!Request::ajax()) return App::abort(404);

        $user = User::find(Input::get('user_id'));

        $user->password = '';

        $token = str_random(64);
        $user->token = $token;

        $user->save();

        Mail::send('emails.restore', array('token' => $token, 'username' => $user->username), function($message) use ($user) {
            $message->to($user->email, $user->username)->subject('Restore password');
        });

        return Response::json([
            'status' => true
        ]);
    }

    /*
     *| Edit company get
     */
    public function editCompanyGet() {
        if(!Request::ajax()) return App::abort(404);

        $company = Companies::find(Input::get('company_id'));

        return Response::json([
            'company' => $company
        ]);
    }

    /*
     *| Edit company post
     */
    public function editCompanyPost() {
        $company = Companies::find(Input::get('company_id'));

        $company->name = Input::get('name');
        $company->save();

        return Response::json([
            'status' => true,
            'company' => $company
        ]);
    }

    /*
     *| Add company post
     */
    public function addCompanyPost() {
        $company = new Companies;

        $company->name = Input::get('name');
        $company->save();

        return Response::json([
            'status' => true,
            'company' => $company
        ]);
    }

    /*
     *| Edit company
     */
    public function deleteCompany() {
        if(!Request::ajax()) return App::abort(404);

        $company = Companies::find(Input::get('company_id'));

        $company->delete();

        return Response::json([
            'status' => true
        ]);
    }

    /*
     *| Get channels for companies
     */
    public function getChannelsForCompanies() {
        $channels = Channel::where('company_id', '=', Input::get('company_id'))->get()->lists('title', 'id');
        if(!empty($channels)) {
            return Response::json([
                'status' => true,
                'channels' => $channels
            ]);
        } else {
            return Response::json([
                'status' => false,
                'channels' => $channels
            ]);
        }
    }

    /*
     *| Get streams for dveos
     */
    public function GetStreamsForDveos() {
        $channel = Channel::find(Input::get('channel_id'));
        $DVEO = DveoModel::find(Input::get('dveo_id'));
        //$dveo = new DVEO($DVEO->ip, 25599, 'apiuser', 'Hn7P67583N9m5sS');
        //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');
        $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');

        $allFileStreams = $dveo->get_file_streams();
        $fileStreams = array();
        $fileStreams[0] = 'Select stream';
        foreach($allFileStreams as $fileStream) {
            if(!empty($channel)) {
                if($fileStream == $channel->stream) {
                    $fileStreams[$fileStream] = $fileStream;
                } else if(is_null(Channel::where('stream', '=', $fileStream)->where('dveo_id', '=', $DVEO->id)->first())) {
                    $fileStreams[$fileStream] = $fileStream;
                }
            } else if(is_null(Channel::where('stream', '=', $fileStream)->where('dveo_id', '=', $DVEO->id)->first())) {
                $fileStreams[$fileStream] = $fileStream;
            }
        }
        return Response::json([
            'status' => true,
            'streams' => $fileStreams
        ]);
    }

    /*
     *| Edit dveo get
     */
    public function editDveoGet() {
        if(!Request::ajax()) return App::abort(404);

        $dveo = DveoModel::find(Input::get('dveo_id'));

        return Response::json([
            'dveo' => $dveo
        ]);
    }

    /*
     *| Edit dveo post
     */
    public function editDveoPost() {
        $error = '';
        if(!is_null(DveoModel::where('id', '!=', Input::get('dveo_id'))->where('ip', '=', Input::get('ip'))->first())) {
            $error = '<i class="fa fa-exclamation-triangle"></i> Ip already exists.';
        }
        if(!empty($error)) {
            return Response::json([
                'status' => false,
                'error' => $error
            ]);
        }

        $dveo = DveoModel::find(Input::get('dveo_id'));

        $dveo->ip = Input::get('ip');
        $dveo->save();

        return Response::json([
            'status' => true,
            'dveo' => $dveo
        ]);
    }

    /*
     *| Add company post
     */
    public function addDveoPost() {
        $error = '';
        if(!is_null(DveoModel::where('ip', '=', Input::get('ip'))->first())) {
            $error = '<i class="fa fa-exclamation-triangle"></i> Ip already exists.';
        }
        if(!empty($error)) {
            return Response::json([
                'status' => false,
                'error' => $error
            ]);
        }

        $dveo = new DveoModel;

        $dveo->ip = Input::get('ip');
        $dveo->save();

        return Response::json([
            'status' => true,
            'dveo' => $dveo
        ]);
    }

    /*
     *| Edit company
     */
    public function deleteDveo() {
        if(!Request::ajax()) return App::abort(404);

        $dveo = DveoModel::find(Input::get('dveo_id'));

        $dveo->delete();

        return Response::json([
            'status' => true
        ]);
    }

    /*
     * | Reload page
     * */
    public function reload() {
        //
    }
}

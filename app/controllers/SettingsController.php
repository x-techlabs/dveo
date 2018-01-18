<?php
require_once base_path('vendor/chargebee/chargebee-php/lib/ChargeBee.php');
use Input;

class SettingsController extends BaseController {


    public function index() {
//        if(!Auth::user()->is(User::USER_MANAGE_CHANNEL)) {
//            return App::abort(404);
//        }

        $customerID = Auth::user()->id;
        // ChargeBee_Environment::configure("onestudio-test","test_r3HdwSMA0Yrgcd4nIdZRwA6lDG4KLaU3B");
        ChargeBee_Environment::configure("onestudio","live_wBXGhbEh4ZqG1tTdz4ZCP9T4aBSBWMdY");
        $result = ChargeBee_Customer::retrieve($customerID);
        $customer = $result->customer();
        $billingInfo = $customer->billingAddress;

        $invoices = ChargeBee_Invoice::invoicesForCustomer($customerID);
        $invoicesArray = [];

        foreach($invoices as $item){
            $invoice = $item->invoice();
            array_push($invoicesArray,$invoice);
        }

        if($customer->cardStatus !== 'no_card'){
            $result = ChargeBee_Card::retrieve($customerID);
            $card = $result->card();
        }
        else{
            $card = '';
        }

        $subscriptionsArray = []; 

        $subscriptions = ChargeBee_Subscription::subscriptionsForCustomer("$customerID");
        foreach($subscriptions as $entry){
            $subscription = $entry->subscription();
            array_push($subscriptionsArray,$subscription);
        }

        $channel_id = BaseController::get_channel_id();
        $channel = Channel::find($channel_id);

        if($channel['format'] == 'hd') {
            $format = array('hd' => 'true', 'sd' => '');
        } else {
            $format = array('hd' => '', 'sd' => 'true');
        }

        if(is_null($channel['launchpad_url'])){
            $launchpad_url = '';
        }else{
            $launchpad_url = $channel['launchpad_url'];
        }
        if(is_null($channel['mobileWebUrl'])){
            $mobileWebUrl = '';
        }else{
            $mobileWebUrl = $channel['mobileWebUrl'];
        }

        $timezones = new Timezones();

        $this->data['channel'] = $channel;
        $this->data['format'] = $format;
		$this->data['channel_tags'] = Channel_tags::where('channel_id',$channel_id)->get();
		$this->data['channel_show'] = Channel_show::where('channel_id',$channel_id)->get();
		$this->data['launchpad_url'] = $launchpad_url;
        $this->data['mobileWebUrl'] = $mobileWebUrl;
        $this->data['timezones'] = $timezones->timezones();
        $this->data['selectedTimezone'] = $channel['timezone'];
        $this->data['source'] = $channel['source'];
		$this->data['collections'] = array();
		$this->data['part2'] = $this->render('video/add_video');
		$this->data['title'] = "";
		$this->data['extLink'] = "";
		$this->data['source_dd'] = "unknown";
        $an = explode('|', $channel['analytics']);
        while(count($an) < 5) $an[] = '';
        $this->data['analytics'] = $an;

        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Overhang_SD.jpg';
        if (!file_exists($destinationPath)) $destinationPath = '';
        $this->data['overhang_sd'] = str_replace(public_path(), 'http://1stud.io', $destinationPath);

        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Overhang_HD.jpg';
        if (!file_exists($destinationPath)) $destinationPath = '';
        $this->data['overhang_hd'] = str_replace(public_path(), 'http://1stud.io', $destinationPath);

        // add images
         $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Focus_HD.jpg';
        if (!file_exists($destinationPath)) $destinationPath = '';
         $this->data['focus_hd'] = str_replace(public_path(), URL::to('/'), $destinationPath);
 
         $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Focus_SD.jpg';
         if (!file_exists($destinationPath)) $destinationPath = '';
         $this->data['focus_sd'] = str_replace(public_path(), URL::to('/'), $destinationPath);
            
         $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Splash_HD.jpg';
         if (!file_exists($destinationPath)) $destinationPath = '';
         $this->data['splash_hd'] = str_replace(public_path(), URL::to('/'), $destinationPath);
        // var_dump($this->data['splash_hd']);die;
 
         $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Splash_SD.jpg';
         if (!file_exists($destinationPath)) $destinationPath = '';
         $this->data['splash_sd'] = str_replace(public_path(), URL::to('/'), $destinationPath);

  
        // end

        $data = $this->GetStorageData($channel['storage']);
        $this->data['layout'] = $data[1];
        $this->data['login'] = $data[3];
        $this->data['login_url'] = $data[5];
        $this->data['bgcolor'] = $data[7];
        $this->data['activation_url'] = $data[9];
        $this->data['login_signup_text'] = $data[11];
        $this->data['ustream_api_key'] = $data[13];
        $this->data['ustream_app_name'] = $data[15];
        $this->data['loginMode'] = $data[17];
        // Chargebee
        $this->data['customer'] = $customer;
        $this->data['billingInfo'] = $billingInfo;
        $this->data['card'] = $card;
        $this->data['subscriptionsArray'] = $subscriptionsArray;
        $this->data['invoicesArray'] = $invoicesArray;

        return $this->render('settings');
    }

	// Show manager
	public function addShow()
	{
		$channel_id = BaseController::get_channel_id();
		$redirect_url = "channel_$channel_id/settings/#tab13";
		$name = Input::get('showname');
		$show = new Channel_show;

		$show->channel_id = $channel_id;
		$show->name = $name;

		$show->save();

		if($show){
			return Redirect::to($redirect_url)->with('success','Operation Successful !');
		}
	}
	public function editShow()
	{
		$channel_id = BaseController::get_channel_id();
		$show_id = Input::get('show_id');
		$name = Input::get('editshowname');
		$redirect_url = "channel_$channel_id/settings/#tab13";
		if(isset($show_id) && !empty($show_id)){
			$show = Channel_show::find($show_id);
			$show->name = $name;
			$show->save();
			if($show){
				return Redirect::to($redirect_url)->with('success','Operation Successful !');
			}
		}
		else{
			return Redirect::to($redirect_url)->with('message','Show is not defined!!!');
		}
	}
	public function activate_show(){
		$channel_id = BaseController::get_channel_id();
		$status = Input::get('show_status');
		$channel = Channel::find($channel_id);
		$channel->display_show = $status;
		$channel->save();
		$redirect_url = "channel_$channel_id/settings/#tab13";
		return Redirect::to($redirect_url)->with('success','Operation Successful !');
	}
	public function deleteShow()
	{
		$show_id = Input::get('show_id');
		if(isset($show_id) && !empty($show_id)){

			$row = Channel_show::find($show_id);
			$row->delete();
			echo json_encode(array('success' => true));die;
		}
		else{
			echo json_encode(array('success' => false));die;
		}

	}
	public function getShow()
	{
		$show_id = Input::get('show_id');
		if(isset($show_id) && !empty($show_id)){
			$show = Channel_show::find($show_id);
			echo json_encode(array(
				'success' => true,
				'result' => $show
			));
			die;
		}
		else{
			echo json_encode(array(
				'success' => false,
				'result'  => ''
			));
			die;
		}
	}

	// Tag manager

	public function addTag()
	{
		$channel_id = BaseController::get_channel_id();
		$redirect_url = "channel_$channel_id/settings/#tab12";
		$name = Input::get('tagname');
		$tag = new Channel_tags;

		$tag->channel_id = $channel_id;
		$tag->name = $name;

		$tag->save();

		if($tag){
			return Redirect::to($redirect_url)->with('success','Operation Successful !');
		}
	}
	public function editTag()
	{
		$channel_id = BaseController::get_channel_id();
		$tag_id = Input::get('tag_id');
		$name = Input::get('edittagname');
		$redirect_url = "channel_$channel_id/settings/#tab12";
		if(isset($tag_id) && !empty($tag_id)){
			$tag = Channel_tags::find($tag_id);
			$tag->name = $name;
			$tag->save();
			if($tag){
				return Redirect::to($redirect_url)->with('success','Operation Successful !');
			}
		}
		else{
			return Redirect::to($redirect_url)->with('message','Tag is not defined!!!');
		}
	}
	public function deleteTag()
	{
		$tag_id = Input::get('tag_id');
		if(isset($tag_id) && !empty($tag_id)){

			$row = Channel_tags::find($tag_id);
			$row->delete();
			echo json_encode(array('success' => true));die;
		}
		else{
			echo json_encode(array('success' => false));die;
		}

	}
	public function getTag()
	{
		$tag_id = Input::get('tag_id');
		if(isset($tag_id) && !empty($tag_id)){
			$tag = Channel_tags::find($tag_id);
			echo json_encode(array(
				'success' => true,
				'result' => $tag
			));
			die;
		}
		else{
			echo json_encode(array(
				'success' => false,
				'result'  => ''
			));
			die;
		}
	}


    public function live_monitor(){
        
        $user_id = Auth::user()->id;
        $channel_id = BaseController::get_channel_id();
        $monitors = LiveMonitors::where('user_id', '=', $user_id)
        ->where('channel_id', '=', $channel_id)
        ->get();

        $this->data['monitors'] = $monitors;

        return $this->render('live_monitor');
    }

    public function delete_stream()
    {
        $stream_id = Input::get('stream_id');
        $monitor = LiveMonitors::find($stream_id);

        $monitor->delete();
        if($monitor){
            echo json_encode(array('success' => true));die;
        }
    }


    public function edit_stream()
    {
        $stream_id = Input::get('stream_id');
        $title = Input::get('monitorTitle');
        $stream_url = Input::get('monitorStream');
        $monitor = LiveMonitors::find($stream_id);
        $monitor->stream_url = $stream_url;
        $monitor->title = $title;
        $monitor->save();
        if($monitor){
            return Redirect::back()->with('message','Operation Successful !');
        }
    }

    public function addMonitor()
    {
        $data = Input::all();
        $user_id = Auth::user()->id;
        $channel_id = BaseController::get_channel_id();
        $title = Input::get('title');
        $stream_url = Input::get('stream_url');
        $monitors = new LiveMonitors;

        $monitors->channel_id = $channel_id;
        $monitors->user_id = $user_id;
        $monitors->stream_url = $stream_url;
        $monitors->title = $title;

        $monitors->save();

        if($monitors){
            return Redirect::back()->with('message','Operation Successful !');
        }
    }

    public function payment_method()
    {
        $data = Input::all();
        $customerId = Auth::user()->id;
        // ChargeBee_Environment::configure("onestudio-test","test_r3HdwSMA0Yrgcd4nIdZRwA6lDG4KLaU3B");
        ChargeBee_Environment::configure("onestudio","live_wBXGhbEh4ZqG1tTdz4ZCP9T4aBSBWMdY");
        $result = ChargeBee_Customer::retrieve($customerId);
        $customer = $result->customer();
        $firstName = Input::get('firstName');
        $lastName = Input::get('lastName');
        $card_number = Input::get('card_number');
        $cvv = Input::get('cvv');
        $month = Input::get('month');
        $year = Input::get('year');
        $address = Input::get('address');
        $ext_address = Input::get('ext_address');
        $city = Input::get('city');
        $zip = Input::get('zip');
        $country = Input::get('country');
        $state = Input::get('state');

        if($customer->cardStatus == 'no_card'){
            
            $result = ChargeBee_PaymentSource::createCard(array(
              "customerId" => "$customerId", 
              "card" => array(
                "firstName" => $firstName, 
                "lastName" => $lastName, 
                "number" => $card_number, 
                "cvv" => $cvv,
                "expiryMonth" => $month, 
                "expiryYear" => $year,
                "billingAddr1" => $address,
                "billingAddr2" => $ext_address,
                "billingCity" => $city,
                "billingZip" => $zip,
                "billingCountry" => $country,
                "billingState" => $state,
              )));
            $customer = $result->customer();
            $paymentSource = $result->paymentSource();
        }else{
            $result = ChargeBee_Card::updateCardForCustomer("$customerId", 
                array(
                    "firstName" => $firstName,
                    "lastName" => $lastName, 
                    "number" => $card_number, 
                    "cvv" => $cvv,
                    "expiryMonth" => $month, 
                    "expiryYear" => $year,
                    "billingAddr1" => $address,
                    "billingAddr2" => $ext_address,
                    "billingCity" => $city,
                    "billingZip" => $zip,
                    "billingCountry" => $country,
                    "billingState" => $state,
                )
            );
            $customer = $result->customer();
            $card = $result->card();
        }

        echo "<pre>";
        var_dump($customer);
        die;

    }

    public function updateBilling(){
        $data = Input::all();
        $customerId = Auth::user()->id;
        $firstName = Input::get('firstName');
        $lastName = Input::get('lastName');
        $email = Input::get('email');
        $phone = Input::get('phone');
        $company = Input::get('company');
        $line1 = Input::get('line1');
        $line2 = Input::get('line2');
        $line3 = Input::get('line3');
        $city = Input::get('city');
        $zip = Input::get('zip');
        $country = Input::get('country');
        $state = Input::get('state');
        // echo "<pre>";

        // ChargeBee_Environment::configure("onestudio-test","test_r3HdwSMA0Yrgcd4nIdZRwA6lDG4KLaU3B");
        ChargeBee_Environment::configure("onestudio","live_wBXGhbEh4ZqG1tTdz4ZCP9T4aBSBWMdY");
        $result = ChargeBee_Customer::updateBillingInfo("34", array(
          "billingAddress" => array(
            "firstName" => $firstName, 
            "lastName" => $lastName,
            "email" => $email,
            "company" => $company,
            "phone" => $phone,
            "line1" => $line1, 
            "line2" => $line2, 
            "line3" => $line3, 
            "city" => $city, 
            "state" => $state, 
            "zip" => $zip, 
            "country" => $country
          )));
        $customer = $result->customer();
        var_dump($customer);
    }

    public function updateAccount()
    {
        $data = Input::all();
        $customerId = Auth::user()->id;
        $firstName = Input::get('firstName');
        $lastName = Input::get('lastName');
        $email = Input::get('email');
        $phone = Input::get('phone');
        $company = Input::get('company');
        // ChargeBee_Environment::configure("onestudio-test","test_r3HdwSMA0Yrgcd4nIdZRwA6lDG4KLaU3B");
        ChargeBee_Environment::configure("onestudio","live_wBXGhbEh4ZqG1tTdz4ZCP9T4aBSBWMdY");
        $result = ChargeBee_Customer::update("$customerId", 
            array(
                "firstName" => $firstName, 
                "lastName" => $lastName,
                "email" => $email,
                "phone" => $phone,
                "company" => $company
            )
        );
        $customer = $result->customer();
    }

    public function show_modal($channel_id,$action)
    {
        $customerID = Auth::user()->id;
        // ChargeBee_Environment::configure("onestudio-test","test_r3HdwSMA0Yrgcd4nIdZRwA6lDG4KLaU3B");
        ChargeBee_Environment::configure("onestudio","live_wBXGhbEh4ZqG1tTdz4ZCP9T4aBSBWMdY");
        $result = ChargeBee_Customer::retrieve($customerID);
        $customer = $result->customer();
        $billingInfo = $customer->billingAddress;
        if($customer->cardStatus !== 'no_card'){
            $result = ChargeBee_Card::retrieve($customerID);
            $card = $result->card();
        }
        else{
            $card = '';
        }
        return View::make("chargebee.$action")
            ->with('customer', $customer)
            ->with('billingInfo', $billingInfo)
            ->with('card', $card)
            ->render();
    }

    public function cancel_subscription()
    {
        $id = Input::get('id');
        if(isset($id) && !empty($id)){
            ChargeBee_Environment::configure("onestudio","live_wBXGhbEh4ZqG1tTdz4ZCP9T4aBSBWMdY");
            $result = ChargeBee_Subscription::cancel("$id");
            $subscription = $result->subscription();
            if(count($subscription) > 0 && $subscription->status == 'cancelled'){
                echo json_encode(array('success' => true));
                die;
            }
            else{
                echo json_encode(array('success' => false));
                die;
            }
        }
    }

    public function GetStorageData($storage)
    {
        $data = explode('|', $storage);
        while(count($data) < 20) $data[] = '';

        $data[1] = ($data[1] == '') ? 'linear' : $data[1];
        $data[3] = ($data[3] == '') ? 'no' : $data[3];
        $data[7] = ($data[7]=='') ? '#000000' : $data[7];

        return $data;
    }

    public function edit() {
        $channel = $this->get_channel();

        if(empty(trim(Input::get('title')))) {
            return Response::json([
                'title' => true
            ], 200);
        } else {
            $channel['title'] = trim(Input::get('title'));
            $channel['format'] = trim(Input::get('format'));
            $channel['timezone'] = trim(Input::get('timezone'));
            $channel['source'] = trim(Input::get('source'));

            $data[] = 'layout|'.trim(Input::get('layout'));
            $data[] = 'login|'.trim(Input::get('login'));
            $data[] = 'login_url|'.trim(Input::get('login_url'));
            $data[] = 'bgcolor|'.trim(Input::get('bgcolor'));
            $data[] = 'activation_url|'.trim(Input::get('activation_url'));
            $data[] = 'login_signup_text|'.trim(Input::get('login_signup_text'));
            $data[] = 'ustream_api_key|'.trim(Input::get('ustream_api_key'));
            $data[] = 'ustream_app_name|'.trim(Input::get('ustream_app_name'));
            $data[] = 'loginMode|'.trim(Input::get('loginMode'));
            
            $channel['storage'] = implode('|', $data);

            $channel->save();
            $this->set_channel($channel);
            $this->write_settings_file();

            //return Response::json(['status' => true], 200);
        }

        return $this->index();

    }

    public function set_stream_url() {
        $channel = $this->get_channel();

        $channel['stream_url'] = trim(Input::get('stream_url'));

        $channel->save();
        $this->set_channel($channel);

        return Response::json([
            'status' => true
        ], 200);
    }

    public function upload_logo2() {
        $file            = Input::file('file');
        $destinationPath = 'images/logo';
        $filename        = str_random(6) . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        $this->write_settings_file();
    }
    public function upload_logo()
    {
        $file            = Input::file('file');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'overhangLogo.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
    }

    public function upload_focus_sd()
    {
        $file            = Input::file('file_focus_sd');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'Focus_SD.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->focus_sd = $filename;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->focus_sd = $filename;
            $data->save();
        }
        $this->write_settings_file();
        // return $this->index();
        return Redirect::back()->with('tab', 2);
    }

     public function upload_focus_hd()
    {
        $file            = Input::file('file_focus_hd');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'Focus_HD.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->focus_sd = $filename;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->focus_sd = $filename;
            $data->save();
        }
        $this->write_settings_file();
        // return $this->index();
        return Redirect::back()->with('tab', 2);
    }

    public function upload_splash_hd()
    {
        $file            = Input::file('file_splash_hd');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'Splash_HD.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->splash_hd = $filename;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->splash_hd = $filename;
            $data->save();
        }
        $this->write_settings_file();
        // return $this->index();
        return Redirect::back()->with('tab', 2);
    }
    public function upload_splash_sd()
    {
        $file            = Input::file('file_splash_sd');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'Splash_SD.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->splash_sd = $filename;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->splash_sd = $filename;
            $data->save();
        }
        $this->write_settings_file();
        // return $this->index();
        return Redirect::back()->with('tab', 2);
    }
    public function upload_sides_hd()
    {
        $file            = Input::file('file_sides_hd');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'Sides_HD.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->sides_hd = $filename;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->sides_hd = $filename;
            $data->save();
        }
        $this->write_settings_file();
        // return $this->index();
        return Redirect::back()->with('tab', 2);
    }
    public function upload_sides_sd()
    {
        $file            = Input::file('file_sides_sd');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'Sides_SD.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->sides_sd = $filename;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->sides_sd = $filename;
            $data->save();
        }
        $this->write_settings_file();
        // return $this->index();
        return Redirect::back()->with('tab', 2);
    }

    public function upload_overhang_sd() {
        $file            = Input::file('file_overhang_sd');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'Overhang_SD.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->overhang_sd = $filename;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->overhang_sd = $filename;
            $data->save();
        }
        $this->write_settings_file();
        // return $this->index();
        return Redirect::back()->with('tab', 2);
    }

    public function upload_overhang_hd() {
        $file            = Input::file('file_overhang_hd');

        $channel_id = BaseController::get_channel_id();
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images';
        $filename        = 'Overhang_HD.jpg';
        $oldFile = $destinationPath.'/'.$filename;
        if(file_exists($oldFile)) unlink($oldFile);
        $file->move($destinationPath, $filename);
        $row = Channel_images::where('channel_id',$channel_id)->first();
        if(!empty($row) && count($row) > 0){
            $row->overhang_hd = $filename;
            $row->save();
        }
        else{
            $data = new Channel_images;
            $data->channel_id = $channel_id;
            $data->overhang_hd = $filename;
            $data->save();
        }

        $this->write_settings_file();
        // return $this->index();
        return Redirect::back()->with('tab', 2);
    }

    // Distro functions

    public function setMobileUrl(){

        $channel = $this->get_channel();
        $channel['mobileWebUrl'] = Input::get('mobileUrl');
        $channel->save();

        return Redirect::back()->with('success','Operation Successful !');
    }
    public function setAmazonUrl(){

        $channel = $this->get_channel();
        $channel['amazon_fire_url'] = Input::get('amazonUrl');
        $channel->save();

        return Redirect::back()->with('success','Operation Successful !');
    }
    public function setAppleUrl(){

        $channel = $this->get_channel();
        $channel['apple_tv_url'] = Input::get('appleUrl');
        $channel->save();

        return Redirect::back()->with('success','Operation Successful !');
    }
    public function setRokuUrl(){

        $channel = $this->get_channel();
        $channel['roku_tv_url'] = Input::get('rokuUrl');
        $channel->save();

        return Redirect::back()->with('success','Operation Successful !');
    }
    public function display_distro(){
        $option = Input::get('option');
        $distro = Input::get('distro');
        if($option == 'yes'){
            $option_value = 1;
        }
        else if($option == 'no'){
            $option_value = 0;
        }
        else{
            $option_value = '';
        }
        if($distro == 'roku'){
            $field = 'display_roku';
        }
        else if($distro == 'apple'){
            $field = 'display_appletv';

        }
        else if($distro == 'amazon'){
            $field = 'display_firetv';

        }
        else if($distro == 'mobile'){
            $field = 'display_mobileweb';

        }
        else{
            $field = '';
        }
        if($option_value !== '' && $field !== ''){
            $channel = $this->get_channel();
            $channel[$field] = $option_value;
            $channel->save();

            print 'success';
        }
        else{
            print 'failed';
            
        }

    }

    //========================================================================== 
    //========================================================================== 
    //========================================================================== 
    public function analytics_set_status()
    {
        $channel = $this->get_channel();
        $channel['analytics'] = Input::get('status');
        $channel['streamlyzer_token'] = Input::get('token');
        $channel['tracking_id'] = Input::get('tracking_id');
        $channel->save();

        $this->write_settings_file();
        print 'success';
    }

    public function set_preRolls(){

        $channel = $this->get_channel();
        $channel['prerolls'] = Input::get('preroll');
        $channel->save();

        return Redirect::back()->with('success','Operation Successful !');
    }

    public function set_launchpad_url(){

        $channel = $this->get_channel();
        $channel['launchpad_url'] = Input::get('url');
        $channel->save();

        print 'success';
    }

    public function set_mobileWeb_url(){

        $channel = $this->get_channel();
        $channel['mobileWebUrl'] = Input::get('url');
        $channel->save();

        print 'success';
    }

    public function write_settings_file() 
    {
        $channel_id = BaseController::get_channel_id(); 	
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Overhang-SD1.jpg';
        $imgsd = (file_exists($destinationPath)) ? "images/Overhang-SD1.jpg" : '';

        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Overhang-HD1.jpg';
        $imghd = (file_exists($destinationPath)) ? "images/Overhang-HD1.jpg" : '';

        // 
        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Focus-HD1.jpg';
        $focushd = (file_exists($destinationPath)) ? "images/Focus-HD1.jpg" : '';

        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Focus-SD1.jpg';
        $focussd = (file_exists($destinationPath)) ? "images/Focus-SD1.jpg" : '';

        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Splash-HD1.jpg';
        $splashhd = (file_exists($destinationPath)) ? "images/Splash-HD1.jpg" : '';

        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Splash-SD1.jpg';
        $splashsd = (file_exists($destinationPath)) ? "images/Splash-SD1.jpg" : '';

        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Sides-HD1.jpg';
        $sideshd = (file_exists($destinationPath)) ? "images/Sides-HD1.jpg" : '';

        $destinationPath = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/Sides-SD1.jpg';
        $sidessd = (file_exists($destinationPath)) ? "images/Sides-SD1.jpg" : '';


        $c = Channel::find($channel_id);
        $data = $this->GetStorageData($c['storage']);
        while(count($data) < 20) $data[] = '';

        $feed[] = 'channelName="'.$c['title'].'"';
        $feed[] = 'channelLogo="http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'"';
        $feed[] = 'root_path="http://1stud.io/tvapp/channel_'.$channel_id.'/roku/"';
        $feed[] = 'channel_id="'.$channel_id.'"';
        $feed[] = 'active_layout="'.$data[1].'"'; 
        $feed[] = 'seedfile_linear="xml/categories_linear.xml"';
        $feed[] = 'seedfile_grid="xml/categories_linear.xml"';
        $feed[] = 'theme_OverhangOffsetSD_X="0"';
        $feed[] = 'theme_OverhangOffsetSD_Y="0"';
        $feed[] = 'theme_OverhangSliceSD=""';
        $feed[] = 'theme_OverhangLogoSD="'.$imgsd.'"';
        $feed[] = 'theme_BackgroundColor="'.$data[7].'"';
        $feed[] = 'theme_OverhangOffsetHD_X="0"';
        $feed[] = 'theme_OverhangOffsetHD_Y="0"';
        $feed[] = 'theme_OverhangSliceHD=""';
        $feed[] = 'theme_OverhangLogoHD="'.$imghd.'"';
        // 
        $feed[] = 'theme_FocusOffsetSD_X="0"';
        $feed[] = 'theme_FocusOffsetSD_Y="0"';
        $feed[] = 'theme_FocusSliceSD=""';
        $feed[] = 'theme_FocusLogoSD="'.$focussd.'"';
        // splash_hd
        $feed[] = 'theme_SplashOffsetHD_X="0"';
        $feed[] = 'theme_SplashOffsetHD_Y="0"';
        $feed[] = 'theme_SplashSliceHD=""';
        $feed[] = 'theme_SplashLogoHD="'.$splashhd.'"';
        // splash_sd
        $feed[] = 'theme_SplashOffsetSD_X="0"';
        $feed[] = 'theme_SplashOffsetSD_Y="0"';
        $feed[] = 'theme_SplashSliceSD=""';
        $feed[] = 'theme_SplashLogoSD="'.$splashsd.'"';
        // sides_hd
        $feed[] = 'theme_SidesOffsetHD_X="0"';
        $feed[] = 'theme_SidesOffsetHD_Y="0"';
        $feed[] = 'theme_SidesSliceHD=""';
        $feed[] = 'theme_SidesLogoHD="'.$sideshd.'"';
        // sides_sd
        $feed[] = 'theme_SidesOffsetSD_X="0"';
        $feed[] = 'theme_SidesOffsetSD_Y="0"';
        $feed[] = 'theme_SidesSliceSD=""';
        $feed[] = 'theme_SidesLogoSD="'.$sidessd.'"';
        // 
        
        $feed[] = 'login="'.$data[3].'"';
        $feed[] = 'APIUrl="'.$data[5].'"';
        $feed[] = 'loginmode="'.$data[17].'"';
        $feed[] = 'RokuActivation="'.$data[9].'"';
        $feed[] = 'signupText="'.$data[11].'"';
        $feed[] = 'loginPageDesign="#000000^5^12^90^80^15^35^Username^15^50^Password^40^35^40^50^images/input_normal.png^images/input_selected.png^images/input_button.jpg^images/loginpanel.jpg"';

        if ($c->source=='ustream')
        {
            $rootNodes = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', '0')->where('type', '=', '1')->orderBy('sort_order')->get();

            $ustreamList = array();
            foreach($rootNodes as $rootNode)
            {
                if (!is_object($rootNode)) continue;
                if (intval($rootNode->title) > 0) $ustreamList[] = $rootNode->title; 
            }


            $feed[] = 'appType="ustream"';
            $feed[] = 'ustream_secret_code="'.$data[13].'"';
            $feed[] = 'ustream_app_id="'.$data[15].'"';
            $feed[] = 'channel_id_list="'.implode(',', $ustreamList).'"'; // "20488137,18651884,18844875,19309263,20202437,19853040,20876986,19010523"
        }
        else
        {
            $feed[] = 'appType="1studio"';
        }

        $feed[] = 'analytics="'.$c->analytics.'"';


        $xmlv = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<settings '."\n ".implode("\n ", $feed)."\n />\n";
    	$path = public_path().'/tvapp/channel_'.$channel_id.'/roku/settings.xml';
    	File::put($path, $xmlv);

        //print 'Settings successfully saved...';
    }
}

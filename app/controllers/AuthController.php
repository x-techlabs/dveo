<?php
/**
 * Created by PhpStorm.
 * User: ffffff
 * Date: 27.08.14
 * Time: 22:02
 */


require_once base_path('vendor/chargebee/chargebee-php/lib/ChargeBee.php');
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Storage\Session;
use \OAuth\ServiceFactory;
class AuthController extends BaseController {

    public function showLogin()
    {
        // Check if we already logged in
        if (Auth::check())
        {
            // Redirect to homepage
            return Redirect::to('')->with('success', 'You are already logged in');
        }
		$service = 'Google';
		$storage = new Session();
		$serviceFactory = new ServiceFactory();
		$credentials = new Credentials(
			Config::get("oauth-4-laravel::consumers.$service.client_id"),
			Config::get("oauth-4-laravel::consumers.$service.client_secret"),
			Config::get("oauth-4-laravel::consumers.$service.redirect_signin")
		);
		$signupCredentials = new Credentials(
			Config::get("oauth-4-laravel::consumers.$service.client_id"),
			Config::get("oauth-4-laravel::consumers.$service.client_secret"),
			Config::get("oauth-4-laravel::consumers.$service.redirect_signup")
		);
		// get google service
		$googleService = $serviceFactory->createService('google', $credentials, $storage, array('userinfo_email', 'userinfo_profile'));
		$googleServiceSignup = $serviceFactory->createService('google', $signupCredentials, $storage, array('userinfo_email', 'userinfo_profile'));

		// get googleService authorization
		$urlSignin = $googleService->getAuthorizationUri();
		$urlSignup = $googleServiceSignup->getAuthorizationUri();

		// get data from input
		$code = Input::get( 'code' );
		if ( !empty( $code ) ) {

			// This was a callback request from google, get the token
			$token = $googleService->requestAccessToken( $code );

			// Send a request with it
			$result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );

			$email = $result['email'];

			//Check is this email present
			$userCheck = User::where('email', '=', $email)->first();
			if (!empty($userCheck)) {

				Auth::login($userCheck, true);
				if(Auth::user()->type == 2) {
					return Redirect::to('admin');
				} else {
					return Redirect::to('/');
				}
			}
			else{
				return Redirect::to('login')->withErrors(array('noUser' => 'User not exist with this email'));
			}

		}

        // Show the login page
        return View::make('auth/login')->with('urlSignin', (string)$urlSignin)->with('urlSignup', (string)$urlSignup);

    }

    public function postLogin()
    {
        // Get all the inputs
        // id is used for login, username is used for validation to return correct error-strings
        $userdata = array(
            'username' => Input::get('username'),
            'password' => Input::get('password')
        );

        // Declare the rules for the form validation.
        $rules = array(
            'username'  => 'Required',
            'password'  => 'Required'
        );

        // Validate the inputs.
        $validator = Validator::make($userdata, $rules);

        // Check if the form validates with success.
        if ($validator->passes())
        {
            // Try to log the user in.
            if (Auth::attempt($userdata))
            {
                // Redirect to homepage
                if(Auth::user()->type == 2) {
                    return Redirect::to('admin');
                } else {
                    return Redirect::to('/');
                }
            }
            else
            {
                // Redirect to the login page.
                return Redirect::to('login')->withErrors(array('password' => 'Password invalid'))->withInput(Input::except('password'));
            }
        }

        // Something went wrong.
        return Redirect::to('login')->withErrors($validator)->withInput(Input::except('password'));
    }

    public function rest_login()
    {
    	
    	// Get all the inputs
    	// id is used for login, username is used for validation to return correct error-strings
    	$userdata = array(
    			'username' => Input::get('username'),
    			'password' => Input::get('password')
    	);
    
    	// Declare the rules for the form validation.
    	$rules = array(
    			'username'  => 'Required',
    			'password'  => 'Required'
    	);
    
    	// Validate the inputs.
    	$validator = Validator::make($userdata, $rules);
    
    	// Check if the form validates with success.
    	if ($validator->passes())
    	{
    		// Try to log the user in.
    		if (Auth::attempt($userdata))
    		{
    			 return Response::json([
    		        'status' => true,
    		        'logged' => 'ok'
    	         ], 200);
    		}
    		else
    		{
    			 return Response::json([
    		        'status' => false,
    		        'logged' => 'wrong credentials c'
    	         ], 200);
    		}
    	}
    
    	// Something went wrong.
        return Response::json([
    	    'status' => false,
    	    'logged' => 'wrong credentials x:'.$userdata['username'].' '.$userdata['password']
    	], 200);
    }
    
    public function getLogout()
    {
        // Log out
        Auth::logout();

        // Redirect to homepage
        return Redirect::to('')->with('success', 'You are logged out');
    }

    public function invite($token) {
        $user = User::where('token', '=', $token)->first();

        if(!is_null($user)) {
            return View::make('emails.password');
        } else {
            return App::abort(404);
        }
    }

    public function register($token) {
        $password = Input::get('password');

        if($password) {
            $user = User::where('token', '=', $token)->first();
            $user->password = Hash::make($password);

            Auth::loginUsingId($user->id);

            $user->token = '';
            $user->save();
        } else {
            return 'Password not inserted';
        }

        return Redirect::to('/');
    }


    public function showSignup(){
		// Show the login page

		$code = Input::get( 'code' );

		$googleService = OAuth::consumer( 'Google' );

		if ( !empty( $code ) ) {

			$token = $googleService->requestAccessToken( $code );

			$result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );

		}
		else{
			$result = array();
		}

		return View::make('auth/signup')->with('userdata', $result);
	}


	public function postSignup(){

		$data = Input::all();
		$company_email = Input::get('email');
		$company_type = Input::get('company_type');
		$channel_name = Input::get('channel_name');
		$company_name = Input::get('company');
		$first_name = Input::get('first_name');
		$last_name = Input::get('last_name');
		$username = Input::get('username');
		$password = Input::get('password');
		$phone = Input::get('phone');
		$plan = Input::get('plan');

		$rules = array(
			'email'  => 'required|unique:users|email',
			'company_type'  => 'required',
			'channel_name'  => 'required',
			'first_name'  => 'required',
			'last_name'  => 'required',
			'username'  => 'required|unique:users',
			'password'  => 'required|min:6',
			'company'  => 'required',
			'phone'  => 'required|max:15',
			'plan'  => 'required',
		);
		// Validate the inputs.
		$validator = Validator::make($data, $rules);

		if ($validator->passes())
		{
			// Create company
			$company = new Companies;
			$company->name = $company_name;
			$company->type = intval($company_type);
			$company->save();

			// Create channel
			$channel = new Channel;
			$channel->title = $channel_name;
			$channel->format = 'hd';
			$channel->timezone = 'US/Hawaii';
			$channel->company_id = $company->id;
			$channel->save();

			// Create user

			$user = new User;
			$user->username = $username;
			$user->password = Hash::make($password);
			$user->email = $company_email;
			$user->company_id = $company->id;
			$user->type = 60;
			$user->channel_id = $channel->id;
			$user->save();

			// Save user in channel

			$user_channel = new Users_in_channels;
			$user_channel->user_id = $user->id;
			$user_channel->channel_id = $channel->id;
			$user_channel->save();

			// Create subscription

			ChargeBee_Environment::configure("onestudio","live_wBXGhbEh4ZqG1tTdz4ZCP9T4aBSBWMdY");
//			ChargeBee_Environment::configure("onestudio-test","test_r3HdwSMA0Yrgcd4nIdZRwA6lDG4KLaU3B");
			$result = ChargeBee_Subscription::create(array(
				"planId" => $plan,
				"id" => $channel->id,
				"customer" => array(
					"id" => $user->id,
					"email" => $company_email,
					"firstName" => $first_name,
					"lastName" => $last_name,
					"autoCollection" => 'off',
					"phone" => $phone
				),
			));
			// Send email
			$data = array(
				'name' => $first_name,
				'email' => $company_email,
				'username' => $username,
				'channel' => $channel_name,
				'company' => $company_name,
			);
			$this->sendWelcomeEmail($data);

			return Redirect::to('login');
		}
		else{
			return Redirect::to('go')->withErrors($validator)->withInput(Input::except('password'));
		}
	}

	protected function sendWelcomeEmail($data){
		$auth = array('api_key' => '9bf06095cebd0e708ee09459df840d76a08f212d01166bd1');
		$smart_email_id = 'fdb62365-9f07-4aa1-b8c0-90550c9e5b19';
		$wrap = new CS_REST_Transactional_SmartEmail($smart_email_id, $auth);
		$message = array(
			"To" => 'Onestudio <'.$data['email'].'>',
			"Data" => array(
				'x-apple-data-detectors' => 'x-apple-data-detectorsTestValue',
				'href^="tel"' => 'href^="tel"TestValue',
				'href^="sms"' => 'href^="sms"TestValue',
				'owa' => 'owaTestValue',
				'role=section' => 'role=sectionTestValue',
				'style*="font-size:1px"' => 'style*="font-size:1px"TestValue',
				'account_name' => $data['name'],
				'account_email' => $data['username'],
				'profile_url' => 'http://1stud.io/login',
				'channel_name' => $data['channel'],
				'company_name' => $data['company'],
			),
		);
		$result = $wrap->send($message);
	}

}

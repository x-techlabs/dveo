<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Google
		 */
		'Google' => array(
			'client_id'     => '437156925416-7cirqfbedjoa815kkndsnoi5rjcua1rv.apps.googleusercontent.com',
			'client_secret' => 'u3Yo7JbwyAttazIYLfM2YB89',
			'redirect_signin' => 'https://1stud.io/login',
			'redirect_signup' => 'https://1stud.io/go',
			'scope'         => array('userinfo_email', 'userinfo_profile'),
		),

	)

);
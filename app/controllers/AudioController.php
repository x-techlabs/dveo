<?php
	class AudioController extends BaseController {


		public function index()
		{
			if(!Auth::user()->is(User::USER_MANAGE_MEDIA) || !in_array(BaseController::get_channel_id(), Auth::user()->checkChannelIds())) 
			{
            	return App::abort(404);
        	}
        	$channel_id = BaseController::get_channel_id();

			$this->data['channel_id'] = $channel_id;
			return $this->render('audio/index');
		}


	}

?>
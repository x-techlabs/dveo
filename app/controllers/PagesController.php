<?php

/**
 * Class PagesController
 */
class PagesController extends BaseController {

	public function stepstolaunch()
	{
		return View::make('pages/stepstolaunch');
	}


	public function calorosomedia()
	{
		return View::make('pages/calorosomedia');
	}

	public function appletv()
	{
		return View::make('pages/appletv');
	}

	public function categories()
	{
		return View::make('pages/categories');
	}

	public function arloopa()
	{
		if( Auth::user()->playout_access == 1){
			return App::abort(404);
		}
		return View::make('pages/arloopa');
	}
    public function grow()
    {
        return View::make('pages/grow');
    }

}


?>
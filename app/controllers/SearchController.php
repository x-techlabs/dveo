<?php
class SearchController extends BaseController {
    public function videoSearch() {
        $search_string = preg_replace("/[^A-Za-z0-9]/", " ", Input::get('query'));
        $channel_id = BaseController::get_channel_id();
        if(strlen($search_string) >= 1 && $search_string !== '') {
            $results = Video::where('title','like','%' . $search_string . '%')->where('channel_id', '=', $channel_id)->get();
            $results = Time::change_to_human_data_in_array($results);
        }
        $this->data['results'] = $results;

        return $this->render('searchResult');

    }

	public function search(){
		return $this->render('search');
	}
}
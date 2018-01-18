<?php

/**
 * Class PlaylistController
 */
class HelpController extends BaseController {

    public function help_get_subject_list()
    {
        $out = array('Select Help Topic', '0');
    	$sectionid = Input::get('sectionid');
        Log::info('sectionid = '.$sectionid);
        $helpTopics = Help::where('section', '=', $sectionid)->orderBy('display_order')->get();

        foreach($helpTopics as $topic)
        {
            $out[] = $topic->title;
            $out[] = $topic->id;
        }
        print implode(';', $out);
    }

    public function tvapp_help()
    {
    	$helpid = Input::get('helpid');
        $help = Help::where('id', '=', $helpid)->First();
        if (is_object($help))
        {
            $result = str_replace('HOMEURL', URL::to('/'), $help->description);
            print $result;
            exit();
        }
    }
}

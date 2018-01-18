<?php

use \App\Helpers\Playlists\TvappPlaylistHelper;
use \Illuminate\Support\Facades\Queue;
use \App\Jobs\Roku\UpdateXml;

/**
 * Class PlaylistController
 */
class TVAppController extends BaseController {

    /**
     * @var TvappPlaylistHelper
     */
    private $helper;

    public function __construct() {
        $this->helper = new TvappPlaylistHelper(BaseController::get_channel_id());
    }

    public function index() {
        if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
            return App::abort(404);
        }
        return $this->render('tvapp/tvapp_index');
    }
	public function update_xml_btn(){
        $channel_id = Input::get('channel_id');
        $tvappplaylist = TvappPlaylist::where('channel_id','=',$channel_id)->get();
        Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => $channel_id]);
        foreach($tvappplaylist  as $tapl):
          Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $tapl->id]);
		endforeach;

    }
    public function tvapp_live() {

        $xml = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

        $this->data['tvapp_title'] = $this->getSubstring($xml, 'title="', '"', 0);
        $this->data['tvapp_description'] = $this->getSubstring($xml, 'description="', '"', 0);
        $this->data['tvapp_live_stream_url'] = $this->getSubstring($xml, 'stream_url="', '"', 0);

        return $this->render('tvapp/tvapp_live');
    }

    // vinay
    public function tvapp_playlist_row($recordID, $contentID, $type, $title, $video_time, $depth)
    {
        $out  = "<div class='col-md-9 draggablePL droppablePL' data='$recordID' style='cursor:pointer;position:relative;margin-left:".(($depth+1)*30)."px;padding:0px !important;'>\n";
        $out .= "    <p class='videoPLtitle' style='margin:0;height:32px;' title='time: $video_time' data='$contentID' level='$depth' ";
        if ($type==1) $out .= " id='plRow_$contentID' onclick='ToggleChildrenList(this)'";
        $out .= ">\n";

        $out .= "        <input type='checkbox' name='ccb' value='$recordID'>&nbsp;\n";
        if ($type==0) $out .= "                <i class='fa fa-video-camera'></i>&nbsp;\n";
        //else $out .= "                <img id='folder_$contentID' src='/1studio/public/images/folder_close.png' style='width:28px;'>\n";
        else $out .= "                <img id='folder_$contentID' src='/images/folder_close.png' style='width:28px;'>\n";

        $out .= "        $title\n";
        $out .= "    </p>\n";
        $out .= "</div>\n";
        return $out;
    }

    public function tvapp_find_root_from_playlist($pid)
    {
        $node = TvappVideo_in_playlist::where('video_id', '=', $pid)
                                      ->where('type','=','1')->first();
        while (is_object($node))
        {
            $parent_id = $node->tvapp_playlist_id;
            //Log::info("tvapp_find_root_from_playlist => pid=".$pid.", parent_id=".$parent_id);
            if ($parent_id == 0) break;

            $pid = $parent_id;
            $node = TvappVideo_in_playlist::where('video_id', '=', $pid)
                                          ->where('type','=','1')->first();

        }
        return $pid;
    }

    public function tvapp_find_root_from_record($pid)
    {
        $node = TvappVideo_in_playlist::where('id','=',$pid)->first();
        return $this->tvapp_find_root_from_playlist($node->tvapp_playlist_id);
    }

    public function tvapp_playlist_tree_t1($video_playlist_relation, $depth)
    {
        $video = TvappPlaylist::find( $video_playlist_relation['video_id'] );
        if (!$video) return '';

        $out = '';
        $out .= $this->tvapp_playlist_row($video_playlist_relation['id'], $video_playlist_relation['video_id'], 1, $video->title, '', $depth);
        $out .= "<div id='childrenOf_".$video_playlist_relation['video_id']."' style='display:none;' class='col-md-12'>\n";
        $out .= $this->tvapp_playlist_tree($video_playlist_relation['video_id'], $depth+1);
        $out .= "</div>\n";
        return $out;
    }

    public function tvapp_playlist_tree($pid, $depth)
    {
        $video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $pid)->orderBy('sort_order')->get();

        $videos = '';
        $thumbnailSet = 0;

        foreach($video_ids as $video_playlist_relation) {
            if ($video_playlist_relation['type']==0)
            {
                $video = Video::find($video_playlist_relation['video_id']);

                if (!$video) continue;
                $video->time = Time::change_to_human_data_in_object($video);
                $htmlText = $this->tvapp_playlist_row($video_playlist_relation['id'], $video_playlist_relation['video_id'], 0, $video->title, $video->time, $depth);
                $videos .= $htmlText;
            }
            else if ($video_playlist_relation['type']==1)
            {
                $videos .= $this->tvapp_playlist_tree_t1($video_playlist_relation, $depth);
            }
        }
        return $videos;
    }

    public function tvapp_get_root_level_tree()
    {
        $playlists = TvappPlaylistHelper::get_tvapp_playlists(BaseController::get_channel_id());
        $video_ids = $this->helper->tvapp_get_root_level_records($playlists);

        $out = '';
        foreach($video_ids as $r)
        {
            $out .= "<li style='list-style-type: none'><div class='lists'><section><div class='row center-block'>\n";
            $out .= "<div class='col-md-10 playlist_thumb' style='margin:5px 0 5px 0'>\n";
            $out .= $this->tvapp_playlist_tree_t1($r, 0);
            $out .= "</div></div></section></div></li>\n";
        }
        return $out;
    }

    //==========================================================================
    //==========================================================================
    //==========================================================================
    //==========================================================================

    function UStream_IsNextPage($xml)
    {
        $paging = explode("\n", $xml);
        //print_r($paging);

        for ($i = 0 ; $i < count($paging) ; $i++)
        {
            $pos1 = strpos($paging[$i], "<next>");
            if ($pos1 !== false) return 1;
        }
        return 0;
    }

    function UStream_AdjustItems($in, & $from)
    {
        $pos = strpos($in, "<videos>\n");
        $in = substr($in, $pos+9);

        $pos = strpos($in, "</videos>");
        $in = substr($in, 0, $pos);

        for ($i = 0 ; $i < 50 ; $i++)
        {
            $search4 = 'array key="'.$i.'"';
            $replaceWith = 'array key="'.$from.'"';
            $in = str_replace($search4, $replaceWith, $in);
            $from++;
        }
        return $in;
    }

    //==========================================================================
    //==========================================================================
    //==========================================================================

    // vinay added for new functionality
    public function tvapp_insert_video_in_playlist_new() {

        $playlist = Input::get('playlist');
        /*
            $playlist['playlist_id'] = playlist_id under which these ids to be inserted
            $playlist['contents'] = array( [content_id, content_type] )
        */

        //Log::info("tvapp_insert_video_in_playlist_new / playlist / ".print_r($playlist, true));

        if (is_array($playlist)) {

            $insert = array();

            $duration = 0;
            $thumbnail_name = '';
            if(isset($playlist['contents']))
            {
                $lastRec = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $playlist['playlist_id'])->orderBy('sort_order', 'desc')->first();
                $lastID = (is_object($lastRec)) ? $lastRec->sort_order + 2 : 1;
                //Log::info("last sort order => ". $lastID);

                foreach($playlist['contents'] as $content)
                {
                    // make sure this is not duplicate entry
                    $records = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $playlist['playlist_id'])->where('video_id', '=', $content[0])->where('type', '=', $content[1])->get();
                    if (count($records) > 0) continue;

                    $insert[] = array('tvapp_playlist_id' => $playlist['playlist_id'], 'video_id' => $content[0], 'type' => $content[1], 'sort_order' => $lastID);
                    $lastID++;
                }
            }
            //Log::info("tvapp_insert_video_in_playlist_new / insert / ".print_r($insert, true));

            if (count($insert) > 0)
            {
                TvappVideo_in_playlist::Insert($insert);
            }
        }

        Queue::push(\App\Jobs\Roku\UpdateXml::UPDATE_PLAYLIST, ['id' => $playlist['playlist_id']]);
        $pid = $this->tvapp_find_root_from_playlist($playlist['playlist_id']);

        //Log::info("tvapp_insert_video_in_playlist_new => ".$pid);

        if ($pid==0) print "0~~".$this->tvapp_get_root_level_tree();
        else print $pid.'~~'.$this->tvapp_playlist_tree($pid, 1);
    }

    //==========================================================================
    //==========================================================================
    //==========================================================================

    public function tvapp_remove_from_playlists_get_children($id) {
        $vpls = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)->get();
        $idArray = array();
        foreach($vpls as $content) {
            $idArray[] = $content->id;
            if ($content->type==1)
            {
                $cid = $this->tvapp_remove_from_playlists_get_children($content->video_id);
                $idArray = array_merge($idArray, $cid);
            }
        }
        return $idArray;
    }

    public function tvapp_remove_from_playlists() {
        $contents = Input::get('contents');

        $finalArray = array();
        $modifiedPlaylists = array();

        $topid = 0;
        foreach($contents as $c)
        {
            // Make sure we delete nodes from one top parent only
            $tpid = $this->tvapp_find_root_from_record($c);
            if ($topid==0) $topid = $tpid;
            if ($topid != $tpid) continue;

            $finalArray[] = $c;
            $tvappl = TvappVideo_in_playlist::find($c);

            $pos = array_search($tvappl->tvapp_playlist_id, $modifiedPlaylists);

            // If this is a playlist itself, get all children
            if ($tvappl->type==1)
            {
                $cid = $this->tvapp_remove_from_playlists_get_children($tvappl->video_id);
                $finalArray = array_merge($finalArray, $cid);

                if ($pos !== false) array_splice($modifiedPlaylists, $pos, 1);
            }
            else
            {
                if ($pos === false) $modifiedPlaylists[] = $tvappl->tvapp_playlist_id;
            }
        }

        foreach($finalArray as $k) TvappVideo_in_playlist::where('id', '=', $k)->delete();

        foreach($modifiedPlaylists as $k) {
            Queue::push(\App\Jobs\Roku\UpdateXml::UPDATE_PLAYLIST, ['id' => $k]);
        };

        if ($topid==0) print "0~~".$this->tvapp_get_root_level_tree();
        else print $topid.'~~'.$this->tvapp_playlist_tree($topid, 1);
    }

    //==========================================================================
    //==========================================================================
    //==========================================================================
    public function tvapp_sort_order_change() {
        $sourceID = Input::get('sourceID');
        $targetID = Input::get('targetID');
        //Log::info("tvapp_sort_order_change => ".$sourceID.', '.$targetID);

        $pid = $this->tvapp_find_root_from_record($sourceID);

        if ($sourceID != $targetID)
        {
            // make sure both belong to same group
            $tvappS = TvappVideo_in_playlist::find($sourceID);
            $tvappT = TvappVideo_in_playlist::find($targetID);
            if ($tvappS->tvapp_playlist_id == $tvappT->tvapp_playlist_id)
            {
                if ($tvappS->tvapp_playlist_id==0) $rootRecords = $this->helper->tvapp_get_root_level_records( TvappPlaylistHelper::get_tvapp_playlists(BaseController::get_channel_id()), 'ids' );

                //Log::info("tvapp_sort_order_change => ".$tvappS->sort_order.' <=> '.$tvappT->sort_order);
                if ($tvappS->sort_order > $tvappT->sort_order)
                {
                    $sql = "update tvapp_video_in_playlist set sort_order=sort_order+1 where tvapp_playlist_id=".$tvappS->tvapp_playlist_id." and sort_order >= ".$tvappT->sort_order;
                    if ($tvappS->tvapp_playlist_id==0) $sql .= " and id IN(".implode(',',$rootRecords).")";
                    //Log::info("tvapp_sort_order_change => ".$sql);
                    DB::update($sql);

                    $sql = "update tvapp_video_in_playlist set sort_order=".$tvappT->sort_order." where id=".$sourceID;
                    //Log::info("tvapp_sort_order_change => ".$sql);
                    DB::update($sql);
                }
                else if ($tvappS->sort_order < $tvappT->sort_order)
                {
                    $sql = "update tvapp_video_in_playlist set sort_order=sort_order-1 where tvapp_playlist_id=".$tvappS->tvapp_playlist_id." and sort_order <= ".$tvappT->sort_order;
                    if ($tvappS->tvapp_playlist_id==0) $sql .= " and id IN(".implode(',',$rootRecords).")";
                    //Log::info("tvapp_sort_order_change => ".$sql);
                    DB::update($sql);

                    $sql = "update tvapp_video_in_playlist set sort_order=".$tvappT->sort_order." where id=".$sourceID;
                    //Log::info("tvapp_sort_order_change => ".$sql);
                    DB::update($sql);
                }
            }
            Queue::push(\App\Jobs\Roku\UpdateXml::UPDATE_PLAYLIST, ['id' => $tvappS->tvapp_playlist_id]);
        }

        //Log::info("tvapp_sort_order_change => pid => ".$pid);
        if ($pid==0) print "0~~".$this->tvapp_get_root_level_tree();
        else print $pid.'~~'.$this->tvapp_playlist_tree($pid, 1);
    }

    //==========================================================================
    //==========================================================================
    //==========================================================================
    public function HelpArray() {
        $helpItems = Help::where('section', '=', 'TVAPP')->orderBy('display_order')->get();
        $helpArray = array();
        foreach($helpItems as $help)
        {
            $helpArray[] = array('id' => $help->id, 'title' => $help->title);
        }
        return $helpArray;
    }
	public function tvapp_playlists_preview(){
        if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
            return App::abort(404);
        }
        $c = Channel::find(BaseController::get_channel_id());
        $playlists = TvappPlaylistHelper::get_tvapp_playlists(BaseController::get_channel_id());
        $video_ids = $this->helper->tvapp_get_root_level_records($playlists);
        $out = '';
         foreach($video_ids as $r)
        {
            $out .= "<li style='list-style-type: none'><div class='lists'><section><div class='row center-block'>\n";
            $out .= "<div class='col-md-10 playlist_thumb' style='margin:5px 0 5px 0'>\n";
            $out .= $this->tvapp_playlist_tree_t1($r, 0);
            $out .= "</div></div></section></div></li>\n";
        }
         $this->data['tvapp_playlists'] = $playlists;
        $this->data['tree'] = $out;
        $this->data['channelId'] = $c->id;
        $this->data['channelInfo'] = $c->title;
        $this->data['help'] = $this->HelpArray();
        return $this->render('tvapp/tvapp_playlists_preview');
    }
	
    public function tvapp_playlists() {
        if(!Auth::user()->is(User::USER_MANAGE_MEDIA)) {
            return App::abort(404);
        }
		if( Auth::user()->playout_access == 1){
			return App::abort(404);
		}

        $c = Channel::find(BaseController::get_channel_id());
        $playlists = TvappPlaylistHelper::get_tvapp_playlists(BaseController::get_channel_id());
        $video_ids = $this->helper->tvapp_get_root_level_records($playlists);


//        exit();

        $out = '';
        foreach($video_ids as $r)
        {
            $out .= "<li style='list-style-type: none'><div class='lists'><section><div class='row center-block'>\n";
            $out .= "<div class='col-md-10 playlist_thumb' style='margin:5px 0 5px 0'>\n";
            $out .= $this->tvapp_playlist_tree_t1($r, 0);
            $out .= "</div></div></section></div></li>\n";
        }

        $this->data['tvapp_playlists'] = $playlists;
        $this->data['tree'] = $out;
        $this->data['channelId'] = $c->id;
        $this->data['channelInfo'] = $c->title;
        $this->data['help'] = $this->HelpArray();

        return $this->render('tvapp/tvapp_manage_videos');
    }

    //==========================================================================

    public function tvapp_about_us() {

        $xml = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

        $intend = strpos($xml, 'title="About Us"');
        $this->data['tvapp_about_us'] = $this->getSubstring($xml, 'description="', '"', $intend);

        return $this->render('tvapp/tvapp_about_us');
    }

    ///////////////////////

    public function tvapp_live_update() {

        $title = trim(Input::get('tvapp_title'));
        $description = trim(Input::get('tvapp_description'));
        $liveStreamURL = trim(Input::get('tvapp_live_stream_url'));



        $title = str_replace('&','and',$title);
        $title = str_replace('"','',$title);
        $title = str_replace("'","",$title);
        $title = str_replace('\'','',$title);

        $description = str_replace('&','and',$description);
        $description = str_replace('"','',$description);
        $description = str_replace('\'','',$description);


        //&lt; represents "<"
        //&gt; represents ">"
        //&amp; represents "&"
        //&apos; represents '
        //&quot; represents "
        $liveStreamURL = str_replace('&','&amp;',$liveStreamURL);
        //$liveStreamURL = str_replace('&','&#038;',$liveStreamURL);



        $xml = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

        //xml($string)
        $xml = $this->injectStr($xml, 'title="', '"', $title, 0);
        $xml = $this->injectStr($xml, 'description="', '"', $description, 0);
        $xml = $this->injectStr($xml, 'stream_url="', '"', $liveStreamURL, 0);

        $path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';

        File::put($path.'/categories_linear.xml', $xml);

        //////////////////////

        $xml2 = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/first.xml');

        //xml($string)
        $xml2 = $this->injectStr($xml2, 'title="', '"', $title, 0);
        $xml2 = $this->injectStr($xml2, 'description="', '"', $description, 0);
        $xml2 = $this->injectStr($xml2, 'stream_url="', '"', $liveStreamURL, 0);

        $path2 = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';

        File::put($path2.'/first.xml', $xml2);


        $this->data['tvapp_title'] = $title;
        $this->data['tvapp_description'] = $description;
        $this->data['tvapp_live_stream_url'] = $liveStreamURL;

        $channel_id = BaseController::get_channel_id();



        $liveInfo = Live_info::where('channel_id', '=', $channel_id)->get();
        if(count($liveInfo)==0){
            $liveInfo = new Live_info;
            $liveInfo->channel_id = BaseController::get_channel_id();
            $liveInfo->title = $title;
            $liveInfo->description = $description;
            $liveInfo->live_url = $liveStreamURL;
            $liveInfo->details= '';
            $liveInfo->save();
        }else{
            $status = Live_info::where('channel_id', '=', $channel_id)->update(array(
                    'title'=>$title,
                    'description'=>$description,
                    'live_url'=>$liveStreamURL,
                    'details'=>''
            ));
        }


        return $this->render('tvapp/tvapp_live');
    }



    //temp duration fixer for 00:06 duration

    function getDuration($file){
        //Log::info('file: '.$file);
//      if (file_exists($file)){

            try{
            ## open and read video file
            $handle = fopen($file, "r");
            ## read video file size
            ini_set('memory_limit', '-1');
            $head = array_change_key_case(get_headers($file, TRUE));
            $filesize = $head['content-length'];
            //Log::info('filesize: '.$filesize);

            // 1156379530 - is file size of http://35.1studio.tv.global.prod.fastly.net/928d5e7484efeb3f86800199a0c0309b.mp4
            $duration =  $filesize*60/1156379530;
            //Log::info('duration : '.$duration);

            return [$filesize, $duration];
            //return $duration;
            } catch (Exception $e) {
                //echo 'Caught exception: ',  $e->getMessage(), "\n";
                Log::info('getDuration Exeption : '.$e->getMessage());
            }

            return [0, 0];

            //$contents = fread($handle, filesize($file));
            $contents = fread($handle, $filesize);
            //Log::info('contents: '.$contents);
            fclose($handle);
            $make_hexa = hexdec(bin2hex(substr($contents,strlen($contents)-3)));
            if (strlen($contents) > $make_hexa){
                $pre_duration = hexdec(bin2hex(substr($contents,strlen($contents)-$make_hexa,3))) ;
                $post_duration = $pre_duration/1000;
                $timehours = $post_duration/3600;
                $timeminutes =($post_duration % 3600)/60;
                $timeseconds = ($post_duration % 3600) % 60;
                $timehours = explode(".", $timehours);
                $timeminutes = explode(".", $timeminutes);
                $timeseconds = explode(".", $timeseconds);
                $duration = $timehours[0]. ":" . $timeminutes[0]. ":" . $timeseconds[0];

                return $duration;
            }

//      }
//      else {
//          return false;
//      }
    }

    function replace_between($str, $needle_start, $needle_end, $replacement) {
        $pos = strpos($str, $needle_start);
        $start = $pos === false ? 0 : $pos + strlen($needle_start);

        $pos = strpos($str, $needle_end, $start);
        $end = $pos === false ? strlen($str) : $pos;

        return substr_replace($str, $replacement, $start, $end - $start);
    }

    function fix6duration(){
        $videos = Video::where('duration', '<', '6')->get();

        foreach($videos as $video) {
            //if($video['f']);
            //$fname = 'https://s3.amazonaws.com/aceplayout/'.$video->file_name.'.mp4';
            //http://aceplayout.s3.amazonaws.com/b4f465283943dcb11a6493da05ab500f.mp4
            $fname = 'http://'.BaseController::get_channel_id().'.1studio.tv.global.prod.fastly.net/'.$video->file_name.'.mp4';

            $r = $this->getDuration($fname);
            //Log::info('id: '.$video->id);
            if($r[0] > 0 && $r[1]>0){
                $storage = $video->storage;
                //Log::info('storage: '.$storage);
                $storageKB = round($r[0]/1000);
                //$video->storage = str_replace('1785', $storageKB, $storage); //   2016/08/15 - 1785kb, 1280X720, video/mp4, h264
                //$video->storage = str_replace('1280', $storageKB, $storage);

                $storage = $this->replace_between($storage, ' - ', 'kb, ', $storageKB);
                //Log::info('storageX: '.$storage);

                $video->hd_file_size = $r[0];
                $video->duration = round($r[1]);
                //Log::info('filesize: '.$video->hd_file_size.', duration: '.$video->duration);
                $video->update();

            }



            //return;
        }
    }

    //end of temp duration fixer for 00:06 duration


    public function tvapp_about_us_update() {

        $this->fix6duration(); //was temp call to fix 6 min durations

        $tvapp_about_us = trim(Input::get('about_us'));

        $tvapp_about_us = str_replace('&','and',$tvapp_about_us);
        $tvapp_about_us = str_replace('"','',$tvapp_about_us);
        $tvapp_about_us = str_replace('\'','',$tvapp_about_us);

        $xml = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

        $intend = strpos($xml, 'title="About Us"');
        $xml = $this->injectStr($xml, 'description="', '"', $tvapp_about_us, $intend);
        if(strlen($xml)>30){
            $path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';
            File::put($path.'/categories_linear.xml', $xml);
        }

        /////////////////

        $xml2 = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/first.xml');
        $intend2 = strpos($xml2, 'title="About Us"');
        $xml2 = $this->injectStr($xml2, 'description="', '"', $tvapp_about_us, $intend2);
        if(strlen($xml2)>30){
            $path2 = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';
            File::put($path2.'/first.xml', $xml2);
        }

        $this->data['tvapp_about_us'] = $tvapp_about_us;

        return $this->render('tvapp/tvapp_about_us');
    }


    //set tvapp playlists order using master_looped field (using master_looped in another context with tvapps)
    public function tvapp_order_playlist() {

        $count = 1;
        foreach($_POST['item'] as $tpid) {
            Log::error("Zencoder: {$tpid}");

            $status = TvappPlaylist::where('id', '=', $tpid)->update(array(
                    'master_looped'=> $count
            ));
            $count++;
        }

        return $this->render('tvapp/tvapp_manage_videos');
    }

    public function tvapp_add_to_playlist() {
        $web_layout = trim(Input::get('web_layout'));
        $viewing = trim(Input::get('viewing'));
        if ($viewing=='') $viewing = 'inherit';

        $platforms = Input::get('platforms');
        if(!is_array($platforms)) {
            $platforms = ['0'];
        }

        if(in_array('0', $platforms)) {
            $platforms = TvappPlatform::lists('id');
        }

        $title = trim(Input::get('title'));
        $description = trim(Input::get('description'));
        $type = trim(Input::get('type'));
        $level = trim(Input::get('level'));
        $shelf = trim(Input::get('shelf'));
        $layout = trim(Input::get('layout'));
        $stream_url = trim(Input::get('stream_url'));
        $playlist_category = trim(Input::get('playlist_category'));
	    $active_for_all_playlists = trim(Input::get('active_for_all_playlists'));



        if (empty($title)) {

            return Response::json([
                    'status' => false,
                    'message' => 'Wrong data'.$title
            ], 200);
            //            return View::make('error')->with(array('message' => 'Wrong data'));
        }

        $playlist = new TvappPlaylist;
        $playlist->title = $title;
        $playlist->description = $description;
        $playlist->type = $type;
        $playlist->level = $level;
        $playlist->shelf = $shelf;
        $playlist->layout = $layout;
        $playlist->web_layout = $web_layout;
        $playlist->viewing = $viewing;
        $playlist->stream_url = $stream_url;
        $playlist->playlist_category = $playlist_category;
        $playlist->channel_id = BaseController::get_channel_id(); // This must by changed




          

        if ($playlist->save()) {
            $latest_id = DB::getPdo()->lastInsertId();
            if( !empty($playlist_category) ) {
            
                $playlist_videos =  Videos_in_collections::get()->all();
                foreach($playlist_videos as $pv) {
                    if($pv['collection_id'] == $playlist_category){
                        $tvapp_video_in_playlist = new TvappVideo_in_playlist;
                        $tvapp_video_in_playlist->video_id = $pv['video_id'];
                        $tvapp_video_in_playlist->tvapp_playlist_id = $latest_id;
                        $tvapp_video_in_playlist->save();                    
                    }
                }
            }
            if($active_for_all_playlists =='yes'){
                 $playlist_videos =  Videos_in_collections::get()->all();
                foreach($playlist_videos as $pv) {
                        $tvapp_video_in_playlist = new TvappVideo_in_playlist;
                        $tvapp_video_in_playlist->video_id = $pv['video_id'];
                        $tvapp_video_in_playlist->tvapp_playlist_id = $latest_id;
                        $tvapp_video_in_playlist->save();                    
                }
            }
            $playlist->platforms()->sync($platforms);


            Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $playlist->id]);
            Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => BaseController::get_channel_id()]);
            return Response::json([
                    'status' => true,
                    'tvapp_playlist_id' => $playlist->id
            ], 200);
        }
    }

    public function file_exists_remote($in)
    {
        $handle = @fopen($in, 'r');
        if ($handle !== false)
        {
            fclose($handle);
            return 1;
        }
        return 0;
    }

    public function tvapp_edit_playlist() {
        $id = trim(Input::get('id'));
        $title = trim(Input::get('title'));
        $description = trim(Input::get('description'));
        $type = trim(Input::get('type'));
        $layout = trim(Input::get('layout'));
        $web_layout = trim(Input::get('web_layout'));
        $stream_url = trim(Input::get('stream_url'));
		$featured_image_url = trim(Input::get('featured_image_url'));
		

        $viewing = trim(Input::get('viewing'));
        if ($viewing=='') $viewing = 'inherit';

        $platforms = Input::get('platforms');
        if(!is_array($platforms)) {
            $platforms = ['0'];
        }

        if(in_array('0', $platforms)) {
            $platforms = TvappPlatform::lists('id');
        }

        if (empty($id) || empty($title)) {
            return Response::json([
                    'status' => false,
                    'message' => 'Wrong data'
            ], 200);
        }

        $playlist = TvappPlaylist::find($id);
        $playlist->title = $title;
        $playlist->description = $description;
        $playlist->type = $type;
        $playlist->layout = $layout;
        $playlist->web_layout = $web_layout;
        $playlist->viewing = $viewing;
        $playlist->stream_url = $stream_url;
        $playlist->featured_image_url = $featured_image_url;

        // save Postername if it exists
        $postername = 'https://prolivestream.imgix.net/logos-poster/channel_'.BaseController::get_channel_id().'_'.'tvapp_playlist_'.$id.".jpg";
        //Log::info("tvapp_edit_playlist checking  logo => ".$postername."\nResult is :".$this->file_exists_remote($postername));
        if ($this->file_exists_remote($postername)) $playlist->thumbnail_name = $postername;

        //        $playlist->status = 0;
        $playlist->channel_id = BaseController::get_channel_id();
        $playlist->save();

        $playlist->platforms()->sync($platforms);

        // Find this playlist entry in tvapp_video_in_playlist
        $nodes = TvappVideo_in_playlist::where('video_id', '=', $id)
                                      ->where('type','=','1')->get();

        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $playlist->id]);
        Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => BaseController::get_channel_id()]);
        return Response::json([ 'status' => true, 'playlist_id' => $playlist->id ], 200);

        /*
        /// update categories.xml



        /// update categories.xml

        $xmlv = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories.xml');

        //xml($string)
        $x1 = strpos($xmlv, '<categories>');
        $x2 = strpos($xmlv, '</categories>');

        $xmlv1 = substr($xmlv, 0, $x1+strlen('<categories>'))."\n\n";
        $xmlv2 = "\n".substr($xmlv, $x2, strlen($xmlv)-$x2);

        $xmlv = '';

        //$playlists = TvappPlaylist::all();
        $playlists = self::get_tvapp_playlists();


        $count = 0;
        foreach($playlists as $pl) {
            ++$count;
        }

        $channel_id = BaseController::get_channel_id();
        foreach($playlists as $pl) {
            $tlt = $this->helper->xtrim($pl->title);

            $plimg = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'_'.'tvapp_playlist_'.$pl->id;

            $ttl = $this->helper->Q($pl->title);
            $desc = $this->helper->Q($pl->description);

            $xmlv .=
            '<category '."\n".
            'title="'.$ttl.'" '."\n".
            'description="'.$desc.'" '."\n".

            'sd_img="'.$plimg.'" '."\n".
            'hd_img="'.$plimg.'" '."\n".
            'feed="http://1stud.io/tvapp/channel_'.$channel_id.'/roku/xml/playlists/'.$tlt.'.xml" '."\n".
            'playlists_count="'.$count.'" '."\n".
            '/>'."\n";
        }

        $xmlv = $xmlv1.$xmlv.$xmlv2;

        $path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';

        File::put($path.'/categories.xml', $xmlv);







        /// update categories_linear.xml

        $xmlv = File::get(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/categories_linear.xml');

        //xml($string)
        $x1 = strpos($xmlv, '<!-- content start -->');
        $x2 = strpos($xmlv, '<!-- content end -->');

        $xmlv1 = substr($xmlv, 0, $x1+strlen('<!-- content start -->'))."\n\n";
        $xmlv2 = "\n".substr($xmlv, $x2, strlen($xmlv)-$x2);

        $xmlv = '';

        //$playlists = TvappPlaylist::all();
        $playlists = self::get_tvapp_playlists();


        $count = 0;
        foreach($playlists as $pl) {
            ++$count;
        }

        $channel_id = BaseController::get_channel_id();
        foreach($playlists as $pl) {
            $tlt = $this->helper->xtrim($pl->title);

            $plimg = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id.'_'.'tvapp_playlist_'.$pl->id;

            $ttl = $this->helper->Q($pl->title);
            $desc = $this->helper->Q($pl->description);

            $xmlv .=
            '<category '."\n".
            'title="'.$ttl.'" '."\n".
            'description="'.$desc.'" '."\n".

            'sd_img="'.$plimg.'" '."\n".
            'hd_img="'.$plimg.'" '."\n".
            'feed="http://1stud.io/tvapp/channel_'.$channel_id.'/roku/xml/playlists/'.$tlt.'.xml" '."\n".
            'playlists_count="'.$count.'" '."\n".
            '/>'."\n";
        }

        $xmlv = $xmlv1.$xmlv.$xmlv2;

        $path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml';

        File::put($path.'/categories_linear.xml', $xmlv);







        ///update tvapp xml files

        $video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)->get();

        $xmlv =
        '<?xml version="1.0" encoding="UTF-8"?>'."\n".
        '<feed>'."\n";
        //'<resultLength>1</resultLength>'."\n".
        //'<endIndex>1</endIndex>'."\n";

        foreach($video_ids as $video_id) {
            $video = Video::find($video_id['video_id']);

            //if (empty($video)) continue;
            //if (!$video) continue;

//          $videos = Video::where('id', '=', $video_id)->get();
//          foreach ($videos as $video) {
//              //here could be only one record
//              $channel_id = $video->channel_id;
//          }

            //$fname = 'https://s3.amazonaws.com/aceplayout/'.$video->file_name.'.mp4';
            $fname = 'http://'.BaseController::get_channel_id().'.1studio.tv.global.prod.fastly.net/'.$video->file_name.'.mp4';
            //http://35.1studio.tv.global.prod.fastly.net/0086a1fd4d43c5a01e501c785acaf147.mp4
            $ttl = str_replace("&","and",$video->title);
            $desc = str_replace("&","and",$video->description);

            //hd w=266&h=150
            //sd w=138&h=77

            //$sdimg = str_replace('https://s3.amazonaws.com/aceplayout/','https://onestudio.imgix.net/',$video->thumbnail_name);
            //$sdimg .= '?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60';
            //$sdimg .= '?w=138&h=77&fit=crop&crop=entropy&auto=format,enhance&q=40';
            //$sdimg = 'https://goo.gl/3CbmY2';

            //since 1stud db updated already with imgix
            //hd 385x218
            //sd 285x145

            $sdimg = $video->thumbnail_name;

            $hd_img = 'https://onestudio.imgix.net/'.$video->file_name.'_1.jpg?'.
                      'w=385&h=218&fit=crop&crop=entropy&auto=format,enhance&q=60';
            $sd_img = 'https://onestudio.imgix.net/'.$video->file_name.'_1.jpg?'.
                      'w=285&h=145&fit=crop&crop=entropy&auto=format,enhance&q=60';

            $hd_img = htmlspecialchars($hd_img, ENT_XML1, 'UTF-8');
            $sd_img = htmlspecialchars($sd_img, ENT_XML1, 'UTF-8');

            ///// alternative: http://dev.bitly.com/
            //https://www.youtube.com/watch?v=zMXsK6hMlbw
            //http://code.google.com/apis/console
            //google shortener key:AIzaSyC_ukHQPxEHSXKhJuf42NtJqwHAyOoMDRw

            //google shortener currently not used...
            //$hd_img = $this->get_short($hd_img);
            //$sd_img = $this->get_short($sd_img);

            $xmlv .=
            '<item sdImg="'.$hd_img.'" hdImg="'.$sd_img.'">'."\n".
            '<title>'.$ttl.'</title>'."\n".
            '<description>'.$desc.'</description>'."\n".
            '<contentType>Talk</contentType>'."\n".
            '<contentId>1</contentId>'."\n".
            '<media>'."\n".
            '<streamFormat>mp4</streamFormat>'."\n".
            '<streamQuality>SD</streamQuality>'."\n".
            '<streamUrl>'.$fname.'</streamUrl>'."\n".
            '</media>'."\n".
            '<media>'."\n".
            '<streamFormat>mp4</streamFormat>'."\n".
            '<streamQuality>HD</streamQuality>'."\n".
            '<streamUrl>'.$fname.'</streamUrl>'."\n".
            '</media>'."\n".
            '<synopsis>'.$desc.'</synopsis>'."\n".
            '<genres>Clip</genres>'."\n".
            '<runlength>'.$video->duration.'</runlength>'."\n".
            '<starrating>75</starrating>'."\n".
            '<Rating>NR</Rating>'."\n".
            '</item>'."\n\n";

        }

        $xmlv .= '</feed>'."\n";

        $path = public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/playlists';
        //$result = File::makeDirectory($path, 0777,true);

        $title = $this->helper->xtrim($title);

        File::put($path.'/'.$title.'.xml', $xmlv);

        //return Response::make($content, 200)->header('Content-Type', 'application/xml');

        ////////////////////////


        if ($playlist->save()) {
            return Response::json([
                    'status' => true,
                    'playlist_id' => $playlist->id
            ], 200);
        }
        */
    }

    public function tvapp_get_playlist_by_id() {
        $id = trim(Input::get('id'));
        $playlist = TvappPlaylist::find($id);
        $video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', (int) $playlist->id)->get();
        $videos = [];
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
        foreach ($video_ids as $video_id) {
            $video = Video::find($video_id['video_id']);
            if (!$video) continue;
            $video->time = Time::change_to_human_data_in_object($video);
            $videos[] = $video;
        }

        $channel_id = BaseController::get_channel_id();
        $channel = Channel::find($channel_id);

        $playlist->time = Time::change_to_human_data_in_object($playlist);
        $platforms = TvappPlatform::all();

         // Get api_url
        $channel = Channel::find(BaseController::get_channel_id());
        $api_url_exist = '0';
        $p = strpos($channel->storage, 'login_url|');
        if ($p > 0)
        {
            $api_url = substr($channel->storage, $p+10);
            $p = strpos($api_url, '|');
            if ($p > 0) $api_url_exist = '1'; 
        }


        return View::make('tvapp/tvapp_edit_playlist')->with([
            'playlist' => $playlist,
            'videos' => $videos,
            'api_url_exist' => $api_url_exist,
            'channel' => $channel,
            'platforms' => $platforms,
        ]);
    }

    public function tvapp_insert_video_in_playlist() {

        $playlist = Input::get('playlist');

        //var_dump($playlist); die();
        if (is_array($playlist)) {

            $insert = array();

            $duration = 0;

            $thumbnail_name = '';
            if(isset($playlist['playlists'])) {
                foreach($playlist['playlists'] as $video_id) {

                    $videos = Video::find((int)$video_id);

                    $duration += (int)$videos->duration;

                    $insert[] = array(
                            'tvapp_playlist_id' => $playlist['playlist_id'],
                            'video_id' => $video_id
                    );

                    $thumbnail_name = $videos->thumbnail_name;
                }
            } else {
                return Response::json([
                        'status' => true
                ], 200);
            }

            $playlist = TvappPlaylist::find($playlist['playlist_id']);
            $playlist->duration = $duration;
            $playlist->thumbnail_name = $thumbnail_name;

            $videos_in_playlists = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $playlist['id'])->get();

            foreach($videos_in_playlists as $videos_in_playlist) {
                $videos_in_playlist->delete();
            }

            TvappVideo_in_playlist::Insert($insert);
            $playlist->save();

            return Response::json([
                    'status' => true
            ], 200);
        } else {
            return Response::json([
                    'status' => false
            ], 200);
        }
    }

    public function tvapp_delete_playlist() {
        $tvapp_playlist_id = Input::get('tvapp_playlistId');
        $tvappPlat = TvappPlaylistPlatforms::where('tvapp_playlist_id', '=', $tvapp_playlist_id)->delete();
        $tvappl = TvappPlaylist::find($tvapp_playlist_id);
        TvappPlaylist::find($tvapp_playlist_id)->delete();
        //Schedule::where('tvapp_playlist_id', '=', $playlist_id)->delete();
        TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $tvapp_playlist_id)->delete();
        File::delete(public_path().'/tvapp/channel_'.BaseController::get_channel_id().'/roku/xml/playlists/'.$this->helper->xtrim($tvappl->title).'.xml');
        Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => BaseController::get_channel_id()]);
        return Redirect::back();
    }

    public function tvapp_duplicate_playlist() {
        $tfp_id = Input::get('tvapp_playlistId'); // tvapp_first_playlist
        $tfp_platform = TvappPlaylistPlatforms::where('tvapp_playlist_id', '=', $tfp_id)->get();
        $tfp = TvappPlaylist::where('id', '=', $tfp_id)->first();

        $tfp_childs = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $tfp_id)->get();

        $playlist = new TvappPlaylist;
        $playlist->title = $tfp->title;
        $playlist->description = $tfp->description;
        $playlist->type = $tfp->type;
        $playlist->level = $tfp->level;
        $playlist->shelf = $tfp->shelf;
        $playlist->layout = $tfp->layout;
        $playlist->sort_order = $tfp->sort_order+1;
        $playlist->web_layout = $tfp->web_layout;
        $playlist->viewing = $tfp->viewing;
        $playlist->stream_url = $tfp->stream_url;
        $playlist->channel_id = $tfp->channel_id;

        $platforms = array();
        foreach ($tfp_platform as $tfp_platfor) {
            $platforms[] = $tfp_platfor->tvapp_platform_id;
        }
        if ($playlist->save()) {
            $playlist->platforms()->sync($platforms);

            foreach ($tfp_childs as $child) {

                $insert = array();
                $insert['video_id'] = $child['video_id'];
                $insert['tvapp_playlist_id'] = $playlist->id;
                $insert['type'] = $child['type'];
                $insert['sort_order'] = $child['sort_order'];
                $insert['created_at'] = Date("Y-m-d h-i-s");
                $insert['updated_at'] = Date("Y-m-d h-i-s");

                TvappVideo_in_playlist::Insert($insert);
            }
            Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $playlist->id]);
            Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => BaseController::get_channel_id()]);
        }

        return Redirect::back();
    }

    //    public function master_loop() {
    //        $id = trim(Input::get('id'));
    //        $playlist = Playlist::find($id);
    //
    //        $videos_in_playlist = Video_in_playlist::where('playlist_id', '=', $id)->get();
    //
    //        $dveo = BaseController::get_dveo();
    //
    //        if($playlist->type != 2) {
    //            $allPlaylists = Playlist::where('channel_id', '=', BaseController::get_channel_id())->get();
    //
    //            foreach($allPlaylists as $one) {
    //                $one->master_looped = 0;
    //                $one->type = 0;
    //                $one->save();
    //            }
    //
    //            ###
    //            $playlistvideos = [];
    //            foreach ($videos_in_playlist as $video_in_playlist) {
    //                $video = Video::find($video_in_playlist['video_id']);
    //                $playlistvideos[] = $video['file_name'] . "." . $video['video_format'];
    //                //$playlistvideos[] .= time() . " " . $video['file_name'] . "." . $video['video_format'] . "\n";
    //            }
    //
    //            // Change playlist on DVEO
    //            //$dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());
    //
    //            // Creating a new DVEO instance
    //            //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');
    //            $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');
    //            $dveo->schedule_videos(BaseController::get_channel_id(),  $playlistvideos);
    //            ###
    //
    //            $dveo->restart_stream(BaseController::get_channel()->stream);
    //
    //            $playlist->master_looped = Carbon::now()->getTimestamp();
    //            $playlist->type = 2;
    //            $playlist->save();
    //
    //            return Response::json([
    //                'status' => true,
    //                'loopOff' => true
    //            ], 200);
    //        } else {
    //            $playlist->master_looped = 0;
    //            $playlist->type = 0;
    //            $playlist->save();
    //
    //            $dveo->stop_stream(BaseController::get_channel()->stream);
    //
    //            return Response::json([
    //                'status' => true,
    //                'loopOff' => false
    //            ], 200);
    //        }
    //    }

    //not used in TV
//     public function tvapp_update_files() {
//      $id = trim(Input::get('id'));
//      $playlist = TvappPlaylist::find($id);

//      $videos_in_playlist = TvappVideo_in_playlist::where('playlist_id', '=', $id)->get();

//      $dveo = BaseController::get_dveo();

//      if($playlist->type != 2) {
//          $allPlaylists = TvappPlaylist::where('channel_id', '=', BaseController::get_channel_id())->get();

//          foreach($allPlaylists as $one) {
//              $one->master_looped = 0;
//              $one->type = 0;
//              $one->save();
//          }

//          // Change playlist on DVEO
//          $dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());
//          $dveo->restart_stream(BaseController::get_channel()->stream);

//          $playlist->master_looped = Carbon::now()->getTimestamp();
//          $playlist->type = 2;
//          $playlist->save();

//          return Response::json([
//                  'status' => true,
//                  'loopOff' => true
//          ], 200);
//      } else {
//          $playlist->master_looped = 0;
//          $playlist->type = 0;
//          $playlist->save();

//          $dveo->stop_stream(BaseController::get_channel()->stream);

//          return Response::json([
//                  'status' => true,
//                  'loopOff' => false
//          ], 200);
//      }
//     }

    /// ROUTINES:

    private function injectStr($str, $token1, $token2, $target, $intend){
        $x1_len = strlen($token1);
        $x1 = strpos($str, $token1, $intend);
        if($x1>0){
            $x2 = strpos($str, $token2, $x1+$x1_len);
            if($x2>$x1){
                $xml1 = substr($str, 0, $x1+$x1_len);
                $xml2 = substr($str, $x2);
                $str = $xml1.$target.$xml2;
            }
        }
        return $str;
    }

    private function getSubstring($str, $token1, $token2, $intend){
        $x1_len = strlen($token1);
        $x1 = strpos($str, $token1, $intend);
        if($x1>0){
            $x2 = strpos($str, $token2, $x1+$x1_len);
            if($x2>$x1){
                $str = substr($str, $x1+$x1_len, $x2-($x1+$x1_len));
            }
        }
        return $str;
    }


    ///REST UTILS

    public function XMLFiletoJSONparse ($url) {
        $fileContents= file_get_contents($url);
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));

        return new SimpleXMLElement($fileContents);

        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);
        //$array = json_decode($json,TRUE);
        return $json;
    }

    public function XMLtoJSONparse ($fileContents) {
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));

        return new SimpleXMLElement($fileContents);

    }

    ///TVAPP REST

    public function tvapp_get_categories() {
        $channel_id = '38';
        //$channel_id = trim(Input::get('channel_id')); // to be implemented
        $url = public_path().'/tvapp/channel_'.$channel_id.'/roku/xml/categories.xml';

        $json = $this->XMLFiletoJSONparse($url);

        return Response::json($json, 200);

        return Response::json([
            'status' => true,
            'playlist_id' => 'ok'
        ], 200);
    }




    ///TVAPP REST FOR VIDEO WEBSITE
    public function tvapp_get_section() {
        $key = trim(Input::get('key'));
        $channel_id = trim(Input::get('channel_id'));
        $section_title = trim(Input::get('section_title'));

        //key to check: sdf88po234pl3zxPP9

        //if($section_title == 'Watch Live')
        //$playlists = TvwebPlaylist::where('channel_id', '=', $channel_id)->
        //                               where('title', '=', $section_title)->get();

//      $liveInfo = new Live_info;
//      $liveInfo->channel_id = BaseController::get_channel_id();
//      $liveInfo->title = $title;
//      $liveInfo->description = $description;
//      $liveInfo->live_url = $liveStreamURL;
//      $liveInfo->details= '';
//      $liveInfo->save();

        $playlists = DB::table('live_info')->where('channel_id', '=', $channel_id)
        //->where('title', '=', $section_title)
        ->get();

        $json = json_encode($playlists[0]);
        return Response::json($json, 200);
    }


    public function tvapp_get_playlists() {
        $key = trim(Input::get('api_key'));
        $channel_id = trim(Input::get('channel_id'));

        //key to check: sdf88po234pl3zxPP9

        $playlists = DB::table('tvapp_playlist')->where('channel_id', '=', $channel_id)
        //->where('type', '=', '0')
        ->get();

        $json = json_encode($playlists);
        return Response::json($json, 200);
    }

    public function tvapp_get_videos() {
        $key = trim(Input::get('api_key'));
        $channel_id = trim(Input::get('channel_id'));
        $playlist_title = trim(Input::get('playlist_title'));
        $tvapp_playlist_id = trim(Input::get('tvapp_playlist_id'));

        $playlists = DB::table('tvapp_playlist')->where('channel_id', '=', $channel_id)
        //->where('title', '=', $playlist_title)
        ->where('id', '=', $tvapp_playlist_id)
        ->get();

        $ar = array();

        foreach($playlists as $pl) {
            $id = $pl->id;
            $ttl = $pl->title;
            $desc = $pl->description;

            $video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)->get();

            foreach($video_ids as $video_id) {
                $video = Video::find($video_id['video_id']);


                if (!$video) continue;

                $fname = 'http://'.$channel_id.'.1studio.tv.global.prod.fastly.net/'.$video->file_name.'.mp4';
                $ttl = $video->title;
                $desc = $video->description;

                //$sdimg = str_replace('https://s3.amazonaws.com/aceplayout/','https://onestudio.imgix.net/',$video->thumbnail_name);
                //$sdimg .= '?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60';
                //$sdimg .= '?w=640&h=360&fit=crop&crop=entropy&auto=format,enhance&q=60';
                //$sdimg .= '?w=670&h=406&fit=crop&crop=entropy&auto=format,enhance&q=60';

                //since 1stud db updated already with imgix
                $sdimg = $video->thumbnail_name;

                $sdimg = htmlspecialchars($sdimg, ENT_XML1, 'UTF-8');

                //$sdimg = $json->id;
                /////

                $ar[] = array($playlist_title => array('tvapp_playlist_id'=>$tvapp_playlist_id,
                        'title'=>$ttl,
                        'description'=>$desc,
                        'url'=>$fname,
                        'duration'=>$video->duration,
                        'img'=>$sdimg,
                ));

            }

        }
        $json = json_encode($ar);
        //Log::info();

        return Response::json($json, 200);
    }


    function array_push_assoc1($array, $key, $value){
        $array[$key] = $value;
        return $array;
    }

    function array_push_assoc2($array, $key, $value){
        $array->$key = $value;
        return $array;
    }

    public function tvapp_get_playlists_with_videos() {
        $key = trim(Input::get('api_key'));
        $channel_id = trim(Input::get('channel_id'));
        //key to check: sdf88po234pl3zxPP9

        $playlists = DB::table('tvapp_playlist')->where('channel_id', '=', $channel_id)
        //->where('type', '=', '0')
        ->get();

        $playlists_with_videos_arr = array();

        foreach($playlists as $pl) {
            $id = $pl->id;
            $ttl = $pl->title;
            $desc = $pl->description;

            $video_ids = TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)->get();

            $videos_arr = array();
            foreach($video_ids as $video_id) {
                $video = Video::find($video_id['video_id']);


                if (!$video) continue;

                $fname = 'http://'.$channel_id.'.1studio.tv.global.prod.fastly.net/'.$video->file_name.'.mp4';
                $ttl = $video->title;
                $desc = $video->description;

                //$sdimg = str_replace('https://s3.amazonaws.com/aceplayout/','https://onestudio.imgix.net/',$video->thumbnail_name);
                //$sdimg .= '?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60';
                //$sdimg .= '?w=640&h=360&fit=crop&crop=entropy&auto=format,enhance&q=60';
                //$sdimg .= '?w=670&h=406&fit=crop&crop=entropy&auto=format,enhance&q=60';

                //since 1stud db updated already with imgix
                $sdimg = $video->thumbnail_name;

                $sdimg = htmlspecialchars($sdimg, ENT_XML1, 'UTF-8');

                $videos_arr[] = array('tvapp_playlist_id'=>$pl->id,
                        'title'=>$ttl,
                        'description'=>$desc,
                        'url'=>$fname,
                        'duration'=>$video->duration,
                        'img'=>$sdimg);
            }

            //insert videos into playlist array
            $pl_arr = $this->array_push_assoc2($pl, 'videos', $videos_arr);

            $playlists_with_videos_arr[] = array('playlist'=>$pl_arr);

        }

        $json = json_encode($playlists_with_videos_arr);
        return Response::json($json, 200);
    }

    public function tvapp_get_channel_live_url() {
        $channel_id = trim(Input::get('channel_id'));

        $liveInfo = Live_info::where('channel_id', '=', $channel_id)->get();

        $json = json_encode($liveInfo);
        //Log::info($json);
        return Response::json($json, 200);

    }

    public function get_short($sdimg){
        $longUrl = $sdimg."&t=".time();
        $apiKey = 'AIzaSyC_ukHQPxEHSXKhJuf42NtJqwHAyOoMDRw';

        // *** No need to modify any of the code line below. ***
        $postData = array('longUrl' => $longUrl, 'key' => $apiKey);
        $jsonData = json_encode($postData);
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
        $response = curl_exec($curlObj);
        $json = json_decode($response);

        curl_close($curlObj);

        //Log::info($response);
        //echo 'Shortened URL ->'.$json->id;
        return $json;
    }

}



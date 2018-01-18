<?php namespace App\Controllers\Api;

use App\Helpers\Playlists\MRssPlaylistParser;
use BaseController as Controller;
use App\Helpers\Playlists\TvappPlaylistHelper;
use App\Jobs\Roku\UpdateXml;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Response;

class TvappPlaylistsController extends Controller {

    /**
     * @var TvappPlaylistHelper
     */
    private $helper;

    public function __construct() {
    }

    /**
     * Display a listing of $COLLECTION$
     *
     * @return Response | JsonResponse
     */
    public function index($channel_id)
    {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
        $query = \TvappPlaylist::with([
                'videos',
            ])
            ->where("channel_id", "=", $channel_id)
            ->orderBy('title', 'asc');

        if(Input::has('platform')) {
            $platform = \TvappPlatform::where('slug', Input::get('platform'))->first();
            if(is_null($platform)) {
                return Response::json(['error' => 'Invalid platform'], 404);
            }

            $query->whereHas('platforms', function ($platformQuery) use ($platform) {
                return $platformQuery->where('tvapp_platforms.id', '=', $platform->id);
            });
        }

        $playlists = $query->get();

        if (Input::get('with_mrss_videos') == 1) {
            $playlists = MRssPlaylistParser::playlistsToArrayWithMRssChildren($playlists,$channel_id);
        }

        return \Time::change_to_human_data_in_array($playlists);
    }

    /**
     * Display a listing of $COLLECTION$
     *
     * @return Response | JsonResponse
     */
    public function add_playlist($channel_id, $title, $shelf, $sort_order, $video_id)
    {
        $id = \TvappPlaylist::insertGetId(
            array(
                'channel_id'=>$channel_id,
                'title'=>$title,
                'shelf'=>$shelf,
                'video_is'=>1,
                'sort_order'=>$sort_order
            )
        );

        if( \TvappVideo_in_playlist::where("video_id", "=", $video_id)->first() ) {
            $tvapp_playlist_id = \TvappVideo_in_playlist::where("video_id", "=", $video_id)->first()->tvapp_playlist_id;
			Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $tvapp_playlist_id]);
        }
        \TvappVideo_in_playlist::where("video_id", "=", $video_id)->delete();
        \TvappVideo_in_playlist::insert(array(
                'video_id'=>$video_id,
                'tvapp_playlist_id'=>$id,
                'sort_order'=>1,
                'created_at'=>Date('Y-m-j H:i:s'),
                'updated_at'=>Date('Y-m-j H:i:s')
            ));

        Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => $channel_id]);
        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $id]);

        return $this->index($channel_id);
    }

    // /**
    //  * Display a listing of $COLLECTION$
    //  *
    //  * @return Response | JsonResponse
    //  */
    // public function tvapp_clean_table_please($channel_id)
    // {
    //     $playlists = \TvappPlaylist::where('id', '>', 0)->get();
    //     $res = array();
    //     foreach ($playlists as $playlist) {
    //         if( $playlist->video_is == 1 ) {
    //             $tvip = \TvappVideo_in_playlist::where("tvapp_playlist_id", "=", $playlist->id)->first();
    //             if( $playlist->parent != null ) $res[] = $playlist->id;
    //             elseif( $tvip == null ) $res[] = $playlist->id;
    //             elseif( $playlist->shelf == 0 ) $res[] = $playlist->id;
    //         }
    //     }
    //     foreach ($res as $id) {
    //         echo $id . " vasdeleted" . "<br>.";
    //         \TvappPlaylist::where('id', '=', $id)->delete();
    //     }
    //     return $res;
    // }

    // /**
    //  * Display a listing of $COLLECTION$
    //  *
    //  * @return Response | JsonResponse
    //  */
    // public function get_all_tvapp_playlists($channel_id)
    // {
    //     $playlists = \TvappPlaylist::where('id', '>', 0)->get();
    //     $res = array();
    //     echo "<pre>";

    //     foreach ($playlists as $playlist) {
    //         if( $playlist->parent_id !== null ){
    //             $parent = \TvappPlaylist::where('id', '=', $playlist->parent_id)->first();

    //             if( $parent == null ) $res[] = $playlist->id;
    //             else {
    //             //         var_dump($parent->shelf);
    //                 if( $parent->shelf != $playlist->shelf ){
    //                     var_dump($parent->id);
    //                     var_dump($parent->shelf);
    //                     var_dump($playlist->id);
    //                     var_dump($playlist->shelf);
    //                     var_dump("");
    //                 }
    //             }
    //         }
    //     }

    //     echo "</pre>";
    //     die;

    //     return $res;
    // }

    /**
     * Display a listing of $COLLECTION$
     *
     * @return Response | JsonResponse
     */
    public function delete_playlist($channel_id, $id, $sort_order, $parent_id)
    {
        if( \TvappVideo_in_playlist::where("tvapp_playlist_id", "=", $id)->first() ) {
            $video_id = \TvappVideo_in_playlist::where("tvapp_playlist_id", "=", $id)->first()->video_id;

            \TvappVideo_in_playlist::where("tvapp_playlist_id", "=", $id)->delete();
            \TvappVideo_in_playlist::insert(array(
                'video_id'=>$video_id,
                'tvapp_playlist_id'=>$parent_id,
                'sort_order'=>$sort_order,
                'created_at'=>Date('Y-m-j H:i:s'),
                'updated_at'=>Date('Y-m-j H:i:s')
            ));

            \TvappPlaylist::where('id', '=', $id)->delete();


        } else {
            \TvappVideo_in_playlist::where("tvapp_playlist_id", "=", $id)->delete();
            \TvappPlaylist::where('id', '=', $id)->delete();
        }

        Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => $channel_id]);
        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $parent_id]);
        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $id]);

        return $this->index($channel_id);
    }

    /**
     * Display a listing of $COLLECTION$
     *
     * @return Response | JsonResponse
     */
    public function delete_one_playlist($channel_id, $id)
    {

        \TvappVideo_in_playlist::where("tvapp_playlist_id", "=", $id)->delete();
        \TvappPlaylist::where('id', '=', $id)->delete();

        Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => $channel_id]);
        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $id]);

        return $this->index($channel_id);
    }

    /**
     * Show the form for creating a new $RESOURCE$
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created $RESOURCE$ in storage.
     *
     * @return Response
     */
    public function store()
    {

    }

    /**
     * Display the specified $RESOURCE$.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($channel_id, $id)
    {
        $playlist = \TvappPlaylist::with(['videos'])
            ->where("channel_id", "=", $channel_id)
            ->find($id);

        return $playlist;
    }

    /**
     * Show the form for editing the specified $RESOURCE$.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified $RESOURCE$ in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($channel_id, $id)
    {
        $playlist = \TvappPlaylist::with(['videos'])
            ->where("channel_id", "=", $channel_id)
            ->find($id);

        $playlist->fill(Input::all()); // fillable parent_id and sort_order only, so I just left it as is.

        if($playlist->parent_id == '0' || $playlist->parent_id == 'null') {
            $playlist->parent_id = NULL;
        }

        $playlist->save();

        $this->helper = new TvappPlaylistHelper($channel_id);
        $this->helper->normalizePlaylistOrder($playlist->parent_id, $playlist->id, $playlist->sort_order);
		
        Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => $playlist->channel_id]);
        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $id]);

        return $this->show($channel_id, $id);
    }

    /**
     * Remove the specified $RESOURCE$ from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($channel_id, $id)
    {
        if(!Auth::check()) {
            return Response::json([], 403);
        }

        // delete many-to-many table values because no foreign key was declared
        \TvappVideo_in_playlist::where('tvapp_playlist_id', '=', $id)->delete();

        // delete playlist itself
        $playlist = \TvappPlaylist::with(['videos'])
            ->where("channel_id", "=", $channel_id)
            ->find($id);

        if(!is_null($playlist)) {
            $playlist->delete();
        }

        Queue::push(UpdateXml::UPDATE_CHANNEL_ROOT, ['channel_id' => $channel_id]);
        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $id]);
        return Response::json([], 204);
    }


    public function addVideo($channel_id, $id, $video_id) {
        $this->helper = new TvappPlaylistHelper($channel_id);

        $playlist = \TvappPlaylist::with(['videos'])
            ->where("channel_id", "=", $channel_id)
            ->find($id);

        if(Input::has('sort_order')) {
            $sort_order = intval(Input::get('sort_order'));
        } else {
            $sort_order = $playlist->videos->count() + 1;
        }

        if($playlist->videos()->where('video.id', $video_id)->count() > 0) {
            $playlist->videos()->updateExistingPivot($video_id, [
                'sort_order' => $sort_order,
                'type' => 0,
            ]);

        } else {
            $playlist->videos()->attach([
                $video_id => [
                    'sort_order' => $sort_order,
                    'type' => 0,
                ]
            ]);
        }

        $this->helper->normalizeVideoOrder($id, $video_id);
        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $id]);
        return $this->show($channel_id, $id);
    }

    public function deleteVideo($channel_id, $id, $video_id) {
        $this->helper = new TvappPlaylistHelper($channel_id);

        $playlist = \TvappPlaylist::with(['videos'])
            ->where("channel_id", "=", $channel_id)
            ->find($id);

        $playlist->videos()->detach([ $video_id ]);

        $this->helper->normalizeVideoOrder($id);
        Queue::push(UpdateXml::UPDATE_PLAYLIST, ['id' => $id]);
        return $this->show($channel_id, $id);
    }
}
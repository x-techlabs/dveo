<?php namespace App\Jobs\Roku;


use App\Helpers\Playlists\TvappPlaylistHelper;
use Illuminate\Support\Facades\Log;

class UpdateXml {
    const UPDATE_PLAYLIST = 'App\Jobs\Roku\UpdateXml@updatePlaylist';
    const UPDATE_PLAYLIST_WITH_PARENTS = 'App\Jobs\Roku\UpdateXml@updatePlaylistWithParents';
    const UPDATE_CHANNEL_ROOT = 'App\Jobs\Roku\UpdateXml@updateChannelRoot';
    const REBUILD_CHANNEL = 'App\Jobs\Roku\UpdateXml@rebuildChannel';

    public $delete = true;

    private $helper;

    public function __construct() {
    }

    public function fire($job, $data)
    {
    }

    public function updatePlaylist($job, $data = []) {
        $playlist_id = intval($data['id']);
        $playlist = \TvappPlaylist::find($playlist_id);
        if(is_null($playlist)) {
            Log::error('Trying to updated non-existed playlist: playlist_id '.$playlist_id);
            return null;
        }

        $this->helper = new TvappPlaylistHelper($playlist->channel_id);
        $this->helper->BuildFeedXML($playlist_id);

        return $playlist;
    }

    public function updatePlaylistWithParents($job, $data = []) {
        $playlist_id = intval($data['id']);
        $playlist = $this->updatePlaylist($job,$data);
        if(is_null($playlist)) {
            return null;
        }

        $this->helper = new TvappPlaylistHelper($playlist->channel_id);
        $this->helper->BuildFeedXML($playlist_id);

        // also update all parent playlists and root
        while (!is_null($playlist->parent_id)) {
            $playlist = \TvappPlaylist::find($playlist->parent_id);
            $this->helper->BuildFeedXML($playlist_id);
        }

        $this->updateChannelRoot($job, ['channel_id' => $playlist->channel_id]);
    }

    public function updateChannelRoot($job, $data = []) {
        $channel_id = intval($data['channel_id']);
        $this->helper = new TvappPlaylistHelper($channel_id);
        $this->helper->BuildChannelRootXml();
    }

    public function rebuildChannel($job, $data = []) {
        $channel_id = intval($data['channel_id']);
        $this->updateChannelRoot($job, [
            'channel_id' => $channel_id,
        ]);

        $this->helper = new TvappPlaylistHelper($channel_id);
        $playlists = \TvappPlaylist::where('channel_id', '=', $channel_id)->get();
        foreach ($playlists as $playlist) {
            $this->helper->BuildFeedXML($playlist->id);
        }
    }
}
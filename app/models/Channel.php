<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/20/14
 * Time: 4:52 PM
 */
class Channel extends BaseModel
{
    protected $table = 'channel';

    public function toArray()
    {
        $channel = parent::toArray();
        $video = Video::where('channel_id', '=', $this->id)->get()->toArray();
        $playlist = Playlist::where('channel_id', '=', $this->id)->get()->toArray();

        $channel['playlists'] = $video;
        $channel['playlist'] = $playlist;
        return $channel;
    }

    private static function channel_deletion(Channel $channel) {

        $playlists = Playlist::where('channel_id', '=', $channel->id)->get();
        foreach($playlists as $playlist) {
            Video_in_playlist::where('playlist_id', '=', $playlist->id)->delete();
            $playlist->delete();
        }

        $videos = Video::where('channel_id', '=', $channel->id)->get();
        foreach($videos as $video) {
            Video_in_playlist::where('video_id', '=', $video->id)->delete();
            $video->delete();
        }
    }

    public static function onDeleteCallback($self) {
        foreach($self->get() as $channel) {
            self::channel_deletion($channel);
        }
    }

    public function delete() {
        Users_in_channels::where('channel_id', '=', $this->id)->delete();

        self::channel_deletion($this);

        parent::delete();
    }


    public function getHumanStorage($storage) {
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if ($storage <= $kb) {
            $divided = $storage;
            $storage = "{$divided} Bytes";

        }
        elseif ($storage <= $mb) {
            $divided = round($storage / $kb, 2);
            $storage = "{$divided} KB";
        }
        elseif ($storage <= $gb) {
            $divided = round($storage / $mb, 2);
            $storage = "{$divided} MB";
        }
        elseif ($storage <= $tb) {
            $divided = round($storage / $gb, 2);
            $storage = "{$divided} GB";
        }
        else {
            $divided = round($storage / $tb, 2);
            $storage = "{$divided} TB";
        }

        return $storage;
    }

    public function getStorageHumanAttribute() {

        $storage = "";

        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if ($this->storage <= $kb) {
            $divided = $this->storage;
            $storage = "{$divided} Bytes";
        }
        elseif ($this->storage <= $mb) {
            $divided = round($this->storage / $kb, 2);
            $storage = "{$divided} KB";
        }
        elseif ($this->storage <= $gb) {
            $divided = round($this->storage / $mb, 2);
            $storage = "{$divided} MB";
        }
        elseif ($this->storage <= $tb) {
            $divided = round($this->storage / $gb, 2);
            $storage = "{$divided} GB";
        }
        else {
            $divided = round($this->storage / $tb, 2);
            $storage = "{$divided} TB";
        }

        return $storage;
    }
}
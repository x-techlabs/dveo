<?php

class Schedule extends BaseModel
{
    protected $table = 'schedule';

    /**
     * Change playlist
     *
     * @param $job
     * @param array $data
     */
    public function change_playlist($job, Array $data)
    {

        Users::where('id', '=', '9')->update(array(
            'remember_token'=> 'alright'
        ));

        // Checking for playlist in masterloop
        if ((bool) Playlist::where('channel_id', '=', $data['channel_id'])->where('master_looped', '<>', '0')->count()) {
            $job->delete();
            return;
        }

        $videos = Video_in_playlist::where('playlist_id', '=', $data['playlist_id']);

        if (empty($videos)) {
            $job->delete();
            return;
        }

        // Getting DVEO instance
        $dveo = DVEO::getInstance($data['dveo_ip'], 25599, 'apiuser', 'Hn7P67583N9m5sS');

        // Changing platylist of channel
        $dveo->change_playlist($videos->get(), $data['channel_id']);

        // Restarting stream to run with new the changes
        $dveo->restart_stream($data['stream_name']);

        // Delete job
        $job->delete();
    }
}
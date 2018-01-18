<?php

/**
 * Created by PhpStorm.
 * User: ffffff
 * Date: 09.08.14
 * Time: 22:05
 */
class Queue
{

    public function fire($job, $data)
    {
        $last = $data['video_id'] - 1;

        if (DB::select('select * from playlist where id = ?', array($last))) {
            DB::update('update timeline set status = 0 where video_id = ?', array($last)); //change the status 0 (stop) in timeline table
            DB::update('update playlist set status = 0 where id = ?', array($last)); //change the status 0 (stop) in playlist table
        }

        DB::update('update timeline set status = 1 where video_id = ?', array($data['video_id'])); //change the status 1(play) in timeline table
        DB::update('update playlist set status = 1 where id = ?', array($data['video_id'])); //change the status 1(play) in timeline table
        $job->delete();
    }
} 
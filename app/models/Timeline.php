<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/18/14
 * Time: 3:03 PM
 */

class Timeline extends BaseModel
{
    protected $table = 'timeline';



    public function play($job, $data)
    {

        $last = $data['video_id']-1;

        if(Playlist::find($last) != NULL) {
            $timeline = self::where('video_id', '=', $last)->update(array('status' => 0));


            $palylist = Playlist::find($last);
            $palylist->status = 0; //change the status 0 (stop) in playlist table
            $palylist->save();
        }

        $timeline = self::where('video_id', '=', $data['video_id'])->update(array('status' => 1));

        $palylist = Playlist::find($data['video_id']);
        $palylist->status = 1; //change the status 1(play) in timeline table
        $palylist->save();

        $job->delete();
    }
} 
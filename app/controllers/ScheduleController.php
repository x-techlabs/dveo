<?php

class ScheduleController extends BaseController
{
    public function index()
    {
        $channel_id = BaseController::get_channel_id();
        $playlists = Playlist::where('channel_id', '=', $channel_id)->get();
        $videos = Video::where('channel_id', '=', $channel_id)->get();
        $schedules = Schedule::where('channel_id', '=', $channel_id)->get();
        $collections = Collections::where('channel_id', '=', $channel_id)->get();

        foreach ($schedules as $schedule) {
            if (empty(Playlist::find($schedule->playlist_id))) $schedule->delete();
        }

        $this->data['channel_id'] = $channel_id;
        $this->data['playlists'] = $playlists;
        $this->data['videos'] = $videos;
        $this->data['schedules'] = $schedules;
        $this->data['collections'] = $collections;

        return $this->render('schedule/schedule');
    }

    public function add()
    {
        $schedule = new Schedule;

        $drop_item_id = Input::get('drop_item_id');
        $drop_item_type = Input::get('drop_item_type');
        $event = Input::get('event');
        $date = Input::get('date');

        if(strcmp($drop_item_type,'playlist')==0){
            $this->set_playlist_to_dveo($drop_item_id);
        }elseif(strcmp($drop_item_type,'collection')==0) {
            $this->set_collection_to_dveo($drop_item_id);
        }elseif(strcmp($drop_item_type,'video')==0) {
            $this->set_video_to_dveo($drop_item_id);
        }

        return Response::json([
            'status' => true
        ]);

        $schedule->playlist_id = $drop_item_type;
        $schedule->channel_id = BaseController::get_channel_id();
        $schedule->name = $event['text'];
        $schedule->start_date = Carbon::createFromFormat('m/d/Y H:i:s', $date['start_date'], 'UTC');
        $schedule->end_date = Carbon::createFromFormat('m/d/Y H:i:s', $date['end_date'], 'UTC');
//        $schedule->start_date = Carbon::now(BaseController::get_channel()->timezone)->addMinutes(1);
//        $schedule->end_date = Carbon::now(BaseController::get_channel()->timezone)->addMinutes(3);
        $schedule->save();

        // Scheduling playlist change on given date
//        Queue::later(
//            Carbon::now(BaseController::get_channel()->timezone)->diffInSeconds($schedule->start_date),
//            'Schedule@change_playlist',
//            [
//                'playlist_id' => $playlist_id,
//                'channel_id' => BaseController::get_channel_id(),
//                'dveo_ip' => BaseController::get_dveo_ip(),
//                'stream_name' => BaseController::get_channel()->stream,
//            ]
//        );

        return Response::json([
            'status' => true,
            'schedule' => $schedule->toArray(),
            //'diff' => Carbon::now(BaseController::get_channel()->timezone)->diffInSeconds($schedule->start_date),
        ]);
    }

    public function deleteEvent()
    {
        $schedule_id = Input::get('schedule_id');
        Schedule::where('id', '=', $schedule_id)->delete();
        return Response::json([
            'status' => true
        ]);
    }

    public function getEnd()
    {
//        $schedule = Schedule::where('channel_id', '=', BaseController::get_channel_id())->orderBy('end_date', 'desc')->first();

        $schedule = Schedule::where('channel_id', '=', BaseController::get_channel_id())->orderBy('end_date', 'desc')->first();


        return Response::json([
            'schedule' => $schedule,
            'bool' => true
        ]);
    }


    ////

    public function set_playlist_to_dveo($playlist_id)
    {

        ###
        $videos_in_playlist = Video_in_playlist::where('playlist_id', '=', $playlist_id)->get();
        $playlist_videos = [];
        foreach ($videos_in_playlist as $video_in_playlist) {
            $video = Video::find($video_in_playlist['video_id']);
            $playlist_videos[time()] = $video['file_name'] . "." . $video['video_format'];
            sleep(1); //prevent key overwrite
        }

        // Change playlist on DVEO
        //$dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());

        // Creating a new DVEO instance
        //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');
        $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');
        $dveo->schedule_videos(BaseController::get_channel_id(),  $playlist_videos);

    }



    public function set_video_to_dveo($video_id)
    {
        $video = Video::find($video_id);

        // Creating a new DVEO instance
        //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');
        $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');
        $dveo->schedule_video(BaseController::get_channel_id(), time(), $video->file_name . '.mp4');

//        $playlist = new Playlist;
//        $playlist->title = "Playlist for video $video->title";
//        $playlist->description = "Playlist for video $video->title description";
//        $playlist->channel_id = BaseController::get_channel_id(); // This must by changed
//        $playlist->thumbnail_name = $video->thumbnail_name;
//        $playlist->duration = $video->duration;
//        $playlist->save();
//
//        $insert[] = array(
//            'playlist_id' => $playlist->id,
//            'video_id' => $video_id
//        );
//
//        $videos_in_playlists = Video_in_playlist::where('playlist_id', '=', $playlist->id)->get();
//
//        foreach($videos_in_playlists as $videos_in_playlist) {
//            $videos_in_playlist->delete();
//        }
//
//        Video_in_playlist::Insert($insert);
//
//        return $playlist->id;
    }

    public function set_collection_to_dveo($collection_id)
    {

        ###
        $videos_in_collections = Videos_in_collections::where('collection_id', '=', $collection_id)->get();
        $playlist_videos = [];
        foreach ($videos_in_collections as $video_in_collection) {
            $video = Video::find($video_in_collection['video_id']);
            $playlist_videos[time()] = $video['file_name'] . "." . $video['video_format'];
            sleep(1); //prevent key overwrite
        }

        // Change playlist on DVEO
        //$dveo->change_playlist($videos_in_playlist, BaseController::get_channel_id());

        // Creating a new DVEO instance
        //$dveo = DVEO::getInstance('162.247.57.18', 25599, 'Hn7P67583N9m5sS');
        $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');
        $dveo->schedule_videos(BaseController::get_channel_id(),  $playlist_videos);

        ###


//        $collection = Collections::find($collection_id);
//
//        $playlist = new Playlist;
//        $playlist->title = "Playlist for collection $collection->title";
//        $playlist->description = "Playlist for collection $collection->title description";
//        $playlist->channel_id = BaseController::get_channel_id(); // This must by changed
//        $playlist->thumbnail_name = '';
//        $playlist->duration = '';
//        $playlist->save();
//
//        $videos_in_collections = Videos_in_collections::where('collection_id', '=', $collection_id)->get();
//        $count = 0;
//        $collection_duration = 0;
//        foreach($videos_in_collections as $videos_in_collection) {
//            $insert[] = array(
//                'playlist_id' => $playlist->id,
//                'video_id' => $videos_in_collection->video_id
//            );
//            $collection_duration += Video::find($videos_in_collection->video_id)->duration;
//            if($count==0){
//                $first_video_in_collection = Video::find($videos_in_collection->video_id);
//            }
//            $count++;
//        }
//
//        Playlist::where('id', '=', $playlist->id)->update(array(
//            'thumbnail_name'=> $first_video_in_collection->thumbnail_name,
//            'duration' => $collection_duration
//        ));
//
//        $videos_in_playlists = Video_in_playlist::where('playlist_id', '=', $playlist->id)->get();
//
//        foreach($videos_in_playlists as $videos_in_playlist) {
//            $videos_in_playlist->delete();
//        }
//
//        Video_in_playlist::Insert($insert);
//
//        return $playlist->id;
    }

}
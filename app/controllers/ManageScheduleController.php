<?php

class ManageScheduleController extends BaseController {

    public function index()
    {
        $channel_id = BaseController::get_channel_id();
        $playlists = Playlist::where('channel_id', '=', $channel_id)->get();
        $videos = Video::where('channel_id', '=', $channel_id)->where('source', '=', 'internal')->get();

        //$collections = Collections::where('channel_id', '=', $channel_id)->get();
        $collections = $this->GetCollectionAndVideosUnderIt();
        $timezone = date_default_timezone_get();
        $availableTime = 31536000; //One year by default
        $astatus = "available";


        $time = date('Y-m-d h:m:s');
        $tempTime = ManageScheduleModel::max('end_date');
        if(isset($tempTime))
            $time = $tempTime;

        $this->data['channel_id'] = $channel_id;
        $this->data['playlists'] = $playlists;
        $this->data['videos'] = $videos;
        $this->data['collections'] = $collections;
        $this->data['time'] = $time;
        $this->data['timezone'] = $timezone;
        $this->data['availableTime'] = $availableTime;
        $this->data['astatus'] = $astatus;
        $this->data['endDate'] = $time;
        $this->data['videoList'] = "videoList";
        $this->data['name'] = "name";
        $this->data['url'] = "url";
        $this->data['genere'] = "genere";
        $this->data['sid'] = "sid";

        return $this->render('schedule/scheduleAdd');
    }

    public function GetCollectionAndVideosUnderIt()
    {
        $channel_id = BaseController::get_channel_id();
        $collections = Collections::where('channel_id', '=', $channel_id)->get();
        $videosIncollections = array();

        foreach($collections as $k => $collection)
        {
            $vc = array();
            $videos_in_collections = Videos_in_collections::where('collection_id', '=', $collection->id)->get();
            foreach ($videos_in_collections as $videoInCollection) 
            {
                $video = Video::find($videoInCollection['video_id']);
                if (!is_object($video)) continue;

                //$vc[] = "{id: ".($video->id*4000000).", text:'".addslashes($video->title)."', item: null, child: 1, userdata: [ {name:'id', content:'$video->id'}, {name:'drop_item_type', content:'video'}, {name:'item_type', content:'video'}, {name:'thumbnail', content:'$video->thumbnail_name'}, {name:'duration', content:'$video->duration'}]}"; 
                $vc[] = "{id: ".($video->id*4000000).", text:'".addslashes($video->title)."', item: null, child: 1, userdata:[{name:'id', content:'$video->id'}, {name:'drop_item_type', content:'video'}, {name:'item_type', content:'video'}, {name:'thumbnail', content:'$video->thumbnail_name'}, {name:'duration', content:'$video->duration'}]}"; 
            }
            $collections[$k]->videosIncollections = 'null';
            if (count($vc) > 0) $collections[$k]->videosIncollections = '['.implode(',', $vc).']';
        }
        return $collections;
    }

    public function showTimeline()
    {
        $sstatus = "NA";
        $name = "NA";
        $events = $this->getEvents();
        $calendar = Calendar::addEvents($events);
        $calendar = Calendar::setCallbacks($this->setCallbacks());
        $calendar->setOptions($this->setOptions());
        $this->data['calendar'] = $calendar;
        $this->data['name'] = $name;
        $this->data['sstatus'] = $sstatus;
        return $this->render('schedule/scheduleTimeline');
    }

    public function dateChange() {
        $availableTime = 31536000; //One year by default
        $astatus = "available";
        $time = date('Y-m-d h:m:s');
        $date = Input::get('date');
        $tempTime1 = ManageScheduleModel::max('end_date');
        if(isset($tempTime1))
            $time = $tempTime1;

        $slots = ManageScheduleModel::where('start_date', '<=', $date)->where('end_date', '>=', $date)->first();
        if(isset($slots)) {
            $astatus = "not_available";
        } else {
            $time = $date;
            $tempTime = ManageScheduleModel::where('start_date', '>', $date)->min('start_date');
            if(isset($tempTime)) {
                $time1 = strtotime($tempTime);
                $time2 = strtotime($date);
                $availableTime = $time1 - $time2;
            }
        }        

        $channel_id = BaseController::get_channel_id();
        $playlists = Playlist::where('channel_id', '=', $channel_id)->get();
        $videos = Video::where('channel_id', '=', $channel_id)->get();
        //$collections = Collections::where('channel_id', '=', $channel_id)->get();
        $collections = $this->GetCollectionAndVideosUnderIt();
        $timezone = date_default_timezone_get();       

        $this->data['channel_id'] = $channel_id;
        $this->data['playlists'] = $playlists;
        $this->data['videos'] = $videos;
        $this->data['collections'] = $collections;
        $this->data['time'] = $time;
        $this->data['timezone'] = $timezone;
        $this->data['availableTime'] = $availableTime;
        $this->data['astatus'] = $astatus;
        $this->data['endDate'] = $time;
        $this->data['videoList'] = "videoList";
        $this->data['name'] = "name";
        $this->data['url'] = "url";
        $this->data['genere'] = "genere";
        $this->data['sid'] = "sid";

        return $this->render('schedule/scheduleAdd');
    }

    public function saveSchedule()
    {
        $name = Input::get('showName');
        $sstatus = "failure";
        $VideoList = Input::get('scheduledVideosList');
        $start = Input::get('showStart');
        $end = Input::get('showEnd');
        $url = Input::get('showUrl');
        $genere = Input::get('showGenere');
        $sid = Input::get('scheduleId');
        $videoIdString = "";
        $str = explode(",", $VideoList);
        for ($x = 0; $x <= count($str); $x++) {
            if (array_key_exists($x, $str)){
                $strn = explode("$$$", $str[$x]);
                $videoIdString = $videoIdString . "," . (string)$strn[0];
            }
        } 
        $id = 0;

        if($sid == 0) {
            $id = ManageScheduleModel::insert(
            array('channel_id' => BaseController::get_channel_id(), 'name' => $name, 'start_date' => $start,
                'end_date' => $end, 'genere' => $genere, 'url' => $url, 'video_id_list' => $videoIdString)
            );
        } else {
            $id = ManageScheduleModel::where('id', $sid)
            ->update(array('channel_id' => BaseController::get_channel_id(), 'name' => $name, 'start_date' => $start,
                'end_date' => $end, 'genere' => $genere, 'url' => $url, 'video_id_list' => $videoIdString));
        }

        if($id > 0)
            $sstatus = "success";

        $events = $this->getEvents();

        $calendar = Calendar::addEvents($events);
        $calendar = Calendar::setCallbacks($this->setCallbacks());
        $calendar->setOptions($this->setOptions());
        $this->data['calendar'] = $calendar;
        $this->data['name'] = $name;
        $this->data['sstatus'] = $sstatus;
        return $this->render('schedule/scheduleTimeline');
    }

    public function getEvents()
    {
        $channel_id = BaseController::get_channel_id();
        $schedules = ManageScheduleModel::where('channel_id', '=', $channel_id)->get();
        $events = [];

        foreach ($schedules as $schedule) {
            $title = $schedule->id . "-" . $schedule->name;
            $startTime = strtotime($schedule->start_date);
            $startTime = date('Y-m-d H:i:s',$startTime);
            $endTime = strtotime($schedule->end_date);
            $endTime = date('Y-m-d H:i:s',$endTime);
            // if(($endTime - $startTime).getSecon)
            $var = Calendar::event(
                $title, //event title
                false,
                $startTime, 
                $endTime
            );
            array_push($events, $var);
        }
        return $events;
    }

    public function setOptions()
    {
        $Options = [
            'theme' => true,
            'header' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'month,agendaWeek,agendaDay',
            ],
            'eventLimit' => true,
            'defaultView' => 'agendaWeek',
            'editable' => true,
            'allDaySlot ' => false,
            'displayEventEnd' => true,
            'displayEventTime' => true,
            'eventDurationEditable' => false,
            'eventOverlap' => false,
            'timeFormat'=> 'h:mma',      // the output i.e. "10:00pm"
            'displayEventEnd' => true,
            'slotDuration'=> '00:15:00', 
            'snapDuration'=> '00:01:00'
        ];
        return $Options;
    }

    public function setCallbacks()
    {
        $Callbacks = [
            'eventClick' => "function(calEvent, jsEvent, view) {
                $(this).css('border-color', 'green');
                $( '#dialog' ).dialog({
                    resizable: false,
                    height:100,
                    width:500,
                    modal: true,
                    title: 'Want do you want to do?',
                    buttons: {
                    'Close': function() {
                    $('#dialog').dialog( 'close' );
                    },
                    'Edit': function() {
                        method = 'post'; 
                        path = ace.path('editSchedule');
                        params = {scheduleId: calEvent.title};
                        var form = document.createElement('form');
                        form.setAttribute('method', method);
                        form.setAttribute('action', path);

                        for(var key in params) {
                            if(params.hasOwnProperty(key)) {
                                var hiddenField = document.createElement('input');
                                hiddenField.setAttribute('type', 'hidden');
                                hiddenField.setAttribute('name', key);
                                hiddenField.setAttribute('value', params[key]);

                                form.appendChild(hiddenField);
                            }
                        }
                        document.body.appendChild(form);
                        form.submit();
                    },
                    'Delete': function() {
                        $.ajax({
                        url: 'deleteSchedule',
                        type:'POST',
                        dataType:'JSON',
                        async: true,
                        data:{
                            'id' : calEvent.title
                        },
                        success:function(data){
                            $('#dialog').dialog( 'close' );
                            var loc = document.location.href;
                            var url = loc.substring(0, loc.lastIndexOf('/'));
                            location.href = url + '/timeline';
                        },
                        error:function(response){
                            document.getElementById('modalContent').innerHTML = 'Error while deleting schedule. Try again';
                            $('#infoModal').modal('toggle');
                        },                
                        });
                    }
                    }
                });
            }",
            // 'eventAfterRender' => "function(event, element, view) { element.find('.fc-time').append('<span style=\'font-size:15px; float:right;\'><i class=\'fa fa-pencil fa-fw\'></i><i class=\'fa fa-trash-o fa-fw\'></span>'); }",
            'eventMouseover' => "function(calEvent, jsEvent, view) {
                $(this).css('border-color', 'red');
            }",
            'eventMouseout' => "function(calEvent, jsEvent, view) {
                $(this).css('border-color', '#3a87ad');
            }",
            'eventDrop' => "function( event, delta, revertFunc, jsEvent, ui, view ) { 
                var sd = event.start.format();
                var ed = event.end.format();
                $.ajax({
                url: 'eventDragged',
                type:'POST',
                dataType:'JSON',
                async: true,
                data:{
                    'start': sd,
                    'end' : ed,
                    'id' : event.title
                },
                success:function(data){
                    document.getElementById('modalContent').innerHTML = 'Schedule is updated successfully.';
                    $('#infoModal').modal('toggle');
                },
                error:function(response){
                    document.getElementById('modalContent').innerHTML = 'Selected time slot is not available. Try again';
                    $('#infoModal').modal('toggle');
                    revertFunc();
                },                
                });
            }",
        ];
        return $Callbacks;        
    }

    public function editSchedule()
    {
        $availableTime = 31536000; //One year by default
        $sidName = Input::get('scheduleId');
        if(!isset($sidName))
            return View::make('error')->with(array('message' => 'Something went wrong. Try again.'));

        $str = explode("-", $sidName);
        $sid = (int)$str[0];

        $schedule = ManageScheduleModel::where('id', $sid)->first();
        $channel_id = $schedule->channel_id;
        $time = $schedule->start_date;
        $endDate = $schedule->end_date;
        $videoList = $schedule->video_id_list;
        $name = $schedule->name;
        $url = $schedule->url;
        $genere = $schedule->genere;

        $tempTime = ManageScheduleModel::where('start_date', '>', $time)->min('start_date');
        if(isset($tempTime)) {
            $time1 = strtotime($tempTime);
            $time2 = strtotime($time);
            $availableTime = $time1 - $time2;
        }

        $playlists = Playlist::where('channel_id', '=', $channel_id)->get();
        $videos = Video::where('channel_id', '=', $channel_id)->where('source', '=', 'internal')->get();
        //$collections = Collections::where('channel_id', '=', $channel_id)->get();
        $collections = $this->GetCollectionAndVideosUnderIt();
        $timezone = date_default_timezone_get();       

        $this->data['channel_id'] = $channel_id;
        $this->data['playlists'] = $playlists;
        $this->data['videos'] = $videos;
        $this->data['collections'] = $collections;
        $this->data['time'] = $time;
        $this->data['timezone'] = $timezone;
        $this->data['availableTime'] = $availableTime;
        $this->data['astatus'] = "not_available";
        $this->data['endDate'] = $endDate;
        $this->data['videoList'] = $videoList;
        $this->data['name'] = $name;
        $this->data['url'] = $url;
        $this->data['genere'] = $genere;
        $this->data['sid'] = $sid;

        return $this->render('schedule/scheduleAdd');
    }

    public function eventDragged() {
        $start = Input::get('start');
        $end = Input::get('end');
        $idStr = Input::get('id');
        $str = explode("-", $idStr);
        $sid = (int)$str[0];
        $startTime = date('Y-m-d H:i:s', strtotime($start));
        $endTime = date('Y-m-d H:i:s', strtotime($end));

        $id = ManageScheduleModel::where('id', $sid)
        ->update(array('start_date' => $startTime, 'end_date' => $endTime));

        if($id<0)
            return;

        return Response::json("Schedule is updated successfully.");
    }

    public function deleteSchedule() {
        $idStr = Input::get('id');
        $str = explode("-", $idStr);
        $sid = (int)$str[0];

        ManageScheduleModel::where('id',  $sid)->delete();

        return Response::json("Schedule is updated successfully.");
    }
}
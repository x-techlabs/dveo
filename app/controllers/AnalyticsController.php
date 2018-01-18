<?php
class AnalyticsController extends BaseController {


    public function index() {

        $channel_id = BaseController::get_channel_id();

        $this->data['channel'] = $channel_id;
        return $this->render('analytics/analytics');
    }

    function Hex2Str($in)
    {
        $out="";
        $z = strlen($in);
        for ($i = 0 ; $i < $z ; $i+=2) 
        {
            $d = hexdec(substr($in, $i, 2));
            $out .= chr($d);
        }
        return $out;
    }

    public function playbackStart()
    {
        $data = trim(Input::get('id'));
        $parts = explode('|', $this->Hex2Str($data));

        if (count($parts) == 4)
        {
            $deviceID = $parts[0];
            $cid = $parts[1];
            $ctype = $parts[2];
            $channel_id = $parts[3]; 
            $secret = strtoupper(md5(uniqid(rand(), true)));
            while(strlen($secret) < 32) $secret .= '0';
            $secret = substr($secret, 0, 32);

            $t1 = time();
            $t3 = explode(':', date('G:i:s', $t1));

            $tracker = new Tracker;
            $tracker->channel_id = $channel_id;
            $tracker->content_id = $cid;
            $tracker->content_type = $ctype;
            $tracker->device_id = $deviceID; 
            $tracker->ip_address = $_SERVER['REMOTE_ADDR'];
            $tracker->start_date = date('Ymd',$t1);
            $tracker->start_time = $t3[0]*3600 + $t3[1]*60 + $t3[2];
            $tracker->duration = 0;
            $tracker->secret_code = $secret;
            $tracker->save();
            print $secret.$tracker->id;
        }
        print '';
    }

    public function playbackEnd()
    {
        $sid = trim(Input::get('id'));
        $secret = substr($sid, 0, 32);
        $id = substr($sid, 32);

        $tracker = Tracker::find($id);
        if (is_object($tracker))
        {
            if ($tracker->secret_code == $secret)
            {
                $t1 = mktime(0, 0, intval($tracker->start_time),
                                   intval(substr($tracker->start_date,4,2)), 
                                   intval(substr($tracker->start_date,6,2)), 
                                   intval(substr($tracker->start_date, 0,4)) 
                            );
                $duration = time() - $t1;
                Tracker::where('id', $id)->update( array('duration' => $duration) );
            }
        }
    }

    public function analytics_set_report_title($sid, $smid, $data1, $data2)
    {
        if ($sid==1)
        {
            $title = 'Viewer Distribution - Yearly';
            if ($smid=='m') $title = 'Monthly Viewer Distribution for year '.substr($data1, 0, 4);
            else if ($smid=='d') $title = 'Daily Viewer Distribution for '.$data1.' '.$data2;
            else if ($smid=='h') $title = 'Hourly Viewer Distribution for day '.$data1;
            return $title;
        }
        else if ($sid==2)
        {
            $title = 'Viewer Time Distribution - Yearly';
            if ($smid=='m') $title = 'Monthly Viewer Time Distribution for year '.substr($data1, 0, 4);
            else if ($smid=='d') $title = 'Daily Viewer Time Distribution for '.$data1.' '.$data2;
            else if ($smid=='h') $title = 'Hourly Viewer Time Distribution for day '.$data1;
            return $title;
        }
        return '';
    }

    public function live_data_update(& $in, $needle, $no)
    {
        if (!in_array($needle, $in[1])) {  $in[0] += $no; $in[1][] = $needle; }
    }

    public function analytics_live_data($channel_id)
    {
        $t1 = time();
        $sdate = date('Ymd', $t1);
        $beginningOfDay = mktime(0, 0, 0, intval(substr($sdate,4,2)), intval(substr($sdate,6,2)), intval(substr($sdate, 0,4)) ); 
        $endtime = $t1 - $beginningOfDay;
        $starttime = $endtime - 1800;

        $viewers = array();
        $usageVideo = array();
        $usageLive = array();

        for ($i = 0 ; $i < 30 ; $i++) 
        {
            $viewers[] = array(0, array());
            $usageVideo[] = array(0, array());
            $usageLive[] = array(0, array());
        }

        $records = Tracker::where('channel_id', '=', $channel_id)
                           ->where('start_date', '=', $sdate)
                           ->where('start_time', '>=', $starttime)
                           ->where('start_time', '<=', $endtime)
                           ->get();

        Log::info("No of records in between $starttime and $endtime on $sdate are ".count($records));

        $out2 = array();
        foreach($records as $rec)
        {
            $from = $rec->start_time - $starttime;  $fm = floor($from / 60);
            $too = $from + $rec->duration;          $tm = ceil($too / 60);
            Log::info("Updating info in between $fm and $tm");
            for ($i = $fm ; $i < $tm ; $i++)
            {
                // viewers calculation
                $this->live_data_update($viewers[$i], $rec->device_id, 1);
                if ($rec->content_type==0) $this->live_data_update($usageVideo[$i], $rec->content_id, 1);
                else if ($rec->content_type==1) $this->live_data_update($usageLive[$i], $rec->content_id, 1);
            }
        }

        $lables = array();
        $y1 = array();
        $y2 = array();
        $y3 = array();
        for ($s = $starttime, $i = 0 ; $i < 30 ; $i++, $s += 60) 
        {
            $lables[] = date('H:i', $s);
            $y1[] = $viewers[$i][0];
            $y2[] = $usageVideo[$i][0];
            $y3[] = $usageLive[$i][0];
        }
        print implode(';', $lables);
        print '^Active Users^rgba(170,242,0,1)';
        print '^'.implode(';', $y1);
        print '^Videos^rgba(255,207,117,1)';
        print '^'.implode(';', $y2);
        print '^Live Streams^rgba(255,128,0,1)';
        print '^'.implode(';', $y3);
        exit();
    }

    public function analytics_get_data_for()
    {
        $sid = trim(Input::get('menuid'));
        $smid = strtolower(Input::get('submenu'));
        $data1 = $rMin = Input::get('rmin');
        $data2 = $rMax = Input::get('rmax');
        $channel_id = BaseController::get_channel_id();

        $monthArray = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        $lables = array(); 
        $data = array();
        $historyData = '';

        if ($sid==1)
        {
            $this->analytics_live_data($channel_id);
        }
        else if ($sid==2 || $sid==3)
        {
            if ($smid=='y' || $smid=='m' || $smid=='d' || $smid=='h')
            {
                if ($smid=='d')
                {
                    $k = array_search($rMin, $monthArray);
                    $historyData = $data2.sprintf('%02d',$k+1);

                    $rMin = $data2.sprintf('%02d',$k+1).'01';
                    $rMax = $data2.sprintf('%02d',$k+1).'31';

                    $days = date('t', mktime(0,0,0,$k+1,10,$data2));
                    for ($i = 1 ; $i <= $days ; $i++) { $lables[] = $i; $data[] = 0; }
                }
                else if ($smid=='m')
                {
                    $lables = $monthArray;
                    $data = array(0,0,0,0,0,0,0,0,0,0,0,0);
                }
                else if ($smid=='h')
                {
                    $rMin = $rMax = $data2.$data1;
                    for ($i = 1 ; $i <= 24 ; $i++) { $lables[] = $i; $data[] = 0; }
                }
                    
                $records = Tracker::where('channel_id', '=', $channel_id)
                                   ->where('start_date', '>=', $rMin)
                                   ->where('start_date', '<=', $rMax)
                                   ->orderBy('start_date')
                                   ->get();

                $out2 = array();
                foreach($records as $rec)
                {
                    $k = substr($rec->start_date, 0, 4);
                    if ($smid=='m') $k = substr($rec->start_date, 4, 2);
                    else if ($smid=='d') $k = substr($rec->start_date, 6, 2);
                    else if ($smid=='h') $k = floor($rec->start_time/3600);

                    if (!isset($out2["$k"])) $out2["$k"] = 0;

                    if ($sid==2) $out2["$k"]++;
                    else if ($sid==3) $out2["$k"] += $rec->duration;
                }

                if ($smid=='y') 
                {
                    foreach($out2 as $k => $v) { $lables[] = $k;  $data[] = $v; }
                }
                else if ($smid=='m') 
                {
                    foreach($out2 as $k => $v) $data[$k-1] = $v;
                    $historyData = substr($rMin, 0, 4);
                }
                else if ($smid=='d') 
                {
                    foreach($out2 as $k => $v) $data[$k-1] = $v;
                }
                else if ($smid=='h') 
                {
                    foreach($out2 as $k => $v) $data[$k] = $v;
                }

                print implode(';', $lables);
                print '^'.( ($sid==3) ? 'Seconds' : 'viewers');
                print '^'.implode(';', $data);
                print '^'.$this->analytics_set_report_title($sid, $smid, $data1, $data2);
                print '^'.$historyData;

                exit();
            }
        }
/*
        foreach($records as $rec)
        {
            if (!isset($out2[$rec->start_date])) $out2[$rec->start_date] = 0;
            $out2[$rec->start_date]++;
            /*
            $out[] = array('start_date' => $rec->start_date, 
                           'start_time' => $rec->start_time,
                           'duration' => $rec->duration,
                           'device_id' => $rec->device_id,
                           'content_id' => $rec->content_id,
                           'content_type' => $rec->content_type
                           );
        }
        foreach($out2 as $k => $v)
        {
            $out[] = $k;
            $out[] = $v;
        }
        print implode(';', $out);
        //print json_encode($out);
*/
    }
}

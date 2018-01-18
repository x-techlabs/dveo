@extends('template.template')

@section('content')
<style type="text/css" media="screen">
    .scheduler{
        /* padding-left: 10px; */
        height: 85%;
        /* height: calc(100% - 62px); */
        background:#ffffff;
    }
</style>

<script type="text/javascript" charset="utf-8">
    var scheduledVideos = [];
    var tree = window.tree;
    $(document).ready(function() {

        tree = new dhtmlXTreeObject("treeBox", "100%", "100%", 0);
        tree.enableDragAndDrop(true);
        tree.setSkin("dhx_terrace");
        tree.attachEvent("onDrag", function() { return false; });
        tree.setImagePath("{{ asset('css/schedule/terrace/imgs/dhxtree_terrace/') }}/");
        tree.setOnDblClickHandler(tondblclick);

        var json_object = {
            id:0,
            item:[]
        }

        json_object.item.push({
            id: 2000000,
            text: "Videos",
            item:[
                @foreach($videos as $video)
                    <?php $v = addslashes($video->title);  ?>
                    @if(1==1)
                        {{ '{' }}
                        {{ "id: $video->id*2000000, text:'$v'," }}
                        {{ "item: null," }}
                        {{ "child: 1," }}
                        {{ "userdata: [" }}
                        {{     "{name: 'id', content: '$video->id'}," }}
                        {{     "{name: 'drop_item_type', content: 'video'}," }}
                        {{     "{name: 'thumbnail', content: '$video->thumbnail_name'}," }}
                        {{     "{name: 'duration', content: '$video->duration'}" }}
                        {{ "]}," }}
                    @endif
                @endforeach
            ]
        });

        tree.loadJSONObject(json_object, function() { });

        if(window.location.href.indexOf("newScheduleDate") != -1) {
            if("{{ $astatus }}" != "available")
            {
                document.getElementById('modalContent').innerHTML = "Selected time slot overlaps with another schedule. Default date selected.";
                $('#infoModal').modal('toggle');
            } else {
                document.getElementById('modalContent').innerHTML = "Selected time slot is available. Slot duration = " +  secondsToTime({{ $availableTime }});
                $('#infoModal').modal('toggle');
            }
        } else if(window.location.href.indexOf("editSchedule") != -1) {
            document.getElementById('show_genre').value = "{{ $genere }}";
            document.getElementById('show_url').value = "{{ $url }}";
            document.getElementById('show_name').value = "{{ $name }}";
            document.getElementById('startDate').disabled = true;
            document.getElementById('cal').disabled = true;
            var str = "{{ $videoList }}";
            var video_id = str.split(",");
            for(var i = 0; i < video_id.length; i++) {
               tondblclick(video_id[i]*2000000);
            }
        } else {
            document.getElementById('modalContent').innerHTML = "Default schedule time is set to end time of last schedule. You can edit this.";
            $('#infoModal').modal('toggle');
        }

        window.setTimeout('X()', 1000);
    });

    function X()
    {
        ht = $('#scheduler_here').height();
        document.getElementById('treeBox').style.height = ht + 'px';
        window.setTimeout('X()', 3000);
    }

    function tondblclick(id){
        if(id.toString().indexOf("1_") != -1 || id.toString().indexOf("1000000_") != -1  || id.toString().indexOf("2000000_") != -1 
            || id == 1 || id == 2000000 || id == 1000000)
            return;

        var video_list;
        var video_id = tree.getUserData(id, 'id');
        if(typeof video_id == 'undefined')
            return;

        var duration = tree.getUserData(id, 'duration');
        var type =  tree.getUserData(id, 'drop_item_type');//video, collection, playlist
        var label = tree.getItemText(id);
        var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
        var divId = randLetter + Date.now();
        var secID = divId + "section";
        var durID = divId + "duration";
        var thumbnail = tree.getUserData(id, 'thumbnail');
        var img;
        if(thumbnail != "")
            img = '<img src="'+ thumbnail + '" class="thumbnail_video">';
        else
            img = 'In process ...';

        if((Number(timeToSeconds(document.getElementById('runDuration').innerHTML)) + Number(duration)) > {{ $availableTime }})
        {
            var aTime = {{ $availableTime }} - Number(timeToSeconds(document.getElementById('runDuration').innerHTML));
            document.getElementById('modalContent').innerHTML = "Cannot add this video. Total duration is exceeding avilable time. Time left = " + secondsToTime(aTime);
            $('#infoModal').modal('toggle');
            return;
        }

        $('#scheduleContents').append('<section id='+ secID +' data-video_id='+ video_id +' class="list_item section_video" style="position: relative;">'+
        '<button id='+ divId +' onclick="onDeletePressed(this)" class="delete_video editDelete fr btn btn-block btn-lg btn-danger" title="Remove video">' +
        '<span class="fui-trash"></span>' +
        '</button>' +
        '<div class="clear"></div>' +
        '<div class="row center-block">' +
        '<div class="col-md-2">' +
        img +
        '</div>' +
        '<div class="col-md-8">' +
        '<h1 class="scheduleVideoTitle">'+ label + '</h1>' +
        '<span class="duration">' +
        '<img src="/images/time_icon.png" style="margin-top: -4px;"><div id='+ durID +' style="display:inline">'+ secondsToTime(duration) +
        '</div></span>' +
        '</div>' +
        '<div class="col-md-2"></div>' +
        '</div>' +
        '</section>');
        
        scheduledVideos.push(video_id + "$$$" + secID);

        var runTime = new Date(document.getElementById('runTime').innerHTML);
        runTime.setSeconds(runTime.getSeconds() + Number(duration));
        runTime = formatDate(runTime)
        document.getElementById('runTime').innerHTML = runTime;

        var runDuration = timeToSeconds(document.getElementById('runDuration').innerHTML);
        runDuration = Number(runDuration) + Number(duration);
        runDuration = secondsToTime(runDuration);
        document.getElementById('runDuration').innerHTML = runDuration;

        // document.getElementById('modalContent').innerHTML = label + " has been added to scheduled content." + type + duration + "" + video_id;
        // $('#infoModal').modal('toggle');
    };

    function secondsToTime(secs)
    {
        secs = Math.round(secs);
        var hours = Math.floor(secs / (60 * 60));
        var divisor_for_minutes = secs % (60 * 60);
        var minutes = Math.floor(divisor_for_minutes / 60);
        var divisor_for_seconds = divisor_for_minutes % 60;
        var seconds = Math.ceil(divisor_for_seconds);
        hours = checkTime(hours);
        minutes = checkTime(minutes);
        seconds = checkTime(seconds);
        return hours + ":" + minutes + ":" + seconds;
    }

    function timeToSeconds(time) {
        var a = time.split(':'); // split it at the colons
        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
        return seconds;
    }

    function formatDate(date) {
        var yyyy = date.getFullYear().toString();                                    
        var mm = (date.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = date.getDate().toString(); 

        var h = date.getHours();
        var m = date.getMinutes();
        var s = date.getSeconds();
        // add a zero in front of numbers<10
        h = checkTime(h)
        m = checkTime(m);
        s = checkTime(s);
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]) + ' ' + h + ':' + m + ':' + s;
    }

    function checkTime(i) {
        return (i < 10) ? "0" + i : i;
    }

    function onDeletePressed(item){
        var duration = timeToSeconds(document.getElementById(item.id + "duration").innerHTML);
        var runTime = new Date(document.getElementById('runTime').innerHTML);
        runTime.setSeconds(runTime.getSeconds() - Number(duration));
        runTime = formatDate(runTime)
        document.getElementById('runTime').innerHTML = runTime;

        var runDuration = timeToSeconds(document.getElementById('runDuration').innerHTML);
        runDuration = Number(runDuration) - Number(duration);
        runDuration = secondsToTime(runDuration);
        document.getElementById('runDuration').innerHTML = runDuration;

        var index = scheduledVideos.indexOf(document.getElementById(item.id + "section").getAttribute("data-video_id") + "$$$" + item.id + "section");
        if (index >= 0) {
            scheduledVideos.splice( index, 1 );
        }
  
        return (elem=document.getElementById(item.id + "section")).parentNode.removeChild(elem);
    }

    function dateChanged(){
        method = "post"; // Set method to post by default if not specified
        path = ace.path('newScheduleDate');
        params = {date: document.getElementById('startDate').value};
        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);

        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);

                form.appendChild(hiddenField);
             }
        }

        document.body.appendChild(form);
        form.submit();
    }

    function saveSchedule()
    {
        var name=document.getElementById('show_name').value;
        if(name == ""){
            alert('Please enter show name.');
            document.getElementById('show_name').style.borderColor = "red";
            return;
        }
        if(scheduledVideos == null) {
            alert('Please add schedule content to continue.')
            return;
        }
         if (scheduledVideos.length === 0) {
            alert('Please add schedule content to continue.')
            return;
        }
        var genere=document.getElementById('show_genre').value;
        var url=document.getElementById('show_url').value;
        var start=document.getElementById('startDate').value;
        var end=document.getElementById('runTime').innerHTML; 
        var sid=0;
        method = "post";
        path = ace.path('saveSchedule');
        if(window.location.href.indexOf("editSchedule") != -1) {
            path = ace.path('editSaveSchedule');
            sid = {{ $sid }};
        }
        params = {showName:name, scheduledVideosList:scheduledVideos, showGenere:genere, showUrl:url, showStart:start, 
            showEnd:end, scheduleId:sid};
        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);

        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);
                form.appendChild(hiddenField);
             }
        }

        document.body.appendChild(form);
        form.submit();          
    }
</script>
<div class="row center-block" id="contnet-wrap">
    <div class="col-md-12 height" id="schedule">
        <div id="schedule-body" class="content">
            <p class="title-name"><i class="fa fa-calendar"></i>Schedule
                <span style="float:right"><a href="/channel_{{ $channel['id'] }}/timeline" style="font-size:20pt; text-decoration:underline">Timeline</a></span>
            </p>

        <table width='96%' align='center' style='border-bottom:1px solid #aaa;'><tr>
            <td class="addShowHeader">Show Description</td>
            <td colspan=2 class="addShowTimeHeader">Show Timeline</td>
            <td></td>
            </tr>
            <tr><td colspan=4 style='border-bottom:1px solid #777;'></td></tr> 
            <tr>
            <td>
                <table cellspacing=5 style='font-size:8pt;font-family:tahoma;margin-bottom:15px;'>
                    <tr><td style='width:100px;padding-bottom:10px;'>Name*: </td>
                        <td><input type="text" required id="show_name" name="show_name" value=""></td>
                    </tr>
                    <tr><td style='width:100px;padding-bottom:10px;'>URL: </td>
                        <td><input type="text" id="show_url" name="show_url" value=""></td>
                    </tr>
                    <tr><td style='width:100px;padding-bottom:10px;'>Genre: </td>
                        <td><input type="text" id="show_genre" name="show_genre" value=""></td>
                    </tr>
                </table>
            </td>

            <td>
                <table cellspacing=5 style='font-size:8pt;font-family:tahoma;'>
                    <tr><td class="addShowTimeTitle">Date/Time Start: </td></tr>
                    <tr><td class="input-group date form_datetime" onchange="dateChanged()" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input1">
                        <input id = "startDate" class="form-control" id="from" name="from" type="text" value="{{ $time }}" readonly>
                        <span class="input-group-addon"><i id="cal" class="fa fa-calendar"></i></span>
                    </td></tr>
                </table>
            </td>

            <td>
                <table cellspacing=5 style='font-size:8pt;font-family:tahoma;'>
                    <tr><td style='width:100px;'>Date/Time End: </td>
                        <td id="runTime" class="addShowDuration">{{ $time }}</td>
                    </tr>
                    <tr><td style='width:100px;'>Duration: </td>
                        <td id="runDuration" class="addShowDuration">00:00:00</td>
                    </tr>
                    <tr><td style='width:100px;'>Timezone: </td>
                        <td class="addShowDuration">{{ $timezone }}</td>
                    </tr>
                </table>
            </td>

            <td>
                <table cellspacing=5><tr><td>                            
                    <div class="save"><input type="submit" id="save_schedule" name="save_schedule" onclick="saveSchedule()" value="Save"></div>
                </td></tr></table>
            </td>

        </tr></table>


            <div class="scheduler">
                <div id="treeBox" class="col-md-3"></div>
                <div id="scheduler_here" class="dhx_cal_container col-md-9" style='float:right;'> <!-- height removed from class -->
                <div class="height">
                <div class="addShowContainer1">

                <table><tr><td>
                    <div class="addShowDescription1">
                        <div class="addShowHeader">Scheduled Contents</div>
                        <div class="row center-block list content_list" id="container_content" style="height: calc(100% - 10px)!important;">
                            <div class="col-md-12 searchHide height" id="scheduleContents"></div>
                        </div>
                    </div>
                </td><td valign='top'>
<!--
                    <div class="addShowTime1">
                        <div class="addShowHeader">Show Description</div>
                        <div class="addShowBlock">
                            <div class="vspace">
                                <div class="addShowTitle">Name*: </div>
                                <div><input type="text" required id="show_name" name="show_name" value=""><br></div>
                            </div>
                            <div class="vspace">
                                <div class="addShowTitle">URL: </div>
                                <div><input type="text" id="show_url" name="show_url" value=""><br></div>
                            </div>
                            <div class="vspace">
                                <div class="addShowTitle">Genre: </div>
                                <div><input type="text" id="show_genre" name="show_genre" value=""><br></div>
                            </div>
                        </div>
                        <div class="addShowTimeHeader">Show Timeline</div>
                        <div class="addShowTimeBlock">
                            <div style="margin-bottom: 10px;">
                                <div class="addShowTimeTitle">Date/Time Start: </div>

                                <div class="input-group date form_datetime" onchange="dateChanged()" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input1">
                                    <input id = "startDate" class="form-control" id="from" name="from" type="text" value="{{ $time }}" readonly>
                                    <span class="input-group-addon"><i id="cal" class="fa fa-calendar"></i></span>
                                </div>

                            </div>
                            <div class="addShowTimeTitle1">Date/Time End: </div>
                            <div id="runTime" class="addShowDuration">{{ $time }}</div>
                            <div class="addShowTimeTitle1">Duration: </div>
                            <div id="runDuration" class="addShowDuration">00:00:00</div>
                            <div class="addShowTimeTitle1">Timezone: </div>
                            <div class="addShowDuration">{{ $timezone }}</div>
                            <div class="save"><input type="submit" id="save_schedule" name="save_schedule" onclick="saveSchedule()" value="Save"></div>
                        </div>
                    </div>
-->
                </td></tr></table>
                </div>
                </div>
                </div>
            </div>
        </div>
         <!-- Modal -->
        <div id="infoModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Schedule Time</h4>
              </div>
              <div class="modal-body">
                <div id="modalContent"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
              </div>
            </div>

          </div>
        </div>
    </div>
<script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        forceParse: 0,
        minuteStep: 1
    });
</script>
@overwrite
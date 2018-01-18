@extends('template.template')

@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" charset="utf-8">
        var scheduledVideos = [];
        var tree = window.tree;
        $(document).ready(function () {

            tree = new dhtmlXTreeObject("treeBox", "100%", "100%", 0);
            tree.enableDragAndDrop(true);
            tree.setSkin("dhx_terrace");
            tree.attachEvent("onDrag", function () {
                return false;
            });
            tree.setImagePath("{{ asset('css/schedule/terrace/imgs/dhxtree_terrace/') }}/");
            tree.setOnDblClickHandler(tondblclick);

            var json_object = {
                id: 0,
                item: []
            }

            json_object.item.push({
                id: 2000000,
                text: "Videos",
                item: [
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
                    {{     "{name: 'item_type', content: 'video'}," }}
                    {{     "{name: 'thumbnail', content: '$video->thumbnail_name'}," }}
                    {{     "{name: 'duration', content: '$video->duration'}" }}
                    {{ "]}," }}
                    @endif
                    @endforeach
                ]
            });

            json_object.item.push({
                id: 3000000,
                text: "Categories",
                item: [
                    @foreach($collections as $video)
                    <?php $v = addslashes($video->title);  ?>
                    @if(1==1)
                    {{ '{' }}
                    {{ "id: $video->id*3000000, text:'$v'," }}
                    {{ "item: $video->videosIncollections," }}
                    {{ "child: 1," }}
                    {{ "userdata: [" }}
                    {{     "{name: 'id', content: '$video->id'}," }}
                    {{     "{name: 'drop_item_type', content: 'video'}," }}
                    {{     "{name: 'item_type', content: 'category'}," }}
                    {{     "{name: 'thumbnail', content: '$video->thumbnail_name'}," }}
                    {{     "{name: 'duration', content: ''}" }}
                    {{ "]}," }}
                    @endif
                    @endforeach
                ]
            });

            tree.loadJSONObject(json_object, function () {
            });

            if (window.location.href.indexOf("newScheduleDate") != -1) {
                if ("{{ $astatus }}" != "available") {
                    document.getElementById('modalContent').innerHTML = "Selected time slot overlaps with another schedule. Default date selected.";
                    $('#infoModal').modal('toggle');
                } else {
                    document.getElementById('modalContent').innerHTML = "Selected time slot is available. Slot duration = " + secondsToTime({{ $availableTime }});
                    $('#infoModal').modal('toggle');
                }
            } else if (window.location.href.indexOf("editSchedule") != -1) {
                document.getElementById('show_genre').value = "{{ $genere }}";
                document.getElementById('show_url').value = "{{ $url }}";
                document.getElementById('show_name').value = "{{ $name }}";
                document.getElementById('startDate').disabled = true;
                document.getElementById('cal').disabled = true;
                var str = "{{ $videoList }}";
                var video_id = str.split(",");
                for (var i = 0; i < video_id.length; i++) {
                    if (video_id[i] > 0) tondblclick(video_id[i] * 2000000);
                    else if (video_id[i] < 0) tondblclick(-video_id[i] * 3000000);
                }
            } else {
                document.getElementById('modalContent').innerHTML = "Default schedule time is set to end time of last schedule. You can edit this.";
                $('#infoModal').modal('toggle');
            }

            window.setTimeout('X()', 1000);

            $("#scheduleContents").sortable();
            $("#scheduleContents").disableSelection();
        });

        function X() {
            ht = $('#scheduler_here').height();
            document.getElementById('treeBox').style.height = ht + 'px';
            window.setTimeout('X()', 3000);
        }

        function generateMRSS() {
            $.ajax({
                url: "mrssForSchedule",
                type: "POST",
                async: true,
                data: {"scheduleId": {{$sid}} },
                dataType: "html",
                success: function (data) {
                    if (data != 'error') alert('mrss Feed generated successfully. ' + data);
                    else alert('mrss Feed generation failed');
                }
            });
        }

        function tondblclick(id) {
            if (id.toString().indexOf("1_") != -1 || id.toString().indexOf("1000000_") != -1 || id.toString().indexOf("2000000_") != -1
                    || id == 1 || id == 2000000 || id == 1000000)
                return;

            var video_list;
            var video_id = tree.getUserData(id, 'id');
            if (typeof video_id == 'undefined')
                return;

            var duration = tree.getUserData(id, 'duration');
            var type = tree.getUserData(id, 'drop_item_type');//video, collection, playlist
            var label = tree.getItemText(id);
            var item_type = tree.getUserData(id, 'item_type');
            var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
            var divId = randLetter + Date.now();
            var secID = divId + "section";
            var durID = divId + "duration";
            var thumbnail = tree.getUserData(id, 'thumbnail');
            var img;
            if (thumbnail != "")
                img = '<img src="' + thumbnail + '" class="thumbnail_video">';
            else if (item_type == 'category')
                img = 'Category';
            else
                img = 'In process ...';

            if ((Number(timeToSeconds(document.getElementById('runDuration').innerHTML)) + Number(duration)) > {{ $availableTime }}) {
                var aTime = {{ $availableTime }} -Number(timeToSeconds(document.getElementById('runDuration').innerHTML));
                document.getElementById('modalContent').innerHTML = "Cannot add this video. Total duration is exceeding avilable time. Time left = " + secondsToTime(aTime);
                $('#infoModal').modal('toggle');
                return;
            }

            $('#scheduleContents').append('<section id=' + secID + ' data-video_id=' + video_id + ' class="list_item section_video" style="position: relative;">' +
                    '<button id=' + divId + ' onclick="onDeletePressed(this)" class="delete_video editDelete fr btn btn-block btn-lg btn-danger" title="Remove video">' +
                    '<span class="fui-trash"></span>' +
                    '</button>' +
                    '<div class="clear"></div>' +
                    '<div class="row center-block">' +
                    '<div class="col-md-2">' +
                    img +
                    '</div>' +
                    '<div class="col-md-8">' +
                    '<h1 class="scheduleVideoTitle">' + label + '</h1>' +
                    '<span class="duration">' +
                    '<img src="/images/time_icon.png" style="margin-top: -4px;"><div id=' + durID + ' style="display:inline">' + secondsToTime(duration) +
                    '</div></span>' +
                    '</div>' +
                    '<div class="col-md-2"></div>' +
                    '</div>' +
                    '</section>');

            if (item_type == 'category')
                scheduledVideos.push(-video_id + "$$$" + secID);
            else
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
        }
        ;

        function secondsToTime(secs) {
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
            var mm = (date.getMonth() + 1).toString(); // getMonth() is zero-based
            var dd = date.getDate().toString();

            var h = date.getHours();
            var m = date.getMinutes();
            var s = date.getSeconds();
            // add a zero in front of numbers<10
            h = checkTime(h)
            m = checkTime(m);
            s = checkTime(s);
            return yyyy + '-' + (mm[1] ? mm : "0" + mm[0]) + '-' + (dd[1] ? dd : "0" + dd[0]) + ' ' + h + ':' + m + ':' + s;
        }

        function checkTime(i) {
            return (i < 10) ? "0" + i : i;
        }

        function onDeletePressed(item) {
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
                scheduledVideos.splice(index, 1);
            }

            return (elem = document.getElementById(item.id + "section")).parentNode.removeChild(elem);
        }

        function dateChanged() {
            method = "post"; // Set method to post by default if not specified
            path = ace.path('newScheduleDate');
            params = {date: document.getElementById('startDate').value};
            var form = document.createElement("form");
            form.setAttribute("method", method);
            form.setAttribute("action", path);

            for (var key in params) {
                if (params.hasOwnProperty(key)) {
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

        function saveSchedule() {
            var name = document.getElementById('show_name').value;
            if (name == "") {
                alert('Please enter show name.');
                document.getElementById('show_name').style.borderColor = "red";
                return;
            }
            if (scheduledVideos == null) {
                alert('Please add schedule content to continue.')
                return;
            }
            if (scheduledVideos.length === 0) {
                alert('Please add schedule content to continue.')
                return;
            }
            var genere = document.getElementById('show_genre').value;
            var url = document.getElementById('show_url').value;
            var start = document.getElementById('startDate').value;
            var end = document.getElementById('runTime').innerHTML;
            var sid = 0;
            method = "post";
            path = ace.path('saveSchedule');
            if (window.location.href.indexOf("editSchedule") != -1) {
                path = ace.path('editSaveSchedule');
                sid = {{ $sid }};
            }
            params = {
                showName: name,
                scheduledVideosList: scheduledVideos,
                showGenere: genere,
                showUrl: url,
                showStart: start,
                showEnd: end,
                scheduleId: sid
            };
            var form = document.createElement("form");
            form.setAttribute("method", method);
            form.setAttribute("action", path);

            for (var key in params) {
                if (params.hasOwnProperty(key)) {
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
        <div class="col-md-12" id="schedule">
            <div id="schedule-body" class="content panel panel-success">
                <div class="container-fluid">
                    <div class="scheduleTitle">
                        <span>
                            <i class="fa fa-calendar"></i>Schedule
                        </span>
                    </div>
                    <div class="timelineTitle">
                        <span>
                            <a href="/channel_{{ $channel['id'] }}/timeline">Timeline</a>
                        </span>
                    </div>
                </div>
                <div class="panel panel-success container-fluid filterable col-md-12 col-sm-12 col-lg-12 col-xs-12">
                    <div class="container">
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                            <div class="col-md-4 col-sm-14 col-lg-4 col-xs-4">
                                <div class="panel-heading">
                                    <p class="addShowHeader panel-title">Show Description</p>
                                </div>
                                <table class="table table-striped form-horizontal">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <label class="col-sm-2 control-label">Name*: </label>
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" required="" id="show_name"
                                                   name="show_name" value="" placeholder="Name">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="col-sm-2 control-label">Live URL</label>
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" id="show_url" name="show_url"
                                                   value=""
                                                   placeholder="URL">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="col-sm-2 control-label">Genre</label>
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" id="show_genre" name="show_genre"
                                                   value="" placeholder="Genre">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-4 col-sm-14 col-lg-4 col-xs-4">
                                <div class="panel-heading">
                                    <p class="addShowTimeHeader panel-title">Show Timeline</p>
                                </div>
                                <table cellspacing=5>
                                    <tr>
                                        <td class="addShowTimeTitle">Date/Time Start:</td>
                                    </tr>
                                    <tr>
                                        <td class="input-group date form_datetime" onchange="dateChanged()"
                                            data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input1">
                                            <input id="startDate" class="form-control" id="from" name="from" type="text"
                                                   value="{{ $time }}" readonly>
                                            <span class="input-group-addon"><i id="cal"
                                                                               class="fa fa-calendar"></i></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-4 col-sm-14 col-lg-4 col-xs-4">
                                <table cellspacing="5" class="table table-bordered">
                                    <tr>
                                        <td>Date/Time End:</td>
                                        <td id="runTime" class="addShowDuration">{{ $time }}</td>
                                    </tr>
                                    <tr>
                                        <td>Duration:</td>
                                        <td id="runDuration" class="addShowDuration">00:00:00</td>
                                    </tr>
                                    <tr>
                                        <td>Timezone:</td>
                                        <td class="addShowDuration">{{ $timezone }}</td>
                                    </tr>
                                </table>
                                <div class="save">
                                    <input class="btn btn-success" type="submit" id="save_schedule" name="save_schedule"
                                           onclick="saveSchedule()" value="Save"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container-fluid filterable col-md-12 col-sm-12 col-lg-12 col-xs-12 scheduler">
                    <div id="treeBox" class="col-md-5 text-left"></div>
                    <div id="scheduler_here" class="dhx_cal_container col-md-7 col-sm-7 col-lg-7 col-md-7 text-right">
                        <div class="addShowContainer1">
                            <div class="addShowDescription1">
                                <div class="addShowHeader">Scheduled Contents</div>
                                <div class="row center-block list content_list" id="container_content">
                                    <div class="searchHide" id="scheduleContents"></div>
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
                    todayBtn: 1,
                    autoclose: 1,
                    todayHighlight: 0,
                    startView: 2,
                    forceParse: 0,
                    minuteStep: 1
                });
            </script>
@overwrite
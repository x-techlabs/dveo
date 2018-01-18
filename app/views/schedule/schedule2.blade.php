@extends('template.template')

@section('content')

<script type="text/javascript" >
    var schedulesavebound = false;
    //DVEO

    var scheduledClones = [];
    var clone_index_id = 0;

    $(function(){

        $('.dragbox2').each(function(){
            $(this).hover(function(){
                $(this).find('h2').addClass('collapsex');
            }, function(){
                $(this).find('h2').removeClass('collapsex');
            })
            .find('h2').hover(function(){
                $(this).find('.configure').css('visibility', 'visible');
            }, function(){
                $(this).find('.configure').css('visibility', 'hidden');
            })
            .click(function(){
                $(this).siblings('.dragbox2-content').toggle();
            })
            .end()
            .find('.configure').css('visibility', 'hidden');

            ///sd
            var duration = secondsTimeSpanToHMS($(this).find('#duration').val());
            $(this).find('p').empty();
            $(this).find('p').append('duration: ' + duration);

            //$('#timepckr').val('12:00am');
            $('#timepckr').timepicker('setTime', new Date());

        });



        $('.columnx').sortable({
            connectWith: '.columnx',
            handle: 'h2',
            cursor: 'move',
            placeholder: 'placeholder',
            forcePlaceholderSize: true,
            opacity: 0.4,
            helper: 'clone',
            //appendTo: 'body',
            stop: function(event, ui){

                if (!ui.sender) {
                    //ui.sender = 'once';
                    goStop(event, ui);

                }

            }
        }).disableSelection();


        //prevents drag from columnxSchedule
        $( "#columnxSchedule" ).sortable({
            connectWith: ".notexisting"
        }).disableSelection();

//        $( ".columnx" ).bind( "sortupdate", function(event, ui) {
//            if (!ui.sender) {
//                ui.sender = 'once';
//                goStop(event, ui);
//            }
//        });

        $('#timepckr').on('changeTime', function() {
            $('#timeline').text($('#timepckr').val());
        });

    });


    function goStop(event, ui){

//        $(ui.itemx).find('h2').click();
        var sortorder = '';

        $('.columnx').each(function () {
            var itemorder = $(this).sortable('toArray');
            var columnId = $(this).attr('id');
            sortorder += columnId + '=' + itemorder.toString() + '&';

            //alert(sortorder);

            if (columnId == 'columnxPlaylists') {
                var lines = itemorder.toString().split(',');
                $.each(lines, function (key, line) {
                    var duration = secondsTimeSpanToHMS($('#' + line).find('#duration').val());

                    $('#' + line).find('p').empty();
                    $('#' + line).find('p').append('duration: ' + duration);
                });
            }

            if (columnId == 'columnxVideos') {
                var lines = itemorder.toString().split(',');
                $.each(lines, function (key, line) {

                    var duration = secondsTimeSpanToHMS($('#' + line).find('#duration').val());

                    $('#' + line).find('p').empty();
                    $('#' + line).find('p').append('duration: ' + duration);
                });
            }

            if (columnId == 'columnxSchedule') {

                //current time to start
                var date = new Date();
                var now_time = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
                //var now_time = date.getTime();
                //var now_time_sec = millisecToSeconds(date.getTime());

                //given time to start
                //var now_time = '18:00:00';

                var now_time_sec = hmsToSecondsOnly(now_time);
                var currentTimeLine = 0;
                var lines = itemorder.toString().split(',');



                var count = 0;//lines.length;




                var item = ui.item;
                //var item = $('#' + line);

                var type = item.find('#type').val();
                var pane = item.find('#pane').val();
                var id = item.find('#id').val();

                if(pane != 'columnxSchedule') {

                }


                $.each(lines, function (key, line) {

                    //var cl = item.clone(true,true);
                    $( "#columnxPlaylists" ).sortable( "cancel" );
                    $( "#columnxVideos" ).sortable( "cancel" );

                    //alert(id+' - '+$('#' + line).find('#id').val());
                    //alert(pane);
                    //
                    if(count >0 || pane == 'columnxSchedule') {

                        $( "#columnxSchedule" ).each(function(inx, item) {
                            //alert($(item).find('#id').val()+' - '+$(this).find('#end_time').val());
                        });
                        return true;
                    }
                    count++;

                    var cl = get_ui_item_Clone(item, type, now_time_sec, currentTimeLine);
                    $("#columnxSchedule").append(cl);

                    scheduledClones.length = 0;
                    var curr_start = now_time_sec;
                    $( "#columnxSchedule").children().each(function() {
                        // alert($(this).find('#id').val()+' - '+$(this).find('#index_id').val()+' - '+$(this).find('#end_time').val());
                        curr_start = reculculate_time_by_order(scheduledClones, $(this), curr_start);
                    });

                });


                if(pane == 'columnxSchedule') {

                    scheduledClones.length = 0;
                    var curr_start = now_time_sec;
                    $( "#columnxSchedule").children().each(function() {
                        curr_start = reculculate_time_by_order(scheduledClones, $(this), curr_start);
                    });
                }

            }

        });

        //alert('SortOrder: '+sortorder);
        /*Pass sortorder variable to server using ajax to save state*/
    }

    function reculculate_time_by_order(clone_arr, this_obj, curr_start){

        var start_time_sec = hmsToSecondsOnly( this_obj.find('#start_time').val().split(" ",1)+'' );

        if(curr_start > start_time_sec) {
            start_time_sec = curr_start;
        }

        var start_time = secondsTimeSpanToAM(curr_start);
        this_obj.find('#start_time').val(start_time);
        var end_time = secondsTimeSpanToAM(curr_start + hmsToSecondsOnly(this_obj.find('#duration').val().split(" ",1)+'' ));
        this_obj.find('#end_time').val(end_time);
        this_obj.find('p').empty();
        this_obj.find('h2').attr('display','inline');
        this_obj.find('p').append('&nbsp;&nbsp;'+start_time+' - '+end_time+'</p>');


        clone_arr.push(this_obj);

        return hmsToSecondsOnly(this_obj.find('#end_time').val());

    }

    function get_ui_item_Clone(item, type, now_time_sec, currentTimeLine){

        var cl = item.clone(true,true);

        cl.find('#index_id').val(clone_index_id++);

        cl.find('#type').val(type);
        cl.find('#pane').val('columnxSchedule');

        //alert('pane: '+pane+', type: '+type+' item id: '+id+' cl id: '+cl.find('#id').val());

        var picker_time = ConvertTimeformat('00:00', $('#timepckr').val()) + ':00';
        //alert(picker_time);
        //var start_time_sec = dateToSec($('#'+line).find('#start_time').val());
        var start_time_sec = hmsToSecondsOnly(picker_time);


        //alert(start_time_sec+' - '+now_time_sec);
        if (start_time_sec < now_time_sec) {
            start_time_sec = now_time_sec;
        }
        //alert(start_time_sec+' '+currentTimeLine);

        if (currentTimeLine > 0) start_time_sec = currentTimeLine;
        else currentTimeLine = start_time_sec;

        currentTimeLine += hmsToSecondsOnly(cl.find('#duration').val());

        //alert(currentTimeLine);

        //var start_date = new Date(start_time_sec*1000);
        ////var start_time = start_date.getHours() + ":" + start_date.getMinutes() + ":" + start_date.getSeconds();
        //var start_time = getHMS(start_date.getHours(), start_date.getMinutes(), start_date.getSeconds());
        var start_time = secondsTimeSpanToHMS(start_time_sec);
        cl.find('#start_time').val(start_time);
        start_time = secondsTimeSpanToAM(start_time_sec);
        //alert(start_time);

        //var end_date = new Date(currentTimeLine*1000);
        ////var end_time = end_date.getHours() + ":" + end_date.getMinutes() + ":" + end_date.getSeconds();
        //var end_time = getHMS(end_date.getHours(), end_date.getMinutes(), end_date.getSeconds());
        var end_time = secondsTimeSpanToHMS(currentTimeLine);
        cl.find('#end_time').val(end_time);
        end_time = secondsTimeSpanToAM(currentTimeLine);



        var title = cl.find('#title').val();

        cl.find('h2').empty();
        cl.find('h2').append('&nbsp;&nbsp;'
                             +'<div class="inlineB">'
                             +'<input type="checkbox" id="chkbox" name="chkbox" onclick="event.stopPropagation();">'
                             +'&nbsp;'
                             +'<span id="del'+cl.find('#index_id').val()+'" class="ui-icon ui-icon-close" onclick="closeEvent(event, '+cl.find('#index_id').val()+');"></span>'
                             +'&nbsp;'
                             +title+'&nbsp;<p style="display: inline">&nbsp;&nbsp;'+start_time+' - '+end_time+'</p>'
                             +'</div>');

        cl.find('h2').css('text-align','left');

//        cl.find('#chkbox').click(function(e){
//            e.stopPropagation();
//        });

//        cl.find('.ui-icon').click(function(e){
//            alert('dd');
//            e.stopPropagation();
//        });




        return cl;
    }

    function closeEvent(e, index_id){
        e.stopPropagation();
        if (confirm('Delete event?')) {
            $('#columnxSchedule').find('#del'+index_id).parent().parent().parent().remove();
        }
    }

    $('.ui-icon-close').click(function(){

        var isGood=confirm('Dialogue');
        if (isGood) {
            alert('true');
        } else {
            alert('false');
        }

    });


    $(function() {
        $( "#daypicker" ).datepicker({ minDate: -20, maxDate: "+1M +10D" });
        $("#daypicker").datepicker('setDate', new Date());
    });

    $(function() {
        $( "#tabs" ).tabs();
        $( "#tabschedule").hide();
        $( "#timeline").hide();
        $( "#tab_days").hide();

        $( "#div_daypicker").hide();


        $("#tabs").tabs({
            activate: function (event, ui) {
                var active = $('#tabs').tabs('option', 'active');

                if(active==0){ $( "#tab_setup").show(); $( "#tab_setup").show();}
                if(active==1){$("#tabschedule").show(); $("#timeline").show(); $( "#div_daypicker").show();}
                else{$("#tabschedule").hide(); $("#timeline").hide(); $( "#div_daypicker").hide();}
                if(active==2){$("#tab_days").show();}else{$("#tab_days").hide();}

            }
        });

        $( "#tab_schedule" ).tabs();


    });


    //////////////// Setup ////////////////////////

    $(function() {
        $( "#from" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3,
            modal: true,
            onClose: function( selectedDate ) {
                $( "#to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3,
            onClose: function( selectedDate ) {
                $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });

    ///////////////////////////////////////////////



    //Days Tab
    function scheduleDay(date) {

        $('#tabs').tabs({active: 1});
        $("#daypicker").datepicker('setDate', date);

    }


</script>

<div class="row center-block height" id="contnet-wrap">
    <div class="col-md-12 height" id="schedule">
        <div id="schedule-body" class="height content">
            <p class="title-name"><i class="fa fa-calendar"></i>Schedule</p>
            <div class="scheduler">

                <div id="tabs" class="tabs-wrapper">

                    <ul>
                        <li><a href="#tab_setup">Add Show</a></li>
                        <li>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;</li>

                        <li><a href="#tab_schedule">Schedule</a></li>
                        {{--<li><a href="#tabs-1">Playlists</a></li>--}}
                        {{--<li><a href="#tabs-2">Collections</a></li>--}}
                        {{--<li><a href="#tabs-3">Videos</a></li>--}}

                        <li>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;</li>
                        <li><a href="#tab_days">Days</a></li>
                        <li><a href="#tab_weeks">Weeks</a></li>
                        <li><a href="#tab_months">Months</a></li>
                        <li><a href="#tab_years">Years</a></li>
                    </ul>

                    <div id="tab_setup" style="width:100%;">
                        <div>
                            {{--<h3>Channel3</h3>--}}
                            {{--Stream name: <input type="text" id="strem_name" name="strem_name"><br>--}}

                            <div class="addShowContainer">

                                <div class="addShowDescription">

                                    <div class="addShowHeader">Show Description</div>

                                    <div class="addShowBlock">
                                        <div class="addShowTitle">Name: </div>
                                        <div><input type="text" id="show_name" name="show_name" value=""><br></div>
                                        <div class="addShowTitle">URL: </div>
                                        <div><input type="text" id="show_url" name="show_url" value=""><br></div>
                                        <div class="addShowTitle">Genre: </div>
                                        <div><input type="text" id="show_genre" name="show_genre" value=""><br></div>
                                    </div>
                                </div>

                                <div class="addShowTime">

                                    <div class="addShowTimeHeader">Show Timeline</div>

                                    <div class="addShowTimeBlock">
                                        <div class="addShowTimeTitle">Date/Time Start: </div>
                                        <div><input type="text" id="from" name="from"><br></div>
                                        <div class="addShowTimeTitle">Date/Time End: </div>
                                        <div><input type="text" id="to" name="to"><br></div>
                                        <div class="addShowTimeTitle">Duration: </div>
                                        <div class="addShowDuration">00:00:00<br></div>
                                        <div class="addShowTimeTitle">Temezone: </div>
                                        {{--http://www.jqueryscript.net/time-clock/Easy-Timezone-Picker-with-jQuery-Moment-js-Timezones.html--}}
                                        <div class="addShowDuration"><br></div>
                                        <div class="addShowTimeTitle">Repeats? </div>
                                        <div><input type="checkbox" id="addShowChx" name="addShowChx"></div>
                                    </div>
                                </div>



                            </div>

                            {{--<br><br>--}}
                            {{--Current Channel lifetime: 24/06/2015 - 24/07/2015<br>--}}
                            {{--Set new Channel lifetime: from <input type="text" id="from" name="from"> to <input type="text" id="to" name="to"><br><br>--}}
                            <div class="addShowSave">
                                <input type="submit" id="submit_stream" name="submit_stream" value="Save">
                            </div>
                        </div>
                    </div>

                    <div id="tab_schedule"  style="text-align: center;">


                        {{--<script>--}}
                            {{--//http://jonthornton.github.io/jquery-timepicker/--}}
                            {{--//$('#timepckr').timepicker();--}}
                            {{--$('#timepckr').timepicker({'scrollDefault': 'now'});--}}

                            {{--$('#setNowTimeButton').on('click', function (){--}}
                                {{--$('#timepckr').timepicker('setTime', new Date());--}}
                            {{--});--}}
                        {{--</script>--}}


                        {{--<div id="div_daypicker" style=" float: left; text-align: left; width: 100%; height: 100%">--}}

                            {{--Pick another day: <input type="text" id="daypicker" size="8" style="text-align: center"/>--}}
                            {{--Pick start time: <input id="timepckr" type="text" size="6" style="text-align: center"/>--}}
                            {{--<button id="setNowTimeButton" style="line-height: 25px; height: 29px; vertical-align: middle;">as current time</button>--}}
                        {{--</div>--}}
                        {{--<div id="div_timepicker" style="text-align: center;">--}}
                        {{--</div>--}}

                        <div style=" margin: 5px; width: 475px; float: left">

                            <div style="text-align: right; width: 100%; margin-bottom: 5px">
                                <button>Add Show</button>
                            </div>

                            <div style="border:2px solid #bfbfbf;">

                                <div>
                                    <fieldset style="border: hidden">
                                        <button id="bplaylist">Playlist</button>
                                        <button id="bvideos">Videos</button>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label for="speed"></label>
                                        <select name="speed" id="speed">
                                            <option>Collection 1</option>
                                            <option>Collection 2</option>
                                            <option selected="selected">Collection 3</option>
                                            <option>Collection 4</option>
                                            <option>Collection 5</option>
                                        </select>
                                    </fieldset>
                                </div>

                                <div class="columnx" id="columnxPlaylists">
                                    @foreach($playlists as $playlist)
                                        <div class="dragbox2" id="item_playlist_{{ $playlist->id }}" >
                                            <input type="hidden" id="id" value="{{ $playlist->id }}">
                                            <input type="hidden" id="title" value="{{ $playlist->title }}">
                                            <input type="hidden" id="duration" value="{{ $playlist->duration }}">
                                            <input type="hidden" id="start_time" value="{{ $playlist->start_time }}">
                                            <input type="hidden" id="end_time">
                                            <input type="hidden" id="type" value="playlist">
                                            <input type="hidden" id="pane" value="columnxPlaylists">
                                            <input type="hidden" id="index_id" value="0">
                                            <h2>{{ $playlist->title }}&nbsp;&nbsp;<p style="display: inline"></p></h2>
                                            <div class="dragbox2-content" >
                                                <div class="col-md-2">
                                                    <img src="{{ $playlist->thumbnail_name }}" class="thumbnail_video" height="100%">
                                                </div>
                                                {{ $playlist->description }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="columnx" id="columnxVideos">
                                    @foreach($videos as $video)
                                        <div class="dragbox2" id="item_video_{{ $video->id }}">
                                            <?php ?>
                                                <input type="hidden" id="id" value="{{ $video->id }}">
                                                <input type="hidden" id="title" value="{{ $video->title }}">
                                                <input type="hidden" id="duration" value="{{ $video->duration }}">
                                                <input type="hidden" id="start_time" value="{{ $video->start_time }}">
                                                <input type="hidden" id="end_time">
                                                <input type="hidden" id="type" value="video">
                                                <input type="hidden" id="pane" value="columnxVideos">
                                                <input type="hidden" id="index_id" value="0">
                                                <h2>{{ $video->title }}&nbsp;&nbsp;<p style="display: inline"></p></h2>
                                            <div class="dragbox2-content" >
                                                <div class="col-md-2">
                                                    <img src="{{ $video->thumbnail_name }}" class="thumbnail_video" height="100%">
                                                </div>
                                                {{ $video->description }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <script>
                                    $('#columnxVideos').hide();
                                    $("#bplaylist").click(function(){
                                        $('#columnxPlaylists').show(); $('#columnxVideos').hide();

                                    });
                                    $("#bvideos").click(function(){
                                        $('#columnxVideos').show(); $('#columnxPlaylists').hide();
                                    });
                                </script>
                            </div>
                        </div>

                        <script>

                            function saveScheduleItems(){

                                //$("#columnxSchedule").each(function () {

                                    var scheduledItems = [];

                                    $.each(scheduledClones, function( index, value ) {
                                        //alert(value.find('#id').val() +' - '+value.find('#index_id').val() +' - '+value.find('#end_time').val());

                                        var data = {
                                            'id': value.find('#id').val(),
                                            'title': value.find('#title').val(),
                                            'duration': value.find('#duration').val(),
                                            'start_time': value.find('#start_time').val(),
                                            'end_time': value.find('#end_time').val(),
                                            'type': value.find('#type').val(),
                                            'pane': value.find('#pane').val()
                                        }
                                        scheduledItems.push(data);

                                        $.ajax({
                                            url: "scheduleAddVideos",
                                            type: "POST",
                                            async: true,
                                            data: {scheduledItems: scheduledItems},
                                            dataType: "html",
                                            success: function (data) {
                                                //alert(JSON.stringify(data));
                                            }
                                        });

                                    });


                                //});
                            }

                        </script>


                        {{--<div class="columnx" id="columnxSchedule">--}}
                        {{--</div>--}}
                        {{--<input id="saveschedulebutton" class="btn btn-inverse" type="button" value="Save" onclick="$('.columnx').trigger('schedulesave', { item : 0 });">--}}

                        <div style=" margin: 5px; width: 475px; float: left">

                            <div style="text-align: left; width: 100%; margin-bottom: 5px">
                                <select name="speed" id="speed">
                                    <option>Select 1</option>
                                    <option>Select 2</option>
                                    <option selected="selected">Select 3</option>
                                    <option>Select 4</option>
                                    <option>Select 5</option>
                                </select>
                                <button>Cut</button> <button>Delete</button> <button>Etc..</button>
                            </div>

                            <div style="border:2px solid #bfbfbf;">

                                <div style="height: 62px">
                                    Title - Short Description &nbsp;&nbsp;
                                    Start &nbsp;&nbsp;
                                    End &nbsp;&nbsp;
                                    Duration
                                </div>

                                <div class="columnx" id="columnxSchedule">
                                    <?php $sch_count = 0; ?>
                                    @foreach($schedule_videos as $schedule_video)
                                        <div class="dragbox2" id="item_video_{{ $video->id }}">
                                            <?php
                                                $video= '';
                                                foreach($videos as $vid){
                                                    if($vid->id == $schedule_video->video_id){
                                                        $video = $vid;
                                                        break;
                                                    }
                                                }
                                            ?>
                                            <input type="hidden" id="id" value="{{ $schedule_video->video_id }}">
                                            <input type="hidden" id="title" value="{{ $schedule_video->name }}">
                                            <input type="hidden" id="duration" value="{{ time($schedule_video->end_time) - time($schedule_video->start_time) }}">
                                            <input type="hidden" id="start_time" value="{{ time($schedule_video->start_time) }}">
                                            <input type="hidden" id="end_time" value="{{ time($schedule_video->end_time) }}">
                                            <input type="hidden" id="type" value="{{ time($schedule_video->type) }}">
                                            <input type="hidden" id="pane" value="columnxSchedule">
                                            <input type="hidden" id="index_id" value="0">

                                            <div class="inlineB">
                                                <h2 style="text-align: left; max-width: 400px">
                                                    <input type="checkbox" id="chkbox" name="chkbox" onclick="event.stopPropagation();">
                                                    &nbsp;<span id="del{{ $sch_count; }}" class="ui-icon ui-icon-close" onclick="closeEvent(event, {{ $sch_count++; }});"></span>
                                                    &nbsp;{{ $video->title }}&nbsp;<p style="display: inline">&nbsp;&nbsp;'{{ time($schedule_video->start_time) }} - {{ time($schedule_video->end_time) }}</p>
                                                </h2>
                                            </div>

                                            <div class="dragbox2-content" >
                                                <div class="col-md-2">
                                                    <img src="{{ $video->thumbnail_name }}" class="thumbnail_video" height="100%">
                                                </div>
                                                {{ $video->description }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <script>


                                </script>

                                <div style="text-align: left; width: 100%; margin: 5px">
                                    Time needed to make SHow Block complete
                                </div>

                            </div>

                            <div style="padding-top: 10px">
                                <input id="saveschedulebutton" class="btn btn-inverse" type="button" value="Save" onclick="saveScheduleItems();">
                            </div>
                        </div>

                    </div>


                    {{--<div id="tabs-2">--}}
                        {{--<div class="columnx" id="columnxPlaylists">--}}
                        {{--@foreach($collections as $collection)--}}
                            {{--<div class="dragbox2" id="item_collection_{{ $collection->id }}" >--}}
                                {{--<h2>{{ $collection->title }}</h2>--}}
                                {{--<div class="dragbox2-content" >--}}
                                    {{--<img src="{{ $collection->thumbnail_name }}" class="thumbnail_video">--}}
                                    {{--{{ $collection->description }}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--@endforeach--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div id="tabs-3">--}}
                        {{--<div class="columnx" id="columnxVideos">--}}
                        {{--@foreach($videos as $video)--}}
                            {{--<div class="dragbox2" id="item_video_{{ $video->id }}" >--}}
                                {{--<h2>{{ $video->title }} &nbsp; &nbsp; &nbsp; &nbsp;01:30:32 - 01:35:32</h2>--}}
                                {{--<div class="dragbox2-content" >--}}
                                    {{--<div class="col-md-2">--}}
                                        {{--<img src="{{ $video->thumbnail_name }}" class="thumbnail_video" height="100%">--}}
                                    {{--</div>--}}

                                    {{--{{ $video->description }}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--@endforeach--}}
                        {{--</div>--}}
                    {{--</div>--}}




                </div>


                <div id="tab_days" class="tab_stream_pack">
                    <div id="columnxDays" class="columnx">

                        {{--@foreach($schedule_videos as $schedule_video)--}}
                            {{--<div class="dragbox2" id="item_video_{{ $video->id }}">--}}
                                <?php
                                    //$video = Video::where('id', '=', $schedule_video->video_id)->get();
                                ?>
                                {{--<input type="hidden" id="id" value="{{ $video->id }}">--}}
                                {{--<input type="hidden" id="title" value="{{ $video->title }}">--}}
                                {{--<input type="hidden" id="duration" value="{{ $video->duration }}">--}}
                                {{--<input type="hidden" id="start_time" value="{{ time($schedule_video->start_time) }}">--}}
                                {{--<input type="hidden" id="end_time" value="{{ time($schedule_video->end_time) }}">--}}
                                {{--<input type="hidden" id="type" value="video">--}}
                                {{--<h2>{{ $video->title }}&nbsp;&nbsp;<p style="display: inline"></p></h2>--}}
                                {{--<div class="dragbox2-content" >--}}
                                    {{--<div class="col-md-2">--}}
                                        {{--<img src="{{ $video->thumbnail_name }}" class="thumbnail_video" height="100%">--}}
                                    {{--</div>--}}
                                    {{--{{ $video->description }}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--@endforeach--}}

                        <?php $days=array('24/06/2015 Wednesday','25/06/2015 Thursday', '26/06/2015 Friday', '27/06/2015 Saturday', '28/06/2015 Sunday', '29/06/2015 Monday','30/06/2015 Tuesday');?>
                        <?php $days=array('06/24/2015','06/25/2015', '06/26/2015', '06/27/2015', '06/28/2015', '06/29/2015','06/30/2015');?>
                        @foreach($days as $day)
                            <div id="day_{{ $day }}" class="dragbox2" >
                                <h2> {{ $day }} &nbsp; &nbsp;
                                    {{--<input  type="text" name="day{{ $day }}" class="date_pic" value="{{ $day }}">--}}
                                        <a onclick="scheduleDay('{{ $day }}');" href="#">Edit</a>
                                </h2>
                                <div class="dragbox2-content" >
                                    Video1 02:30:32 - 03:30:30<br>
                                    Video2 03:30:30 - 04:40:30<br>
                                    etc..
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>

                <script>
                    $('#columnxSchedule').css("overflow-y", "scroll");
                    $('#columnxPlaylists').css("overflow-y", "scroll");
                    $('#columnxVideos').css("overflow-y", "scroll");
                    $('#columnxDays').css("overflow-y", "scroll");
                </script>

            </div>
        </div>
    </div>
</div>

@stop
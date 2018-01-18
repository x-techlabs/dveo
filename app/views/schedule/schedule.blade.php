@extends('template.template')

@section('content')

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
        window.tree = new dhtmlXTreeObject("treeBox", "100%", "100%", 0);
        tree.enableDragAndDrop(true);
        tree.setSkin("dhx_terrace");
        tree.attachEvent("onDrag", function() { return false; });
        tree.setImagePath("{{ asset('css/schedule/terrace/imgs/dhxtree_terrace/') }}/");

        var json_object = {
            id:0,
            item:[]
        }


        json_object.item.push({
            id: 1,
            text: "Playlists",
            userdata: [],
            item:[
                @foreach($playlists as $playlist)
                {{ '{' }}
                {{ "id: $playlist->id, text:'$playlist->title'," }}
                {{ "item: null," }}
                {{ "child: 1," }}
                {{ "userdata: [" }}
                {{     "{name: 'id', content: '$playlist->id'}," }}
                {{     "{name: 'drop_item_type', content: 'playlist'}," }}
                {{     "{name: 'duration', content: '$playlist->duration'}" }}
                {{ "]}," }}
                @endforeach
            ]
        });

        json_object.item.push({
            id: 1000000,
            text: "Collections",
            item:[
                @foreach($collections as $collection)
                    <?php
                        $collection_duration=0;
                        $video_ids = Videos_in_collections::where('collection_id', '=', $collection->id)->get();
                        foreach ($video_ids as $video_id) {
                            $video = Video::find($video_id['video_id']);
                            if($video) $collection_duration += $video->duration;
                        }
                    ?>
                    {{ '{' }}
                    {{ "id: $collection->id*1000000, text:'$collection->title'," }}
                    {{ "item: null," }}
                    {{ "child: 1," }}
                    {{ "userdata: [" }}
                    {{     "{name: 'id', content: '$collection->id'}," }}
                    {{     "{name: 'drop_item_type', content: 'collection'}," }}
                    {{     "{name: 'duration', content: '$collection_duration'}" }}
                    {{ "]}," }}
                @endforeach
            ]
        });

        json_object.item.push({
            id: 2000000,
            text: "Uncategorized videos",
            item:[
                @foreach($videos as $video)
                    @if(!Videos_in_collections::where('video_id', '=', $video->id)->first())
                        {{ '{' }}
                        {{ "id: $video->id*2000000, text:'$video->title'," }}
                        {{ "item: null," }}
                        {{ "child: 1," }}
                        {{ "userdata: [" }}
                        {{     "{name: 'id', content: '$video->id'}," }}
                        {{     "{name: 'drop_item_type', content: 'video'}," }}
                        {{     "{name: 'duration', content: '$video->duration'}" }}
                        {{ "]}," }}
                    @endif
                @endforeach
            ]
        });

        tree.loadJSONObject(json_object, function() { });

        console.log(tree);


        scheduler.attachEvent("onEventDropOut", function (id,original, to, e){
            //any custom logic here
            scheduler.render(e);
            scheduler.select(e);
        });


        //key point!
        //convert data from tree in property of event
        //similar approach can be used for any other dhtmlx component
        scheduler.attachEvent("onExternalDragIn", function(id, source, e) {
            var node_id = tree._dragged[0].id;

            var label = tree.getItemText(node_id);
            var event = scheduler.getEvent(id);
            var events = scheduler.getEvents();
            var bool = false;

            function addZero(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }
            function normalizeDate(date) {
                var month = date.getUTCMonth() + 1; //months from 1-12
                var day = date.getUTCDate();
                var year = date.getUTCFullYear();
                var time = addZero(date.getUTCHours()) + ":" + addZero(date.getUTCMinutes()) + ":" + addZero(date.getUTCSeconds());
                return month + "/" + day + "/" + year + " " + time;
            }
            $.ajax({
                url: ace.path('scheduleGetEnd'),
                type: "POST",
                dataType: "json",
                success: function(data) {
                    event.text = label;

                    // find the largest item and push it to the end of the list
                    var sort = function (list) {

                        var comparisons = 0,
                                swaps = 0;

                        for (var i = 0, swapping; i < list.length - 1; i++) {
                            comparisons++;
                            if (list[i].start_date > list[i + 1].start_date) {
                                // swap
                                swapping = list[i + 1];

                                list[i + 1] = list[i];
                                list[i] = swapping;
                                swaps++;
                            };
                        };

                        return list;
                    };

                    if (event.start_date.getTime() < ace.timestamp * 1000) {
                        event.start_date = new Date(ace.timestamp * 1000);

                        var all_events = scheduler.getEvents();
                        var event_index, scheduled_event;

                        all_events = sort(all_events);

                        for (event_index in all_events) {
                            scheduled_event = all_events[event_index];

                            if ((event.start_date >= scheduled_event.start_date && event.start_date <= scheduled_event.end_date) ||
                                    (event.end_date >= scheduled_event.start_date && event.end_date <= scheduled_event.end_date)) {
                                event.start_date = scheduled_event.end_date;
                            }
                        }
                    }

                    event.end_date = new Date(event.start_date.getTime() + tree.getUserData(node_id, 'duration') * 1000);

                    console.log(event.start_date);

                    if(data.bool) {
                        startEndDate = {};
                        startEndDate.start_date = normalizeDate(event.start_date);
                        startEndDate.end_date = normalizeDate(event.end_date);

                        $.ajax({
                            url: ace.path('scheduleAdd'),
                            type: "POST",
                            data: {
                                drop_item_id: tree.getUserData(node_id, 'id'),
                                drop_item_type: tree.getUserData(node_id, 'drop_item_type'),
                                event: event,
                                date: startEndDate
                            },
                            dataType: "json",
                            success: function(data) {
                                $.each(data, function(i, object) {
                                    if(i='schedule'){
                                        $.each(object, function(k, v) {
                                            if(k=='id'){
                                                //event.id = v;
                                                event.room = v;
                                            }
                                        });
                                    }
                                });
                                console.log(data);
                            }
                        });
                    } else {
                        return false;
                    }
                }
            });

            return true;
        });

        scheduler.attachEvent("onEventAdded", function(id,ev){
//            location.reload();
            var event = scheduler.getEvent(id);
            //alert(event.room);
        });
        scheduler.attachEvent("onFullSync", function(){

        });

        scheduler.attachEvent("onBeforeEventDelete", function(id,e){
            var event = scheduler.getEvent(id);

            $.ajax({
                url: ace.path('deleteEvent'),
                type: "POST",
                data: {
                    schedule_id: event.room
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                }
            });

            return true;
        });

//        scheduler.config.details_on_create = false;
//        scheduler.config.details_on_dblclick = false;
//
//        scheduler.config.time_step = 1;

        if(localStorage.getItem('channel_' + {{ $channel_id }} + '_range') !== null) {
            scheduler.config.hour_size_px = 44 * localStorage.getItem('channel_' + {{ $channel_id }} + '_range');
            $("[type=range]").val(localStorage.getItem('channel_' + {{ $channel_id }} + '_range'));
        } else {
            scheduler.config.hour_size_px = 44;
        }

        //scheduler.config.readonly = false;

        var date = new Date();
        var month = date.getUTCMonth() + 1; //months from 1-12
        var day = date.getUTCDate();
        var year = date.getUTCFullYear();
        var newdate = month + "/" + day + "/" + year + " ";

        scheduler.config.separate_short_events = false;
        //scheduler.config.container_autoresize = true;
        //scheduler.config.event_duration = 60;
        //scheduler.config.auto_end_date = true;
        //scheduler.config.all_timed = false;

        var format = scheduler.date.date_to_str("%H:%i:%s");
        var step = 0.559;//0.017;

        scheduler.templates.hour_scale = function(date){
            html="";
            for (var i=0; i<60/step; i++){
                html+="<div style='height:21px;line-height:21px;'>"+format(date)+"</div>";
                date = scheduler.date.add(date,step,"minute");
            }
            return html;
        }

        scheduler.config.hour_size_px = 44 * 52;

        scheduler.init('scheduler_here', date, "day");

        var events = [];

        @foreach($schedules as $schedule)
            events.push({
                id: {{ $schedule->id }},
                room: {{ $schedule->id }},
                text: "{{ $schedule->name }}",
                start_date: "{{ Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_date, 'UTC')->setTimezone('Asia/Yerevan')->format('m/d/Y H:i:s') }}",
                end_date: "{{ Carbon::createFromFormat('Y-m-d H:i:s', $schedule->end_date, 'UTC')->setTimezone('Asia/Yerevan')->format('m/d/Y H:i:s') }}"
            });
        @endforeach

        scheduler.parse(events, "json");

        var loaderInterval = setInterval(function(){
            if($('.showLoader').attr('style') == "display: block;") {
                $('.dhx_cal_tab.active').click();
                clearInterval(loaderInterval);
            }
        }, 100);

        $("[type=range]").change(function() {
            var range = $(this).val();
            scheduler.config.hour_size_px = 44 * range;
            $('.dhx_cal_tab.active').click();

            localStorage.setItem('channel_' + {{ $channel_id }} + '_range', range);
        });

        $('.dhx_cal_data').animate({
            scrollTop: $("[type=range]").val() * 44 * (new Date()).getHours()
        }, 2000);
    });
</script>
<div class="row center-block height" id="contnet-wrap">
    <div class="col-md-12 height" id="schedule">
        <div id="schedule-body" class="height content">
            <p class="title-name"><i class="fa fa-calendar"></i>Schedule</p>
            <div class="scheduler col-sm-12 col-md-12">
                <div id="treeBox" class="col-md-3 height"></div>
                <div id="scheduler_here" class="dhx_cal_container col-md-9 col-lg-9 col-sm-9 col-lg-offset-3">
                    <div class="dhx_cal_navline">
                        <div class="dhx_cal_prev_button">&nbsp;</div>
                        <div class="dhx_cal_next_button">&nbsp;</div>
                        <div class="dhx_cal_today_button"></div>
                        <div class="dhx_cal_date"></div>
                        <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
                        <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
                        <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>

                        {{--<input type="range" value="1" min="1" max="10" step="0.1" />--}}
                    </div>
                    <div class="dhx_cal_header">
                    </div>
                    <div class="dhx_cal_data">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
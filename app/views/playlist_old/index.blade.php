@extends('template.template')
@section('content')
{{ HTML::script('js/date.js') }}
<style>
    html, body {
        height: 100%;
    }

    .height {
        height: 100%;
    }

    @media (min-width: 1200px) {
        #playlist, #timeline {
            width: 290px
        }
    }

    .columwrap {
        width: 625px;
        margin: 0 auto;
    }

    .title-p {
        background-color: #000000;
        color: #ffffff
    }

    .scroll-bar {
        overflow-y: scroll;
        overflow-x: hidden;
        max-height: 91%;
    }

    .panel-scroll {
        height: 80%;
        margin-bottom: 0;
        min-height: 400px;
        max-height: 2000px;
    }

    .row-centered {
        max-width: 640px;
        margin: 0 auto;
    }

    .col-centered {
        display: inline-block;
        float: none;
        /* reset the text-align */
        text-align: left;
        /* inline-block space fix */
        margin-right: -4px;
    }

    .playlist {
        cursor: pointer;
    }

    #play-button {
        margin-top: 10px;
        float: right;
    }
</style>

<main class="height container">
    @if(isset($video))


    <div class="row height row-centered ">

        <div class="span4 colum1 height" id="playlist">
            <div class="panel panel-default panel-scroll">
                <div class="panel-heading">
                    <h3 class="panel-title">Playlist</h3>
                </div>
                <div class="panel-body scroll-bar">

                    @foreach($video as $vid)
                    <div class="panel panel-default playlist" time="{{$vid->seconds}}" id="{{$vid->id}}"
                         title="{{$vid->title}}">
                        <div class="panel-heading">
                            <h3>{{$vid->title}}</h3>
                        </div>
                        <div class="panel-body">
                            <p>time: {{$vid->time}}</p>

                            <p>staus: @if($vid->status==0) <span>not playing</span>
                                @else <span>is playing</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="span4 colum1 height" id="timeline">
            <div style="margin-bottom: 0" class="panel panel-default panel-scroll">
                <div class="panel-heading">
                    <h3 class="panel-title">Timline</h3>
                </div>
                <div class="panel-body height">
                    <div style="height: 94%;margin-bottom: 0;" id="time">
                        @if($timeline)
                        @foreach($timeline as $tm)
                        <div style="height:{{$tm->percentage}}%;cursor:pointer;" class="{{$tm->classColor}} tm" title="Title: New klip start time:
                                    0:00:00 end time: 15:00:00" data-placement="right"></div>
                        @endforeach
                        @endif
                    </div>
                </div>

            </div>
            <!-- Bootstrap button-->
            <button type="button" class="btn btn-default btn-lg" id="play-button">
                <span></span> Play
            </button>

        </div>


    </div>

    <!-- Modal dialog-->
    <div class="modal fade" id="modal-click">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span></button>
                    <h4 class="modal-title">You added this in timeline</h4>
                </div>
                <div class="modal-body">
                    Click on other video
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-limit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span></button>
                    <h4 class="modal-title">The limit is a 24 hours</h4>
                </div>
                <div class="modal-body">
                    You cann't add more videos
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-tm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span></button>
                    <h4 class="modal-title">You have a timline</h4>
                </div>
                <div class="modal-body">
                    You cann't add more videos
                </div>

            </div>
        </div>
    </div>
    <!--end modal dialog-->
</main>
<script type="text/javascript">
    timline = {
        timeAll: 0,// all time in timeline <= timeLimit
        lastChoose: 5, // last element, which had been clicked
        timlineId: 0,// id element in timeline
        lastEnd: 0, // the end last video
        /*
         * Get the time value in seconds
         * from div block time attribute
         *
         */
        getTime: function (thisFunc) {
            if ($(thisFunc).hasClass('clicked')) return $('#modal-click').modal('show'); // stop the script work
            if ($('#time div').hasClass('tm')) return $('#modal-tm').modal('show');

            var time = parseInt($(thisFunc).attr('time'));
            var limit = this.timeAll + time;
            var timeLimit = 86400;
            if (limit > timeLimit) return $('#modal-limit').modal('show');// stop the script work
            var percentage = (100 * time) / timeLimit;//how much percentage is limitTime
            this.timeAll = this.timeAll + time;
            /* add twitter bootstrap classes for random choose */
            var arr = ["bg-primary", "bg-success", "bg-info", "bg-warning", "bg-danger", ""];

            // If click do first
            if (this.lastChoose == 5) {
                var rand = Math.floor(Math.random() * (-4) + 4);
                var classBootstrap = arr[rand];

            } else {
                /* choose the class what don't repeat*/
                while (true) {
                    var rand = Math.floor(Math.random() * (-4) + 4);
                    if (rand != this.lastChoose) {
                        var classBootstrap = arr[rand];
                        break;
                    }

                }
            }
            this.timlineId++;
            this.lastChoose = rand;
            $(thisFunc).addClass('clicked');
            $(thisFunc).css({'opacity': 0.5});
            var start = this.lastEnd; //start time in seconds
            var end = start + time; //end time in seconds
            this.lastEnd = end;

            var hours = new Date;
            startHum = hours.clearTime().addSeconds(start).toString('H:mm:ss'); // convert to string in format H:mm:ss
            endHum = hours.clearTime().addSeconds(end).toString('H:mm:ss'); // convert to string in format H:mm:ss

            $('#time').append('<div style="height:' + percentage + '%;cursor:pointer;" percentage="' + percentage + '" seconds="'
                + time + '" start="' + start + '" end="' + end + '" class="' + classBootstrap + ' timeColor" cls="'
                + classBootstrap + '" title="Title: ' + $(thisFunc).attr("title") + '  start time: ' + startHum + ' end time: '
                + endHum + '" video_id="' + $(thisFunc).attr("id") + '"' +
                'timline_id="' + this.timlineId + '" data-placement="right"></div>');

        },
        /*
         * this function send ajax request when button would be clicked
         */
        sendAjaxFromButton: function (thisFunc) {
            $(thisFunc).css({'opacity': 0.5});
            var url = 'getTimelineData';
            data = {};
            var count = 0;
            $('.timeColor').each(function () {
                data[count] = {};
                data[count].timeline_id = $(this).attr('timline_id');
                data[count].video_id = $(this).attr('video_id');
                data[count].start = $(this).attr('start');
                data[count].end = $(this).attr('end');
                data[count].seconds = $(this).attr('seconds');
                data[count].percentage = $(this).attr('percentage');
                data[count].clBootstrap = $(this).attr('cls');
                count++;
            });

            $.ajax({
                url: url,
                type: 'GET',
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data.status = true) {
                        alert('Timeline is playing');
                    } else {
                        alert('Timeline isnt playing');
                    }
                    $(thisFunc).css({'opacity': 1}).attr("disabled", "disabled");
                }
            });

        }
    };


    $(window).ready(function () {
        $('.playlist').click(function () {
            timline.getTime(this);
        });

        $('#time').on("mouseover", ".timeColor", function () {
            $(this).tooltip('show');
        });

        $('.tm').hover(function () {
            $(this).tooltip('show');
        });
        $('#play-button').click(function () {
            timline.sendAjaxFromButton(this);
        });
        if ($('#time div').hasClass('tm')) $('#play-button').attr("disabled", "disabled");
    });
</script>
@else <p style="color:red">You haven't</p>
@endif

@stop
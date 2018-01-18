<!DOCTYPE html>
<html lang="en">
<head>

    <title>{{$title}}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"/>

    {{-- CSS Libraries --}}
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/fullcalendar.min.css') }}
    {{ HTML::style('css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.css') }}
    {{ HTML::style('bower_components/flat-ui/dist/css/flat-ui.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css') }}
    {{ HTML::style('css/jQuery_ui_css/jquery-ui.css') }}
    {{ HTML::style('css/font-awesome.min.css') }}
    {{ HTML::style('css/schedule/dhtmlxscheduler.css') }}
    {{ HTML::style('css/schedule/dhtmlxtree.css') }}
    {{ HTML::style('css/schedule/terrace/dhtmlxtree.css') }}
    {{ HTML::style('//vjs.zencdn.net/5.8/video-js.min.css') }}


    {{-- JS Libraries --}}
    {{ HTML::script('//vjs.zencdn.net/5.8/video.min.js') }}
    {{ HTML::script('js/jquery-2.1.1.min.js') }}
    {{ HTML::script('js/jQuery_UI/jquery-ui.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('http://www.google.com/jsapi') }}
    {{ HTML::script('js/jquery_file_upload/vendor/jquery.ui.widget.js') }}
    {{ HTML::script('js/jquery_file_upload/jquery.iframe-transport.js') }}
    {{ HTML::script('js/jquery_file_upload/jquery.fileupload.js') }}
    {{-- HTML::script('js/flat-ui.js') --}}
    {{ HTML::script('js/schedule/dhtmlxscheduler.js') }}
    {{ HTML::script('js/schedule/dhtmlxscheduler_outerdrag.js') }}
    {{ HTML::script('js/schedule/dhtmlxtree.js') }}
    {{ HTML::script('js/panda-uploader.min.js') }}
    {{ HTML::script('js/misc.js') }}
    
    

    {{-- My CSS files --}}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/index.css') }}
    {{ HTML::style('css/collections.css') }}
    {{ HTML::style('css/add_playlist.css') }}
    {{ HTML::style('css/playout.css') }}
    {{ HTML::style('css/upload.css') }}
    {{ HTML::style('css/styles.css') }}
    {{ HTML::style('css/schedule/time_picker.css') }}


    {{-- My JS files --}}
    {{ HTML::script('js/ace.js') }}
    {{ HTML::script('js/index/ace.js') }}
    {{ HTML::script('js/collections/add_collection.js') }}
    {{ HTML::script('js/collections/drag_and_drop.js') }}
    {{ HTML::script('js/collections/collections_index.js') }}
    {{ HTML::script('js/collections/script.js') }}
    {{ HTML::script('js/index/script.js') }}
    {{ HTML::script('js/playlist/script.js') }}
    {{ HTML::script('js/tvapp/script.js') }}
    {{ HTML::script('js/tvweb/script.js') }}
    {{ HTML::script('js/videos/drag_and_drop.js') }}
    {{ HTML::script('js/videos/get_video_desc.js') }}
    {{ HTML::script('js/videos/script.js') }}
    {{ HTML::script('js/settings/script.js') }}
    {{ HTML::script('js/schedule/time_picker.js') }}


    @if(isset($playout))
        {{ HTML::script('http://cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js') }}
        {{ HTML::script('http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/moment.min.js') }}
        {{ HTML::script('http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js') }}
        <!--    {{ HTML::script('packages/timeline/timeline.js') }}-->
        {{ HTML::script('js/d3-timeline/d3-timeline.js') }}
        {{ HTML::style('css/d3-timeline/d3-timeline.css') }}
        {{ HTML::script('js/d3-timeline/utils.js') }}
        {{ HTML::script('js/playout/script.js') }}
        <!--    {{ HTML::style('packages/timeline/timeline.css') }}-->
    @endif

    {{ HTML::script('js/upload/script.js') }}


	                            
    <script>
        ace = ace || {};
        ace.user_id = 0;
        ace.timestamp = {{ $timestamp }};

        @if(isset($channel))
        ace.channel_id = '{{$channel['id']}}';
        @endif

        @if(!empty($master_loop_playlist))
        ace.master_loop_playlist = {{ json_encode($master_loop_playlist) }}

        $(document).ready(function () {
            $('#playlist-header-wrap #playlist-name-header').html(ace.master_loop_playlist.title);

            var currentTime = ace.timestamp;
            var playlistStartTime = ace.master_loop_playlist.master_looped;
            var duration = ace.master_loop_playlist.duration;
            var colon = true;

            function addZero(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }

            setInterval(function () {
                $('#playlist-header-wrap .progress-bar-success').width((100 * ((currentTime - playlistStartTime) % duration)) / duration + '%');

                var l = 0;
                $.each(ace.master_loop_playlist.videos, function (index, video) {
                    l += parseInt(video.duration);
                    if (((currentTime - ace.master_loop_playlist.master_looped) % duration) < l) {
                        $('#video-header-wrap #video-name-header').html(video.title);
                        $('#video-header-wrap .progress-bar-success').width(((video.duration - (l - ((currentTime - playlistStartTime) % duration))) * 100) / video.duration + '%');

                        return false;
                    }
                });

                var startTime = currentTime * 1000;

                $('.hours').html(addZero(new Date(startTime).getHours()));
                $('.minutes').html(addZero(new Date(startTime).getMinutes()));
                $('.seconds').html(addZero(new Date(startTime).getSeconds()));

//                    currentTime = (parseFloat(currentTime + 0.1)).toFixed(1);
                ace.timestamp++;
                currentTime++;
            }, 1000);

            setInterval(function() {
                if(colon) {
                    $('.colon').css('visibility', 'hidden');
                    colon = false;
                } else {
                    $('.colon').css('visibility', 'visible');
                    colon = true;
                }
            }, 500);
        });
        @else

        $(document).ready(function () {
            var currentTime = ace.timestamp;
            var colon = true;

            function addZero(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }

            setInterval(function() {
                var startTime = currentTime * 1000;

                $('.hours').html(addZero(new Date(startTime).getHours()));
                $('.minutes').html(addZero(new Date(startTime).getMinutes()));
                $('.seconds').html(addZero(new Date(startTime).getSeconds()));

//                    currentTime = (parseFloat(currentTime + 0.1)).toFixed(1);
                ace.timestamp++;
                currentTime++;
            }, 1000);

            setInterval(function() {
                if(colon) {
                    $('.colon').css('visibility', 'hidden');
                    colon = false;
                } else {
                    $('.colon').css('visibility', 'visible');
                    colon = true;
                }
            }, 500);
        });

        @endif

    </script>


    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }

        .progress {
            background-color: #d9dbdd;
        }
        .td-filename {
            width: 400px;
        }
        .td-filesize {
            width: 40px;
        }
        .td-progress {
            width: 300px;
        }

        .td-action .btn {
            width: 75px;
            padding-left: 0px;
            padding-right: 0px;
        }

    </style>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    {{ HTML::script('js/stream/mediaelement-and-player.min.js') }}
    {{ HTML::style('js/stream/mediaelementplayer.min.css') }}
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    <link rel="stylesheet" media="print" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.print.css"/>
    
    
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.3.17.min.js"></script>
            
    
</head>
<body id="plugins_snapshot">
<div id="wrapper" class="container showLoader" style="display: none;">
    <header>
        <div class="row center-block">
            <div class="col-md-12" id="head-logo">
                <div class="company_logo">
                    <a href="/" id="logo">
                        <img onerror="$('#logo img').attr('src', '{{asset('images/noLogo.png')}}')" src="http://prolivestream.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}?{{ md5($channel['updated_at']) }}" alt="ACE playout" class="logo1 center-block">
                    </a>
                </div>
                <div class="playing height">
                    <div id="playlist-header-wrap">
                        <p id="playlist-name-header"></p>

                        <div class="progress progress-bar-2" id="progress-left">
                            <div class="progress-bar progress-bar-success"></div>
                        </div>
                    </div>
                    <div id="video-header-wrap">
                        <p id="video-name-header"></p>

                        <div class="progress progress-bar-2" id="progress-right">
                            <div class="progress-bar progress-bar-success"></div>
                        </div>
                    </div>
                </div>
                <div class="group2">
                    <div class="header_group">
                        <div class="ace_logo">
                            <a href="/">
                                <img src="{{ asset('images/acelogo.png') }}" alt="ACE Playout" class="center-block">
                            </a>
                        </div>
                        <a href="{{ URL::route('logout') }}" class="log_out" title="Logout">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                        <div class="clear"></div>
                    </div>
                    <button class="btn btn-block btn-lg btn-danger watch_now" title="WATCH NOW!">
                        <i class="fa fa-arrow-circle-right"></i> &nbsp;WATCH NOW!
                    </button>
                </div>
                <div class="group1">
                    <div class="header_group">
                        @if($status == 1)
                            <div class="on_air" title="ON AIR">ON AIR</div>
                        @elseif($status == 0)
                            <div class="on_air on_air_off" title="ON AIR">ON AIR</div>
                        @endif
                        <div class="time">
                            <i class="fa fa-clock-o"></i>&nbsp;
                            <span class="hours"></span>
                            <span class="colon"> : </span>
                            <span class="minutes"></span>
                            <span class="colon"> : </span>
                            <span class="seconds"></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    @if($status == 1)
                        <button class="btn btn-block btn-lg btn-success stream_status stop_stream" title="STOP STREAM">
                            <i class="fa fa-stop"></i> &nbsp;STOP STREAM
                        </button>
                    @elseif($status == 0)
                        <button class="btn btn-block btn-lg btn-success stream_status start_stream" title="START STREAM">
                            <i class="fa fa-play"></i> &nbsp;START STREAM
                        </button>
                    @endif
                </div>
                {{--<div class="col-md-1">--}}
                {{--@if($status == 1)--}}
                {{--<button class="btn btn-block btn-lg btn-success btnStop" id="startStop" title="Stop stream"><span class="fui-power"></span></button>--}}
                {{--@elseif($status == 0)--}}
                {{--<button class="btn btn-block btn-lg btn-success btnStart" id="startStop" title="Start stream"><span class="fui-play"></span></button>--}}
                {{--@endif--}}
                {{--</div>--}}
                {{--<div class="col-md-1">--}}
                {{--<a href="{{ URL::route('logout') }}" class="btn btn-block btn-lg btn-danger btnLogout" title="Log Out"><span class="fui-exit"></span></a>--}}
                {{--</div>--}}
            </div>
        </div>
    </header>

    <nav>
        <div class="row center-block">
            <div class="col-md-12">
                <div id="nav">
                    @if(isset($channel))
                        <ul>
                            <li ><a href="/channel_{{ $channel['id'] }}/settings"><i class="fa fa-cog"></i></a></li>
                            @if(Auth::user()->is(User::USER_MANAGE_CHANNEL))
                                {{--<li ><a href="/channel_{{ $channel['id'] }}/settings"><i class="fa fa-cog"></i>Settings</a></li>--}}
                            @endif
                            @if(Auth::user()->is(User::USER_MANAGE_MEDIA))
<!--                                 <li ><a href="/channel_{{ $channel['id'] }}/tvweb">Mobile/Web</a></li> -->
                                <li ><a href="/channel_{{ $channel['id'] }}/tvapp"><!--i class="fa fa-play-circle"></i-->TV Apps</a></li>
<!--                                 <li ><a href="/channel_{{ $channel['id'] }}/schedule">Schedule</a></li> -->
                                <li ><a href="/channel_{{ $channel['id'] }}/playlists"><!--i class="fa fa-play-circle"></i-->Live Playlists</a></li>
                                <li ><a href="/channel_{{ $channel['id'] }}/collections"><!--i class="fa fa-film"></i-->Collections</a></li>
                                <li ><a href="/channel_{{ $channel['id'] }}/videos"><!--i class="fa fa-video-camera"></i-->Videos</a></li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                {{--<h4 class="modal-title" id="myModalLabel">Video stream</h4>--}}
            {{--</div>--}}
            <div class="modal-body">
                <video width="640" height="360" id="player1">
                    <!-- Pseudo HTML5 -->

                    {{--<source type="application/x-mpegURL" src="http://198.241.44.164:8888/hls/master-acetest2.m3u8"/>--}}
                    <source type="application/x-mpegURL" src="{{ $channel['stream_url'] }}"/>

                    {{--<source type="application/x-mpegURL" src="http://198.241.44.164:7880/channel-1"/>--}}
                    {{--<source type="application/x-mpegURL" src="http://162.247.57.18:7877/test1"/>--}}
                    {{--<source type="application/x-mpegURL" src="http://162.247.57.18:80/hls/master-test1.m3u8"/>--}}
                    {{--<source type="application/x-mpegURL" src="http://162.247.57.18:80/hls/master-motornationtv.m3u8"/>--}}
                    {{--<source type="application/x-mpegURL" src="http://www.streambox.fr/playlists/test_001/stream.m3u8"/>--}}
                    {{--<source type="application/x-mpegURL" src="http://198.241.44.164:7877/tufftv-test"/>--}}
                </video>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.watch_now').click(function() {
            $('#myModal').modal('toggle');
            $('video').mediaelementplayer({
                success: function (media) {
                    media.stop();
                    media.play();
                }
            });
        });
    });
</script>

{{--Page Load--}}
{{ HTML::style('loading/loading.css') }}
{{ HTML::script('loading/loading.js') }}




</body>
</html>

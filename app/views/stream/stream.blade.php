<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Stream</title>

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/stream/mediaelement-and-player.min.js"></script>
    {{--<script src="testforfiles.js"></script>--}}
    <link rel="stylesheet" href="js/stream/mediaelementplayer.min.css"/>
</head>
<body>
    <video width="640" height="360" id="player1">
        <!-- Pseudo HTML5 -->
        <source type="application/x-mpegURL" src="{{ $channel['stream_url'] }}"/>
        {{--<source type="application/x-mpegURL" src="http://198.241.44.164:8888/hls/master-acetest2.m3u8"/>--}}
        {{--<source type="application/x-mpegURL" src="http://198.241.44.164:7880/channel-1"/>--}}
        {{--<source type="application/x-mpegURL" src="http://www.streambox.fr/playlists/test_001/stream.m3u8"/>--}}
    </video>

    {{--<script type="text/javascript" src="hls_streams.js"></script>--}}
    {{--<script type="text/javascript">--}}
        {{--function listStreams(list, container) {--}}
            {{--for (var i = 0; i < list.length; i++) {--}}
                {{--var entry = document.createElement("li");--}}
                {{--entry.innerHTML = "<a href='#' onclick='return loadStream(\"" + list[i].file + "\")'>" + list[i].title + "</a>";--}}
                {{--document.getElementById(container).appendChild(entry);--}}
            {{--}--}}
        {{--}--}}
        {{--listStreams(teststreams, "streamlist");--}}

        {{--function userSubmit() {--}}
            {{--loadStream(document.getElementById('userInput').value);--}}
        {{--}--}}
        {{--function loadStream(url) {--}}
            {{--$('video')[0].player.setSrc(url);--}}
            {{--$('video')[0].player.play();--}}
        {{--}--}}
    {{--</script>--}}
    <script>
        $('video').mediaelementplayer({
            success: function (media, node, player) {
                $('#' + node.id + '-mode').html('mode: ' + media.pluginType);

                media.play();
            }
        });
    </script>
</body>
</html>
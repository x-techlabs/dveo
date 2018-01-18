<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version='2.0' xmlns:media='http://search.yahoo.com/mrss/'>
    <channel>
        <title><![CDATA[{{$playlist->title}}]]></title>
        <link><![CDATA[http://www.erienewsnow.com/category/211870/ocw525home]]></link>
        <description><![CDATA[{{$playlist->description}}]]></description>
        <language>en-us</language>
        @foreach($items as $video)
            @include('tvapp.mrss._video', ['video' => $video, 'channel_id' => $playlist->channel_id])
        @endforeach
    </channel>
</rss>

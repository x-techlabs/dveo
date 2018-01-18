<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version='2.0' xmlns:media='http://search.yahoo.com/mrss/'>
    <channel>
        <title><![CDATA[{{$slides->title}}]]></title>
        <link><![CDATA[http://www.erienewsnow.com/category/211870/ocw525home]]></link>
        <description><![CDATA[{{$slides->description}}]]></description>
        <language>en-us</language>
        @foreach($slides->images as $image)
            @include('images.mrss._image', ['image' => $image, 'channel_id' => $slides->channel_id])
        @endforeach
    </channel>
</rss>

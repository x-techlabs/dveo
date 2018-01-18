<item xmlns:wn="http://www.worldnow.com/">
    <title><![CDATA["{{$video->title}}"]]></title>
    <pubDate>{{$video->updated_at}}</pubDate>
    <description><![CDATA[{{$video->description}}]]></description>
    <media:title><![CDATA[{{$video->title}}]]></media:title>
    <media:content url="{{$video->video_path}}" type="{{$video->hd_mime_type}}" medium='video' height="{{$video->hd_height}}" width="{{$video->hd_width}}" duration="{{$video->duration}}" fileSize="{{$video->hd_file_size}}" bitrate="{{$video->hd_video_bitrate}}" />
    <media:thumbnail url="{{$video->mrss_thumbnail}}" width='285' height='145' />
    @if($video->prerollUrl != '')
		<preroll>{{ $video->prerollUrl }}</preroll>
    @endif
</item>

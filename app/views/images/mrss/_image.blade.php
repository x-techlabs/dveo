<item xmlns:wn="http://www.worldnow.com/">
    <title><![CDATA["{{$image->title}}"]]></title>
    <pubDate>{{$image->updated_at}}</pubDate>
    <description><![CDATA[{{$image->description}}]]></description>
    <media:title><![CDATA[{{$image->title}}]]></media:title>
    <media:content url="{{$image->file_name}}" type="{{$image->hd_mime_type}}" medium='image' height="{{$image->hd_height}}" width="{{$image->hd_width}}" fileSize="{{$image->hd_file_size}}" />
    <media:thumbnail url="{{$image->thumbnail_name}}" width='285' height='145' />
</item>

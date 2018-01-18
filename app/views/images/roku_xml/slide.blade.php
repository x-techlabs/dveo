<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>"; ?>

<feed>
    @foreach($slides->images as $image)
    <?php
        $streamUrl = trim($image->file_name);
        if ($image->source != '0' && $image->source != 'internal') {
            $streamUrl = trim($image->file_name);
        }
    ?>
    <item sdImg="{{htmlspecialchars($image->roku_xml_thumbnail_hd, ENT_XML1)}}" hdImg="{{htmlspecialchars($image->roku_xml_thumbnail_sd, ENT_XML1)}}">
        <title>{{htmlspecialchars($image->title, ENT_QUOTES | ENT_XML1)}}</title>
        <description>{{htmlspecialchars(strip_tags($image->description), ENT_QUOTES | ENT_XML1)}}</description>
        <contentType>View</contentType>
        <contentId>{{$image->id}}</contentId>
        <media>
            <streamFormat>{{ $image->image_format }}</streamFormat>
            <streamUrl>{{ $streamUrl }}</streamUrl> 
        </media>
        <media>
            <streamFormat>{{ $image->image_format }}</streamFormat>
            <streamUrl>{{ $streamUrl }}</streamUrl>
        </media>
        <synopsis>{{htmlspecialchars(strip_tags($image->description), ENT_XML1)}}</synopsis>
        <genres>Clip</genres>
        <starrating>75</starrating>
        <Rating>NR</Rating>
    </item>
    @endforeach
</feed>

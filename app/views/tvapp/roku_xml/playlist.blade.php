<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>"; ?>

<feed>
    @foreach($playlist->videos as $video)
    <?php
      $streamUrl = trim($video->video_path);
      if ($video->source != '0' && $video->source != 'internal') {
          $streamUrl = htmlspecialchars(trim($video->file_name));
      }
    ?>
	<item mobileweb_url = "{{ !empty($video->mobileweb_image_url) ? 'https://prolivestream.imgix.net/banners/'.$video->mobileweb_image_url : '' }}"  banner_url = "{{ (!empty($video->tvapp_image_url)) ? 'https://prolivestream.imgix.net/banners/'.$video->tvapp_image_url : '' }}" sdImg="{{ (!empty($video->custom_poster)) ? 'https://prolivestream.imgix.net/banners/'.$video->custom_poster.'?w=854&amp;h=480&amp;fit=crop&amp;crop=entropy&amp;auto=format,enhance&amp;q=60' : 'https://1stud-io.imgix.net/'.htmlspecialchars($video->roku_xml_thumbnail_sd, ENT_XML1).'?w=854&amp;h=480&amp;fit=crop&amp;crop=entropy&amp;auto=format,enhance&amp;q=60'}}" hdImg="{{ (!empty($video->custom_poster)) ? 'https://prolivestream.imgix.net/banners/'.$video->custom_poster.'?w=854&amp;h=480&amp;fit=crop&amp;crop=entropy&amp;auto=format,enhance&amp;q=60' : 'https://1stud-io.imgix.net/'.htmlspecialchars($video->roku_xml_thumbnail_sd, ENT_XML1).'?w=854&amp;h=480&amp;fit=crop&amp;crop=entropy&amp;auto=format,enhance&amp;q=60'}}">
{{--	<item custom_poster = "{{ (!empty($video->custom_poster)) ? 'https://prolivestream.imgix.net/banners/'.$video->custom_poster : ''}}" mobileweb_url = "{{ !empty($video->mobileweb_image_url) ? 'https://prolivestream.imgix.net/banners/'.$video->mobileweb_image_url : '' }}"  banner_url = "{{ (!empty($video->tvapp_image_url)) ? 'https://prolivestream.imgix.net/banners/'.$video->tvapp_image_url : '' }}" sdImg="https://1stud-io.imgix.net/images/{{htmlspecialchars($video->roku_xml_thumbnail_hd, ENT_XML1)}}" hdImg="https://1stud-io.imgix.net/images/{{htmlspecialchars($video->roku_xml_thumbnail_sd, ENT_XML1)}}">--}}
        <title>{{htmlspecialchars($video->title, ENT_QUOTES | ENT_XML1)}}</title>
        <description>{{htmlspecialchars(strip_tags($video->description), ENT_QUOTES | ENT_XML1)}}</description>
        <contentType>Talk</contentType>
        <contentId>{{$video->id}}</contentId>
        <media>
            <streamFormat>mp4</streamFormat>
            <streamQuality>SD</streamQuality>
            <streamUrl>{{ $streamUrl }}</streamUrl>
        </media>
        <media>
            <streamFormat>mp4</streamFormat>
            <streamQuality>HD</streamQuality>
            <streamUrl>{{ $streamUrl }}</streamUrl>
        </media>
        <synopsis>{{htmlspecialchars(strip_tags($video->description), ENT_XML1)}}</synopsis>
        <genres>Clip</genres>
        <runlength>{{$video->duration}}</runlength>
        <starrating>75</starrating>
        <Rating>NR</Rating>
        @if($video->prerollUrl != '')
            <preroll>{{ $video->prerollUrl }}</preroll>
        @endif
    </item>
    @endforeach
</feed>

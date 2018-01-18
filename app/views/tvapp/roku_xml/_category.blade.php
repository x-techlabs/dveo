<?php
    $feed_url = '';
    $stream_url = '';
    $layout = 'linear';
    $shelf = ($playlist->shelf == 1)? 'top' : '';
    $paid = 'no';

    if ($playlist->layout == 0)
    {
        $layout = "linear";

        // why 7?
        // I dont know what the hell is this

        if (strlen($playlist->stream_url) < 7)
        {
            $feed_url = "http://1stud.io/tvapp/channel_$playlist->channel_id/roku/xml/playlists/$playlist->file_name.xml";
        }
        else
        {
            $stream_url = $playlist->stream_url;
            $clienttype = substr($stream_url, -16);
            if ($clienttype != '?clienttype=mrss') $stream_url.= '?clienttype=mrss';
            $feed_url = htmlspecialchars($stream_url, ENT_XML1 | ENT_COMPAT, 'UTF-8');
        }
    }
    else if ($playlist->layout == 1) {
        $layout = "grid";
        if (strlen($playlist->stream_url) < 7)
        {
            $feed_url = "http://1stud.io/tvapp/channel_$playlist->channel_id/roku/xml/playlists/$playlist->file_name.xml";
        }
        else
        {
            $stream_url = $playlist->stream_url;
            $clienttype = substr($stream_url, -16);
            if ($clienttype != '?clienttype=mrss') $stream_url.= '?clienttype=mrss';
            $feed_url = htmlspecialchars($stream_url, ENT_XML1 | ENT_COMPAT, 'UTF-8');
            $stream_url = htmlspecialchars($stream_url, ENT_XML1 | ENT_COMPAT, 'UTF-8');
        }
    }
    else if ($playlist->layout == 2 || $playlist->layout == 4 || $playlist->layout == 5)
    {
        $layout = "video";
        $feed_url = htmlspecialchars($playlist->stream_url, ENT_XML1 | ENT_COMPAT, 'UTF-8');
        $stream_url = htmlspecialchars($playlist->stream_url, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
    else if ($playlist->layout == 3)
    {
        $feed_url = '';
        $layout = "paragraph";
    }
	 $img_url = str_replace('http://prolivestream.s3.amazonaws.com/', 'https://onestudio.imgix.net/', $playlist->thumbnail_name); 
    if( !empty($img_url) ) {
        if( strrpos( $img_url, "?" ) ) {
            $img_url .= "&w=854&h=480&fit=crop&crop=entropy&auto=format,enhance&q=60";
        } else {
            $img_url .= "?w=854&h=480&fit=crop&crop=entropy&auto=format,enhance&q=60";
        }
    }
	
	
	if($playlist->video_is == 0){
		?>
			<category
				contentId="{{$playlist->id}}"
				title="{{htmlspecialchars($playlist->title, ENT_QUOTES | ENT_XML1)}}"
				description="{{htmlspecialchars(strip_tags($playlist->description), ENT_QUOTES | ENT_XML1)}}"
			  sd_img="{{htmlspecialchars( $img_url , ENT_XML1)}}"
				hd_img="{{htmlspecialchars( $img_url , ENT_XML1)}}"
				feed="{{$feed_url}}"
				stream_url="{{$stream_url}}"
				layout="{{$layout}}"
				paid="{{$playlist->viewing}}"
				banner_url="{{$playlist->featured_image_url}}"
				<?= ($shelf!='')? 'shelf="'.$shelf.'"' : '' ?>
				<?= (count($playlist->children)!=0)? 'type="nested"' : '' ?>
			>
				@foreach($playlist->children as $child_playlist)
					@include('tvapp.roku_xml._category', ['playlist' => $child_playlist])
				@endforeach
			</category>
		<?php
	}
	else{
		
		?>
			<feed>
				<item sdImg="{{htmlspecialchars( $img_url , ENT_XML1)}}" hdImg="{{htmlspecialchars( $img_url , ENT_XML1)}}" <?= ($shelf!='')? 'shelf="'.$shelf.'"' : '' ?>  <?= (count($playlist->children)!=0)? 'type="nested"' : '' ?> >
					<title>"{{htmlspecialchars($playlist->title, ENT_QUOTES | ENT_XML1)}}"</title>
					<description>"{{htmlspecialchars(strip_tags($playlist->description), ENT_QUOTES | ENT_XML1)}}"</description>
					<contentType>Talk</contentType>
					<contentId>"{{$playlist->id}}"</contentId>
					<layout>"{{$layout}}"</layout>
					<paid>"{{$playlist->viewing}}"</paid>
					<banner_url>"{{$playlist->featured_image_url}}"</banner_url>
					<media>
						<streamFormat>mp4</streamFormat>
						<streamQuality>SD</streamQuality>
						<streamUrl>"{{$stream_url}}"</streamUrl>
					</media>
					<media>
						<streamFormat>mp4</streamFormat>
						<streamQuality>HD</streamQuality>
						<streamUrl>"{{$stream_url}}"</streamUrl>
					</media>
					<synopsis>
						<?php
							foreach($video as $v){
								if($v->id == $playlist->id){
									echo $v->description;
								}
								
							}
						?>
					</synopsis>
					<genres>Clip</genres>
					<runlength>
						<?php
							foreach($video as $v){
								if($v->id == $playlist->id){
									echo $v->duration;
								}
								
							}
						?>
					</runlength>
					<starrating>75</starrating>
					<Rating>NR</Rating>
					
				</item>
			</feed>
		<?php
	}
?>


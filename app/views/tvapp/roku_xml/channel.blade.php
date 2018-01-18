<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>"; ?>
<categories>
    @foreach($playlists as $child_playlist)
        @include('tvapp.roku_xml._category', ['playlist' => $child_playlist , 'video' => $video])
    @endforeach
</categories>
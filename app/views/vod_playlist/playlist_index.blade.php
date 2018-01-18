@extends('template.template')

@section('content')

@if(isset($playlists))

<div class="row center-block height" id="contnet-wrap">

    <div class="col-md-12 list-wrap height-inherit" id="playlists">

        <div class="height-inherit playlists content">
            <div class="title-name">
                <!-- <i class="fa fa-play-circle"></i>
                <div class="title">Get Playlist</div> -->
                <div class="input-group searchPlay">
                    <input type="text" class="form-control" placeholder="Search" id="search-query-3">
                    <span class="input-group-btn">
                        <button type="submit" class="btn"><span class="fui-search"></span></button>
                    </span>
                </div>
                <a href="#" class="btn btn-block btn-lg greenActionBtn plusPtnCol" id="create-playlist" title="New playlist">&plus; New Playlist</a>
                <div class="clear"></div>
            </div>

            <div class="row center-block list content_list" id="container_content">
                <div class="col-md-12 height appendPlaylist">
                    @foreach($playlists as $playlist)
                        <div class="lists">
                            <section data-playlist_id="{{$playlist->id}}" class="list_item section_playlist">
                                @if($playlist->type == 2)
                                <a  href = "/channel_{{$channel_id}}/vod_playlist/{{$playlist->id}}" id="{{$playlist->id}}" class="open_playlist editDelete fr btn btn-block btn-lg btn-primary" title="Get Playlist">
                                    <i class="fa fa-play-circle" aria-hidden="true"></i>
                                </a>
                                @else
                                <a  href = "/channel_{{$channel_id}}/vod_playlist/{{$playlist->id}}" id="{{$playlist->id}}" class="open_playlist editDelete fr btn btn-block btn-lg btn-warning" title="Get Playlist">
                                    <i class="fa fa-play-circle" aria-hidden="true"></i>
                                </a>
                                @endif
                                <button id="{{$playlist->id}}" class="edit_playlist editDelete fr btn btn-block btn-lg btn-inverse" title="Edit playlist">
                                    <span class="fui-new"></span>
                                </button>
                                <button id="{{$playlist->id}}" class="delete_playlist editDelete fr btn btn-block btn-lg btn-danger" title="Delete playlist">
                                    <span class="fui-trash"></span>
                                </button>
                                <div class="clear"></div>
                                <div class="row center-block">
                                    <div class="col-md-2 playlist_thumb">
                                        <img style="width:100%;" src="{{ $playlist->video_thumb }}">
                                    </div>
                                    <div class="col-md-10 playlist_thumb">
                                        <h1 class="videoTtitle">{{$playlist->title}}</h1>
                                        <p class="duration">
                                            <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$playlist->time}}
                                            @if($playlist->type == 2)
                                                <span class="master_looped">&nbsp;|&nbsp;Master looped</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  window.NickelledMissionDockSettings = {
    missionDockId: 129,
    userId: {{ $channel['id'] }}
  };

  (function(){var NickelledMissionDock=window.NickelledMissionDock=NickelledMissionDock||{};NickelledMissionDock.preloadEvents=[];NickelledMissionDock.show=function(){NickelledMissionDock.preloadEvents.push('show')};NickelledMissionDock.hide=function(){NickelledMissionDock.preloadEvents.push('hide')};var loadMD=function(){var s,f;s=document.createElement("script");s.async=true;s.src="https://cdn.nickelled.com/mission-dock.min.js";f=document.getElementsByTagName("script")[0];f.parentNode.insertBefore(s,f);};loadMD();NickelledMissionDock.show();})();
</script>
@endif
@stop
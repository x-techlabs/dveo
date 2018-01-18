<div class="row center-block playlists-for-collection-by-id" id="contnet-wrap">
    <div class="col-md-6 height" style="padding-right: 0px!important;">
        <div class="collectionPlaylistBox height content">
            <div class="title-name">
                <i class="fa fa-video-camera"></i>
                <div class="title">Videos</div>
                <div class="input-group searchColPl">
                    <input type="text" class="form-control" placeholder="Search" id="search-query-3">
                    <span class="input-group-btn">
                        <button type="submit" class="btn"><span class="fui-search"></span></button>
                    </span>
                </div>
            </div>
            <div class="row center-block videosCol content_list" id="container_content">
                <div class="blocks height">

                    <div class="col-md-12">
                        @foreach($videos as $video)
                        <section  class="section_playlist" style='cursor: pointer;'>

                            <div class="row center-block" video_id="{{$video['id']}}">
                                <div class="col-md-4">
                                    <img src="{{$video['thumbnail_name']}}" class="thumbnail_video">
                                </div>
                                <div class="col-md-8" style="">
                                    <h1 class="videoTtitle">
                                        {{$video['title']}}
                                    </h1>
                                    <p class="duration">
                                        <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$video['time']}}
                                    </p>
                                </div>
                            </div>
                        </section>
                        @endforeach

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

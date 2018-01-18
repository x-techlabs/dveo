@extends('template.template')

@section('content')
<style type="text/css">
    .tab-container{
        display: none;
    }
    #tab1{
        display: block;
    }
</style>
<script language='javascript'>
    function OnTabChange(x)
    {
        document.getElementById('tab1').style.display='none';
        document.getElementById('tab2').style.display='none';
        document.getElementById('tab3').style.display='none';
        document.getElementById('tab'+x).style.display='block';
    }
</script>

<div class="row center-block height" id="content-wrap">
    <div class="col-md-12 list-wrap height-inherit" id="imagesWrapper">
        <div class="height-inherit content">
            <div class="title-name">
                <nav class="imageNav">
                    <ul class="nav tabs">
                        <li class="active" onclick='OnTabChange(1)'>
                            <a class = "btn btn-block btn-lg greenActionBtn imageryButtons" href="#tab1" data-toggle="tab">Image Library</a>
                        </li>
                        <li onclick='OnTabChange(2)'>
                            <a class = "btn btn-block btn-lg greenActionBtn imageryButtons" href="#tab2" data-toggle="tab">+ Folders</a>
                        </li>
                        <li onclick='OnTabChange(3)'>
                            <a class = "btn btn-block btn-lg greenActionBtn imageryButtons" href="#tab3s" data-toggle="tab">+ Slide Show</a>
                        </li>
                    </ul>
                </nav>
                <div class="clear"></div>
            </div>
            <div class="row center-block list content_list" id="tabsContainer">
                <div class="col-md-12 appendSlide">
                    <div class="tab-container height" id="tab1">
                        <div class="addBlock">
                            <a href="../channel_{{$channel['id']}}/upload_image" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="add-image">+ New Image</a>
                        </div>
                        <div class="imagesContent" id = "images-col">
                            @if(count($images) > 0 && !empty($images))
                                @foreach($images as $image)
                                    <section id="image_item{{ $image->id }}" index="{{ $image->id }}" data-img_id="{{ $image->id }}" class="list_item image_item" style="position: relative;">
                                        <button id="{{ $image->id }}" class="delete_image fr editDelete btn btn-block btn-lg btn-danger" title="Delete image">
                                            <span class="fui-trash"></span>
                                        </button>
                                        <button id="{{ $image->id }}" class="edit_image fr editDelete btn btn-block btn-lg btn-inverse" title="Edit image">
                                            <span class="fui-new"></span>
                                        </button>
                                        <a href="https://s3.amazonaws.com/1stud-images/{{ $image->file_name }}" data-lightbox = "{{ $image->id }}">
                                            <span class="zoomImg" img_id="{{ $image->id }}"><i class="fa fa-search fa-lg"></i></span>
                                        </a>

                                        <div class="clear"></div>

                                        <div class="row center-block">
                                            <div class="col-md-2">        
                                                <img src="https://s3.amazonaws.com/1stud-images/{{ $image->file_name }}" class="thumbnail_video" style="width:100%;">
                                            </div>

                                            <div class="col-md-10">
                                                <h1 class="videoTtitle">{{$image->title}}</h1>
                                                <span class="duration">
                                                    <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$image->created_at}}
                                                </span>
                                               </span>
                                            </div>
                                        </div>
                                    </section>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- Folders -->
                    <div class="tab-container row center-block folderWrapper" id="tab2">
                        <div id = "tabContainer" class="col-md-12">
                            <div class="addBlock">
                                <a href="javascript:void(0)" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="add-folder">
                                    + New Folder
                                </a>
                            </div>
                            <div class="row center-block list content_list" id="container_content">
                                <div class="col-md-12 height folder_container">
                                    @foreach($collections as $collection)

                                    <section data-collection_id="{{$collection->id}}" class="folder_item list_item section_collections">
                                        <button id="{{$collection->id}}" class="delete_folder editDelete btn btn-block btn-lg btn-danger" title="Delete collection">
                                            <span class="fui-trash"></span>
                                        </button>
                                        <button id="{{$collection->id}}" class="edit_folder editDelete btn btn-block btn-lg btn-inverse" title="Edit collection">
                                            <span class="fui-new"></span>
                                        </button>
                                        <div class="clear"></div>

                                        <div class="row center-block" style="margin-left:20px">
                                            <div class="col-md-12">
                                                <h1 class="videoTtitle folder_title">{{$collection->title}}</h1>
                                            </div>
                                        </div>
                                    </section>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide -->
                    <div class="tab-container" id="tab3">
                        <div class="addBlock">
                            <a href="javascript:void(0)" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="add-slide">
                                + New Slide
                            </a>
                        </div>
                        <div id = "slides">
                            <div class="row center-block list content_list" id="container_content1">
                                <div class="col-md-12 height appendPlaylist" id = "slide_container">
                                    @foreach($playlists as $playlist)
                                        <div class="lists">
                                            <section data-playlist_id="{{$playlist->id}}" class="list_item section_slide">
                                                <button id="{{$playlist->id}}" class="edit_slide editDelete fr btn btn-block btn-lg btn-inverse" title="Edit slide">
                                                    <span class="fui-new"></span>
                                                </button>
                                                <button id="{{$playlist->id}}" class="delete_slide editDelete fr btn btn-block btn-lg btn-danger" title="Delete slide">
                                                    <span class="fui-trash"></span>
                                                </button>
                                                <div class="clear"></div>
                                                <div class="row center-block">
                                                    <div class="col-md-2 playlist_thumb">
                                                        <img style="width:100%;" src="{{ (isset($playlist->video_thumb) && !empty($playlist->video_thumb)) ? $playlist->video_thumb : 'http://speakingagainstabuse.com/wp-content/themes/AiwazMag/images/no-img.png'}}">
                                                    </div>
                                                    <div class="col-md-10 playlist_thumb">
                                                        <h1 class="videoTtitle slideTitle" style="margin-top: 5px">{{$playlist->title}}</h1>
                                                        <p class="duration">
                                                            <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$playlist->created_at}}
                                                        </p>
                                                        @if($playlist->mrss_url != '' && file_exists(public_path("/imagery/channel_$channel_id/roku/mrss/$playlist->mrss_url")))
                                                            <p class="text-left slide_mrss"> <span><a target = "_blank" href="/imagery/channel_{{$channel_id}}/roku/mrss{{$playlist->mrss_url}}">MRSS URL</a></p>
                                                        @endif
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
@stop
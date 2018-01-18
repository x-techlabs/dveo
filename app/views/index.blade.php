@extends('template.template')

@section('content')

@if(isset($videos))

{{ HTML::script('js/videos/script.js') }}
<style type="text/css">
    #stype_for_design{
        float:left;
        border:4px solid red;
        margin-left:0px!important;
    }

</style>
<div class="row center-block list-wrap" id="contnet-wrap">
    <div class="col-md-12 content" id="videos-col">
	
        {{ Form::open(array('url' => '/channel_' . BaseController::get_channel_id() . '/videos', 'class' => 'form-horizontal', 'name' => 'videoList', 'method' => 'get')) }}

        <div class="title-name" style="display: block;">
            <!-- <i class="fa fa-video-camera"></i>
            <div class="title">Videos</div> -->
            &nbsp;{{ Form::select('stype', [
               '0' => 'Recent',
               '2' => 'Old At Top',
               '1' => 'Alphabetical'
               ],
			   $stype,
			   array('id' => 'stype', 'onchange' => 'OnSortChanged()')
			) }}
            {{ Form::hidden('search', '', array('id' => 'videoSearch')) }}
<!--
            <div class="input-group" style='float:left;width:40%;padding-left:20px;'>
                {{ Form::text('search', $search, array('class' => 'form-control', 'placeholder' => 'Search', 'id' => 'videoSearch')) }}
                <span class="input-group-btn">
                    <button type="submit" class="btn"><span class="fui-search"></span></button>
                </span>
            </div>
-->
            <a href="../channel_{{$channel['id']}}/upload" class="btn btn-block btn-lg greenActionBtn plusPtnVid" title="Upload video">&plus; Upload video</a>
            {{--<a href="../channel_{{$channel['id']}}/uploadLink" class="btn btn-block btn-lg greenActionBtn plusPtnVid" title="Upload video">&plus; Link Video</a>--}}
            <div class="clear"></div>
        </div>
        {{ Form::close() }}

        <div class="row center-block list content_list" id="container_content">
            <div class="col-md-12 searchHide height" id="myScroller">
                @foreach($videos as $key => $video)

                    <section id='vsec_{{$key}}' index='{{$video->id}}' data-video_id="{{$video->id}}" class="list_item section_video" style="position: relative;">

                        <!-- Edit Delete Buttons -->
                        <button id="{{$video->id}}" class="delete_video editDelete fr btn btn-block btn-lg btn-danger" title="Delete video">
                            <span class="fui-trash"></span>
                        </button>
                        <button id="{{$video->id}}" class="edit_video editDelete fr btn btn-block btn-lg btn-inverse" title="Edit video">
                            <span class="fui-new"></span>
                        </button>
<!--                        <img class="snapshot section_video" data-video_id="{{$video->id}}" src='{{ URL::to('/') }}/images/snapshot.png'>  -->
                        @if($video->source !== 'vimeo')
                            <span class="snapshot section_video" data-video_id="{{$video->id}}"><i class="fa fa-camera-retro fa-3x"></i></span>

                        @endif
                        <!-- Onclick event for class playVideoInPopup is defined in template.blade.php and it looks for attribute video_id -->
                        <span class="snapshot playVideoInPopup {{ $video->source == 'vimeo' ? 'noSnap' : ''}}" video_id="{{$video->id}}"><i class="fa fa-play fa-lg"></i></span>

                        <div class="clear"></div>

                        <div class="row center-block">
                            <div class="col-md-2">
                                @if($video->thumbnail_name == null)
                                    In process ...
                                @else
                                    <img data="{{ (!empty($video->custom_poster)) ? 'https://s3.amazonaws.com/aceplayout/banners/'.$video->custom_poster : $video->thumbnail_name}}" src="" class="thumbnail_video" style='width:100%;'>  <!-- vinay added style -->
                                @endif
                            </div>

                            <div class="col-md-10">
                                <h1 class="videoTtitle">{{$video->title}}</h1>
                                <span class="duration">
                                    <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$video->time}}
                                    <span class="master_looped">&nbsp;|&nbsp;{{ $video->mb_file_name }}</span>
                                    @if($video->source == 'vimeo')
                                        <img src="/images/vimeo_icon.png" class="vimeoIcon">
                                    @endif
                                </span>
								@if($channel['display_show'] == 1)
                                <div class="custom_options">
									@if(count($video->shows) > 0)
										<span class="custom_option"><b>Show:</b>
											@foreach($video->shows as $show)
												<span class="show_name">
													{{ $show->show_names->name }}
												</span>
											@endforeach
										</span>
									@endif
                                    <span class="custom_option"><b>Season:</b> {{ $video->season }}</span>
                                    <span class="custom_option"><b>Episode:</b> {{ $video->episode }}</span>
                                </div>
								@endif
                                <!-- <div>{{$video->storage}}</div>  -->
                            </div>
                        </div>
                        <div class=" {{ empty($video->job_id) ? 'section-overlay-hidden' : 'section-overlay' }} " >
                            <div class="wait_text">
                                Processing...
                            </div>
                            <div class="loader">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                        </div>
                    </section>
                @endforeach
            </div>
            <div class="searchAppend col-md-12"></div>
        </div>
    </div>
</div>

<script language='javascript'>

function LoadImages()
{
    var posters =  document.getElementsByClassName("thumbnail_video");
    for(i = 0 ; i < posters.length ; i++)
    {
        url = posters[i].getAttribute('data');

        math_random = Math.floor(Math.random() * 10000);

        random_string = 'rnd='+math_random+'&w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60';

        url = url.replace('w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60',random_string);

        if (url != '') posters[i].src = url;
    }
}

function ScrollIntoView(id)
{  
    if (id==0) return;

    var ofst = 0;
    o1 = 0;
    for (i = 1 ; i < 9999 ; i++)
    {
        var obj = document.getElementById('vsec_'+i);
        if (obj==null) break;
        
        mid = obj.getAttribute('index');
        if (mid != id) 
        {
            o1 += $('#vsec_'+i).outerHeight();
            continue;
        }
        document.getElementById('myScroller').scrollTop = o1;
        return;
    } 
}

window.onload = function() 
{  
    LoadImages();  
    window.setTimeout('ScrollIntoView( {{$scrollToVideo}} )', 1000);
}

function OnSortChanged()
{
    document.videoList.submit();
}

function OnSearch(searchStr)
{
    document.getElementById('videoSearch').value = searchStr;
    document.videoList.submit();
}

</script>
<script>
  window.NickelledMissionDockSettings = {
    missionDockId: 129,
    userId: {{ $channel['id'] }}
  };

  (function(){var NickelledMissionDock=window.NickelledMissionDock=NickelledMissionDock||{};NickelledMissionDock.preloadEvents=[];NickelledMissionDock.show=function(){NickelledMissionDock.preloadEvents.push('show')};NickelledMissionDock.hide=function(){NickelledMissionDock.preloadEvents.push('hide')};var loadMD=function(){var s,f;s=document.createElement("script");s.async=true;s.src="https://cdn.nickelled.com/mission-dock.min.js";f=document.getElementsByTagName("script")[0];f.parentNode.insertBefore(s,f);};loadMD();NickelledMissionDock.show();})();
</script>
@endif
@stop

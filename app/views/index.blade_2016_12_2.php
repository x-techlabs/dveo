@extends('template.template')

@section('content')

@if(isset($videos))

{{ HTML::script('js/videos/script.js') }}

<div class="row center-block list-wrap" id="contnet-wrap">
    <div class="col-md-12 content" id="videos-col">
        <div class="title-name" style="display: block;">
            <i class="fa fa-video-camera"></i>
            <div class="title">Videos</div>
            <div class="input-group search">
                {{ Form::text('search', '', array('class' => 'form-control', 'placeholder' => 'Search', 'id' => 'videoSearch')) }}
                <span class="input-group-btn">
                    <button type="submit" class="btn"><span class="fui-search"></span></button>
                </span>
            </div>
            <a href="../channel_{{$channel['id']}}/upload" class="btn btn-block btn-lg btn-inverse plusPtnVid" title="Upload video">&plus; Upload video</a>
            <div class="clear"></div>
        </div>

        <div class="row center-block list content_list" id="container_content">
            <div class="col-md-12 searchHide height">
                @foreach($videos/*->slice(0, 5)*/ as $video)

                    <section data-video_id="{{$video->id}}" class="list_item section_video" style="position: relative;">

                        <!-- Edit Delete Buttons -->
                        <button id="{{$video->id}}" class="delete_video editDelete fr btn btn-block btn-lg btn-danger" title="Delete video">
                            <span class="fui-trash"></span>
                        </button>
                        <button id="{{$video->id}}" class="edit_video editDelete fr btn btn-block btn-lg btn-inverse" title="Edit video">
                            <span class="fui-new"></span>
                        </button>
                        <div class="clear"></div>

                        <div class="row center-block">
                            <div class="col-md-2">
                                @if($video->thumbnail_name == null)
                                    In process ...
                                @else
                                    <img src="{{$video->thumbnail_name}}" class="thumbnail_video" style='width:120px;'>  <!-- vinay added style -->
                                @endif
                            </div>

                            <div class="col-md-10">
                                <h1 class="videoTtitle">{{$video->title}}</h1>
                                <span class="duration">
                                    
                                </span>
                                <div>{{$video->storage}}</div>
                            </div>
                        </div>
                    </section>
                @endforeach

                
            </div>
            <div class="searchAppend col-md-12"></div>
        </div>
    </div>
</div>

@endif
@stop
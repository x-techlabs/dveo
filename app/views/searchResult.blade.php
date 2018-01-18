@extends('template.template')

@section('content')
    <div class="row center-block list-wrap" id="contnet-wrap">
        <div class="col-md-12 content" id = "videos-col">
            <div class="title-name">
                <i class="fa fa-video-camera"></i>
                <div class="title">
                    Search Results
                </div>
            </div>

            <div class="row center-block list content_list" id="container_content">
                <div class="col-md-12 searchHide height" id="myScroller">
                    @if(isset($results) && count($results) > 0)

                        {{ HTML::script('js/videos/script.js') }}
                        @foreach($results as $result)
                        <section data-video_id="{{ $result['id'] }}" class="list_item section_video" style="position: relative;">

                            <!-- Edit Delete Buttons -->
                            <button id="{{ $result['id'] }}" class="delete_video editDelete fr btn btn-block btn-lg btn-danger">
                                <span class="fui-trash"></span>
                            </button>
                            <button id="{{ $result['id'] }}" class="edit_video editDelete fr btn btn-block btn-lg btn-inverse">
                                <span class="fui-new"></span>
                            </button>
                            <div class="clear"></div>

                            <div class="row center-block">
                                <div class="col-md-2">
                                    <img src="{{ $result['thumbnail_name'] }}" class="thumbnail_video">
                                </div>
                                <div class="col-md-10">
                                    <h1 class="videoTtitle">{{ $result['title'] }}</h1>
                                    <span class="duration">
                                        <img src="/images/time_icon.png" style="margin-top: -4px;"> {{ $result['duration'] }}
                                    </span>
                                </div>
                            </div>
                        </section>
                        @endforeach
                    @else
                        <h5>No results found for your search</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
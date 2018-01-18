@extends('template.template')
@section('content')

<div class="row center-block height margin-top list-wrap" id="contnet-wrap">
    <div class="col-md-3 height" style="margin-top:0px;">
        <p class="title-name">Playlists</p>


        <div class="row center-block list" id="container_content">
            <div class="col-md-12">
                <hr>
                @foreach($playlists as $play)

                    <section
                        data-playlist_id="{{$play->id}}"
                        data-duration="{{$play->duration}}"
                        data-name="{{$play->title}}"
                        class="section_playlist_playout list_item   "
                    >

                        <div class="row center-block">
                            <div class="col-md-12" style="">
                                <p style="text-align: right">
                                    {{$play->title}}
                                </p>
                                <p style="text-align: right">
                                    <img src="/images/time_icon.png" style="margin-top: -4px;"> {{$play->time}}
                                </p>
                            </div>
                        </div>
                    </section>
                <hr>
                @endforeach

            </div>
        </div>
    </div>


    <div class="col-md-9" style="height: 100%">
        <div class="blocks height white-bg" id="timeline" style="height: 100%">
            <div id="time" class="time" style="display: none;">17:46:39</div>
            <div class="col-md-11 height center-block" id="timeline-playout" style="height: 100%"></div>
        </div>
    </div>
</div>
@stop
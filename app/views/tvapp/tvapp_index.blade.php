@extends('template.template')

@section('content')

<div class="row center-block height" id="contnet-wrap">

    <div class="col-md-12 list-wrap height-inherit" id="playlists">

        <div class="height-inherit playlists content">
            <div class="title-name">
                <i class="fa fa-play-circle"></i>
                <div class="title">TV App categories:</div>
                <div class="input-group searchPlay">
                    <input type="text" class="form-control" placeholder="Search" id="search-query-3">
                    <span class="input-group-btn">
                        <button type="submit" class="btn"><span class="fui-search"></span></button>
                    </span>
                </div>
                
                <div> 
                <a  style="margin-left:20px;" href="/channel_{{ $channel['id'] }}/tvapp_about_us" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvapp_about_us" title="About US">About US</a>
                </div>
             
                <div>
                <a style="margin-left:20px;" href="/channel_{{ $channel['id'] }}/tvapp_playlists" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvapp_playlists" title="Manage Videos">Manage Videos</a>
                </div>
                 
                <div>
                <a href="/channel_{{ $channel['id'] }}/tvapp_live" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvapp_live" title="Live">Live</a>
                </div>
                
                <div class="clear"></div>
            </div>


        </div>
    </div>
</div>

@stop
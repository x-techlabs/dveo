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
                <a  style="margin-left:20px;" href="/channel_{{ $channel['id'] }}/tvweb_settings" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvweb_settings" title="Settings">Settings</a>
                </div>
             
                <div>
                <a style="margin-left:20px;" href="/channel_{{ $channel['id'] }}/tvweb_playlists" class="btn btn-block btn-lg btn-inverse plusPtnCol" id="tvweb_manage_videos" title="Manage Content">Manage Content</a>
                </div>
                
                <div class="clear"></div>
            </div>


        </div>
    </div>
</div>

@stop
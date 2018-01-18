@extends('template.template')

@section('content')
{{ HTML::script('js/collections/edit_delete_collection.js') }}
<style type="text/css">
    #stype{
        float:left;
        margin-left:0px!important;
    }

</style>
<div class="row center-block list-wrap" id="contnet-wrap">
    <div class="col-md-12 content height" id="collections-col">
        {{ Form::open(array('url' => '/channel_' . BaseController::get_channel_id() . '/collections', 'class' => 'form-horizontal', 'name' => 'videoList', 'method' => 'get')) }}
        <div class="title-name" style="display: block;">
            <!-- <i class="fa fa-tags"></i>
            <div class="title">Folders</div> -->
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
            <div class="input-group searchCol">
                <input type="text" class="form-control" placeholder="Search" id="search-query-3">
                <span class="input-group-btn">
                    <button type="submit" class="btn"><span class="fui-search"></span></button>
                </span>
            </div>
-->
            <a href="#" class="btn btn-block btn-lg greenActionBtn plusPtnCol" id="add-collection">&plus; New folder</a>
<!--            <button class="btn btn-success plus" id="add-collection">+</button>-->
        </div>
        {{ Form::close() }}

        <div class="row center-block list content_list" id="container_content">
            <div class="col-md-12 height">
                @foreach($collections as $collection)

                <section data-collection_id="{{$collection->id}}" class="list_item section_collections">

                    <!-- Edit Delete Buttons -->
                    <button id="{{$collection->id}}" class="delete_collection editDelete fr btn btn-block btn-lg btn-danger" title="Delete collection">
                        <span class="fui-trash"></span>
                    </button>
                    <button id="{{$collection->id}}" class="edit_collection editDelete fr btn btn-block btn-lg btn-inverse" title="Edit collection">
                        <span class="fui-new"></span>
                    </button>
                    <div class="clear"></div>

                    <div class="row center-block" style="margin-left:20px">
                        <div class="col-md-12">
                            <h1 class="videoTtitle">{{$collection->title}}</h1>
                        </div>
                    </div>
                </section>
                @endforeach
            </div>
        </div>
        <div class="collectionData"></div>
    </div>
</div>

<script language='javascript'>

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
@stop
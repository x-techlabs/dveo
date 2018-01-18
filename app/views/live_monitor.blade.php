@extends('template.template')

@section('content')
<div class="monitor_wrapper">
    <div class="container">
        <div class="add_wrapper">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#addMonitor" id = "add_monitor" class="btn greenActionBtn pull-right">
                <i class="fa fa-plus"></i>
                Monitor
            </a>
        </div>
        <div class="row monitors_container">
            <div class="col-md-12 col-sm-12 col-xs-12">
                @if(count($monitors) > 0)
                    @foreach($monitors as $value)
                        <div class="col-md-3 monitor_item">
                            <div id = "stream_{{ $value->id }}" class="live_monitor"></div>
                            <div class="stream_title">{{ $value->title }}</div>
                            <div class="actions">
                                <a href="#updateMonitor" data-url = "{{ $value->stream_url }}" data-target = "modal" class="edit_stream" data-id = "{{ $value->id }}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="javascript:void(0)" class="delete_stream" data-id = "{{ $value->id }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <script type="text/javascript">
                                jwplayer("stream_{{ $value->id }}").setup({
                                    file: "{{ $value->stream_url }}"
                                });
                            </script>
                        </div>

                    @endforeach
                @else
                    <h4>You haven't added live monitors yet</h4>
                @endif
            </div>
        </div>

    </div>
</div>
    
<!-- Add Monitor Modal -->
<div class="modal fade" id="addMonitor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Monitor</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
                        <form action="addMonitor" method="post">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title" value="">
                            </div>
                            <div class="form-group">
                                <label for="stream_url">Stream URL:</label>
                                <input type="text" class="form-control" id="stream_url" name="stream_url" value="">
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Update Monitor Modal -->
<div class="modal fade" id="updateMonitor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Monitor</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
                        <form action="edit_stream" method="post">
                            <input type="hidden" name="stream_id" id = "stream_id">
                            <div class="form-group">
                                <label for="monitorTitle">Title:</label>
                                <input type="text" class="form-control" id="monitorTitle" name="monitorTitle" value="">
                            </div>
                            <div class="form-group">
                                <label for="monitorStream">Stream URL:</label>
                                <input type="text" class="form-control" id="monitorStream" name="monitorStream" value="">
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

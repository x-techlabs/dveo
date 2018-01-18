@extends('template.template')

@section('content')
<style type="text/css" media="screen">
	.scheduler{
        padding: 10px;
		height: auto;
		overflow: hidden;
		background-color:white;
	}
	.h2{
		font-size: 20pt;
	}
	.hover-end{padding:0;margin:0;font-size:75%;text-align:center;position:absolute;bottom:0;width:100%;opacity:.8}
</style>
<script type="text/javascript" charset="utf-8">
var loadCalendar;
	$(document).ready(function() {
		if("{{ $sstatus }}" == "success")
        {
            document.getElementById('modalContent').innerHTML = "Schedule {{ $name }} has been added successfully.";
            $('#infoModal').modal('toggle');
        } else if("{{ $sstatus }}" == "failure") {
            document.getElementById('modalContent').innerHTML = "There was a problem saving the schedule {{ $name }}. Please try again.";
            $('#infoModal').modal('toggle');
        }
        $('.fc-today-button').click(function() {
        	clearInterval(loadCalendar);});
        	loadCalendar = setInterval(calRefresh, 600);
	});
	
	function calRefresh()
	{		
		$('.fc-today-button').click();
	}
</script>

<div class="row center-block height" id="contnet-wrap">
    <div class="col-md-12 height" id="schedule">
        <div id="schedule-body" class="height content">
            <p class="title-name"><i class="fa fa-calendar"></i>Timeline
            	                <span style="float:right"><a href="/channel_{{ $channel['id'] }}/schedule" style="font-size:20pt; text-decoration:underline">Add Show</a></span>
            </p>
            <div id="dialog" title="" style="display:none;">Please select a option below.</div>
            <div class="scheduler">
                <div id="scheduler_here" class="dhx_cal_container col-md-12 height">
			    	{{ $calendar->calendar() }}
			    	{{ $calendar->script() }}
				</div>				
			</div>
		</div>
	</div>
     <!-- Modal -->
    <div id="infoModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Schedule Status</h4>
          </div>
          <div class="modal-body">
            <div id="modalContent"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
          </div>
        </div>

      </div>
    </div>
@overwrite

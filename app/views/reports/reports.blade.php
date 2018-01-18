@extends('template.template')

@section('content')
<div class="settings height content">
	<div class="title-name">
		<i class="fa fa-calendar"></i>
		<div class="title">Reports</div>
	</div>
	<div class="clear"></div>
	<div class="reports_wrapper" style='background:#ecf0f1;'>
		<h2>Streams</h2>
		<div class="streams_wrapper">
			@if(count($streams) > 0)
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Application</th>
						<th>Stream</th>
						<th>Status</th>
						<th>Protocol</th>
						<th>Video codec</th>
						<th>Audio codec</th>
						<th>Bandwidth</th>
						<th>Resolution</th>
					</tr>
				</thead>
				<tbody>
					@foreach($streams as $value)
					<tr>
						<td>{{ $value->id }}</td>
						<td>{{ $value->application }}</td>
						<td>{{ ucfirst($value->stream) }}</td>
						<td>{{ $value->status }}</td>
						<td>{{ $value->protocol }}</td>
						<td>{{ $value->video_codec }}</td>
						<td>{{ $value->audio_codec }}</td>
						<td>{{ $value->bandwidth }}</td>
						<td>{{ $value->resolution }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@else
			<h3>No report</h3>
			@endif

		</div><!-- End Streams-->
		<!-- Statictics -->
		<div class="stats_wrapper">
			<h2>Statistics</h2>
			<div id = "dateWrapper">
				<div id = "infoMessage"></div>
				<div class="row">
					<div class='col-sm-8'>
						<div class="form-group">
							<label>Start Date</label>
							<div class='input-group date' id='fromDate'>
								<input type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label>End Date</label>
							<div class='input-group date' id='toDate'>
								<input type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
						<div class="form-button">
							<button type="button" class="btn btn-success" id = "filterStats">Search</button>
						</div>

					</div>
				</div>
			</div>
			@if(count($stats) > 0)
			<table class="table table-bordered" id = "statsTable">
				<thead>
					<tr>
						<th>Date</th>
						<th>Viewers</th>
						<th>Unique viewers</th>
						<th>Smooth viewers</th>
						<th>HLS viewers</th>
						<th>HDS viewers</th>
						<!-- <th>RTSP viewers</th> -->
						<!-- <th>RTMP viewers</th> -->
						<!-- <th>Dash viewers</th> -->
						<!-- <th>DVR viewers</th> -->
						<!-- <th>PD viewers</th> -->
						<!-- <th>MGH viewers</th> -->
						<!-- <th>ICE viewers</th> -->
						<th>Message in</th>
						<th>Message out</th>
						<!-- <th>message_lost</th> -->
						<!-- <th>max_message_in_rate</th> -->
						<!-- <th>max_message_out_rate</th> -->
						<th>Total view time</th>
					</tr>
				</thead>
				<tbody>
					@foreach($stats as $item)
					<tr>
						<td>{{ $item->date }}</td>
						<td>{{ $item->viewers }}</td>
						<td>{{ $item->unique_viewers }}</td>
						<td>{{ $item->smooth_viewers }}</td>
						<td>{{ $item->hls_viewers }}</td>
						<td>{{ $item->hds_viewers }}</td>
						<!-- <td>{{ $item->rtsp_viewers }}</td> -->
						<!-- <td>{{ $item->rtmp_viewers }}</td> -->
						<!-- <td>{{ $item->dash_viewers }}</td> -->
						<!-- <td>{{ $item->dvr_viewers }}</td> -->
						<!-- <td>{{ $item->pd_viewers }}</td> -->
						<!-- <td>{{ $item->mgh_viewers }}</td> -->
						<!-- <td>{{ $item->ice_viewers }}</td> -->
						<td>{{ $item->message_in }}</td>
						<td>{{ $item->message_out }}</td>
						<!-- <td>{{ $item->message_lost }}</td> -->
						<!-- <td>{{ $item->max_message_in_rate }}</td> -->
						<!-- <td>{{ $item->max_message_out_rate }}</td> -->
						<td>{{ $item->total_view_time }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@else
			<h3>No stats</h3>
			@endif
		</div>
	</div>

</div>



<script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">
	$(function () {
		$('#fromDate, #toDate').datetimepicker({
			format: 'yyyy-mm-dd',
			startView: 'decade',
			endDate: new Date,
			minView: 'month',
			autoclose: true,
			todayHighlight: 0,
			todayBtn: 1
		});

		$('#filterStats').click(function(event) {
			var fromDate = $("#fromDate").find("input").val();
			var toDate = $("#toDate").find("input").val();
			if(toDate != '' && fromDate != ''){
				$('#infoMessage').removeClass('alert alert-danger').text('');
				$.ajax({
					url: ace.path('getStatsByDate'),
					type: 'POST',
					data: {fromDate: fromDate, toDate: toDate},
					success: function (data) {
						var response = JSON.parse(data);
						console.log(response);
						if(response.success){
							$('#statsTable tbody').empty();
							$('#infoMessage').removeClass('alert alert-danger').text('');
							var str = '';
							$.each(response.data, function(index, item) {
								str += '<tr>';
								str += '<td>'+item.date+'</td>';
								str += '<td>'+item.viewers+'</td>';
								str += '<td>'+item.unique_viewers+'</td>';
								str += '<td>'+item.smooth_viewers+'</td>';
								str += '<td>'+item.hls_viewers+'</td>';
								str += '<td>'+item.hds_viewers+'</td>';
								str += '<td>'+item.message_in+'</td>';
								str += '<td>'+item.message_out+'</td>';
								str += '<td>'+item.total_view_time+'</td>';
								str += '</tr>';
							});
							$('#statsTable tbody').append(str);
						}
						else{
							$('#statsTable tbody').empty();
							$('#infoMessage').addClass('alert alert-danger').text('No data found!');
						}
					}
				});
			}
			else{
				$('#infoMessage').addClass('alert alert-danger').text('Please choose date period');
			}
		});
	});
</script>
@stop

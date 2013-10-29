@section('schedule_module')

<div class="col-md-4 well">
	<h3 class="text-center">Schedule</h3>
	<table class="table table-striped">
		@foreach($schedule as $day)
			<tr>
				<td>{{ $day['DayString'] }}</td>
				<td class="schedule-day">
					<ul>
						@foreach($day['Items'] as $item)
							<li><a href="{{ route('show', array('name' => $item['Name']))}}"><strong>{{ $item['Name'] }}</strong> - {{ $item['EpisodeName'] }}</a></li>
						@endforeach
					</ul>
				</td>
			</tr>
		@endforeach
	</table>
</div>	

@stop
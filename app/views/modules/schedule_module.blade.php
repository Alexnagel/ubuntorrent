@section('schedule_module')

<div class="col-md-4 well">
	<h3 class="text-center">Schedule</h3>
	<table>
		@foreach($schedule as $day)
			<tr>
				<td>{{ $day['DayString'] }}</td>
				<td class="schedule-day">
					<ul>
						@foreach($day['Items'] as $item)
							<li><a href="#urltoroute">{{ $item['Name'] }} - {{ $item['EpisodeName'] }}</a></li>
						@endforeach
					</ul>
				</td>
			</tr>
		@endforeach
	</table>
</div>	

@stop
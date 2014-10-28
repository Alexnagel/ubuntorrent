@section('recently_added_module')

<div class="col-md-8">
	<div class="well">
		<h3>Recently Added</h3>
		@if (count($recently_added) > 0)
			<table class="table table-striped">
				<tr>
					<th>Show</th>
					<th>Title</th>
					<th>Season</th>
					<th>Episode</th>
					<th>Added</th>
				</tr>
				@foreach($recently_added as $item)
					<tr>
						<td><a href="{{ route('show', array('name' => $item->show_name)) }}">{{ $item->show_name }}</a></td>
						<td>
							<a href="{{ route('episode', array('name' => $item->show_name, 'season' => $item->season, 'episode' => $item->episode)) }}">
								{{ $item->episode_title }}
							</a>
						</td>
						<td>{{ $item->season }}</td>
						<td>{{ $item->episode }}</td>
						<td>{{ $item->date_added->format('d-m-Y') }}
					</tr>
				@endforeach
			</table>
		@endif
	</div>
</div>

@stop
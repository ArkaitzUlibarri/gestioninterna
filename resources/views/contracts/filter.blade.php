<form class="form-inline pull-right" method="GET" action="{{ route('contracts.index') }}">

	<input type="text" class="form-control" name="name" placeholder="Employee name" value="{{ $filter['name'] }}">

	<select name="type" class="form-control">
		<option selected="true" value="">Type</option>
		@foreach (config('options.dates') as $date)
			<option value="{{ $date }}" {{ $date == $filter['type'] ? 'selected' : '' }}>{{ ucfirst($date) }}</option>
		@endforeach
	</select>

	<button type="submit" title="Search" class="btn btn-default">
		<span class="glyphicon glyphicon-search"></span>
	</button>
</form> 
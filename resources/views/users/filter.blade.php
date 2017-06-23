<form class="form-inline pull-right" method="GET" action="{{ route('users.index') }}">

	<input type="hidden" name="admin" type="text" value="{{ Auth::user()->isAdmin() }}">
	<input type="hidden" name="pm" type="text" value="{{ Auth::user()->isPM() }}">

	<input type="text" class="form-control" name="name" placeholder="Employee name" value="{{ $filter['name'] }}">

	<select name="type" class="form-control">
		<option selected="true" value="">Status</option>
		@foreach (config('options.dates') as $date)
			<option value="{{ $date }}" {{ $date == $filter['type'] ? 'selected' : '' }}>{{ ucfirst($date) }}</option>
		@endforeach
	</select>

	<button type="submit" title="Search" class="btn btn-default">
		<span class="glyphicon glyphicon-search"></span>
	</button>
</form> 

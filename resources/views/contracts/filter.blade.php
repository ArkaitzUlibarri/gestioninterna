<form class="form-inline pull-right" method="GET" action="{{ route('contracts.index') }}">

	<input type="text" class="form-control" name="name" placeholder="Employee name" value="{{ $filter['name'] }}">

	<select name="contract" class="form-control">
		<option selected="true" value="">Contract Type</option>
		@foreach ($contractTypes as $contractType)
			<option value="{{ $contractType->id }}" {{ $contractType->id == $filter['contract'] ? 'selected' : '' }}>{{ ucfirst($contractType->name) }}</option>
		@endforeach
	</select>

	<select name="status" class="form-control">
		<option selected="true" value="">Status</option>
		@foreach (config('options.dates') as $date)
			<option value="{{ $date }}" {{ $date == $filter['status'] ? 'selected' : '' }}>{{ ucfirst($date) }}</option>
		@endforeach
	</select>

	<button type="submit" title="Search" class="btn btn-default">
		<span class="glyphicon glyphicon-search"></span>
	</button>
</form> 
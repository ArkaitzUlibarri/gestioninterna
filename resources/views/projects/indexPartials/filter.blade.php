<form class="form-inline pull-right-sm" method="GET" action="{{ route('projects.index') }}">
	
	<input type="text" class="form-control input-sm" name="name" placeholder="Project name" value="{{ $filter['name'] }}">

	<select name="customer" class="form-control input-sm">
		<option selected="true" value="">Customer</option>
		@foreach ($customers as $customer)
			<option value="{{ $customer->id }}" {{ $customer->id == $filter['customer'] ? 'selected' : '' }}>{{ ucfirst($customer->name) }}</option>
		@endforeach
	</select>

	<select name="type" class="form-control input-sm">
		<option selected="true" value="">Status</option>
		@foreach (config('options.status') as $date)
			<option value="{{ $date }}" {{ $date == $filter['type'] ? 'selected' : '' }}>{{ ucfirst($date) }}</option>
		@endforeach
	</select>

	<button type="submit" title="Search" class="btn btn-default btn-sm">
		<span class="glyphicon glyphicon-search"></span> Search
	</button>
</form> 

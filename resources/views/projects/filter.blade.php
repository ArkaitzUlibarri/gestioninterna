<div class="row">
	<form class="form-inline pull-right" method="GET" action="{{ route('projects.index') }}">

		<input type="text" class="form-control" name="name" placeholder="Project name">

		<select name="customer" class="form-control">
			<option value="">Customer</option>
			@foreach ($customers as $customer)
				<option value="{{ $customer->id }}">{{ ucfirst($customer->name) }}</option>
			@endforeach
		</select>

		<select name="end date" class="form-control">
			<option value="">Type</option>
			@foreach (config('options.dates') as $date)
				<option value="{{ $date }}">{{ ucfirst($date) }}</option>
			@endforeach
		</select>

		<button type="submit" class="btn btn-default">
			<span class="glyphicon glyphicon-search"></span>
		</button>
	</form> 
</div>
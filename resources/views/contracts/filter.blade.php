<div class="form-group">
	<div class="row">
		<form class="form-inline pull-right" method="GET" action="{{ route('contracts.index') }}">

			<input type="text" class="form-control" name="name" placeholder="Employee name">

			<select name="end date" class="form-control">
				<option selected="true" disabled="disabled" value="">Type</option>
				@foreach (config('options.dates') as $date)
					<option value="{{ $date }}">{{ ucfirst($date) }}</option>
				@endforeach
			</select>

			<button type="submit" title="Search" class="btn btn-default">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</form> 
	</div>
</div>
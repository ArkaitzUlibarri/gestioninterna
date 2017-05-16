<div class="row">

	<form class="form-inline pull-right" method="GET" action="{{ route('workingreports.index') }}">

		<input type="{{ $admin ? 'text' : 'hidden' }}" name="name" class="form-control"  placeholder="Employee name" >

		<select name="date" class="form-control">
			<option selected="true" disabled="disabled" value="">Period</option>
			@foreach (config('options.periods') as $date)
				<option value="{{ $date }}">{{ ucfirst($date) }}</option>
			@endforeach
		</select>

		<select name="validation" class="form-control">
			<option selected="true" disabled="disabled" value="">Validation</option>
			@foreach (config('options.validations') as $validation)
				<option value="{{ $validation }}">{{ ucfirst($validation) }}</option>
			@endforeach
		</select>

		<button type="submit" title="Search" class="btn btn-default">
			<span class="glyphicon glyphicon-search"></span>
		</button>

	</form> 
	
</div>
<form class="form-inline pull-right" method="GET" action="{{ route('workingreports.index') }}">

	<input type="hidden" name="admin" type="text" value="{{ $auth_user->isAdmin() }}">
	<input type="hidden" name="pm" type="text" value="{{ $auth_user->isPM() }}">

	@if(Auth::user()->isPM() && count($pm_projects))
		<select name="project" class="form-control">
			<option selected="true" disabled="disabled" value="">Project</option>
			@foreach ($pm_projects as $project)
				<option value="{{ $project}}">{{ strtoupper($project) }}</option>
			@endforeach
		</select>
	@endif

	@if(Auth::user()->isAdmin() || Auth::user()->isPM())
		<input type="text" name="name" class="form-control"  placeholder="Employee name" >
	@endif

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

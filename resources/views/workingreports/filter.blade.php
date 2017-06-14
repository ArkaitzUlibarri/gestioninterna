<form class="form-inline pull-right" method="GET" action="{{ route('workingreports.index') }}">

	<input type="hidden" name="admin" type="text" value="{{ Auth::user()->isAdmin() }}">
	<input type="hidden" name="pm" type="text" value="{{ Auth::user()->isPM() }}">

	@if(! Auth::user()->isAdmin() && Auth::user()->isPM() && count($projects))
		<select name="project" class="form-control">
			<option selected="true" disabled="disabled" value="">Project</option>
			@foreach ($projects as $project)
				<option value="{{ $project }}" {{ $project == $filter['project'] ? 'selected' : '' }} >{{ strtoupper($project) }}</option>
			@endforeach
			<option value="All" {{ "All" == $filter['project'] ? 'selected' : '' }}>All</option>
		</select>
	@endif

	@if(Auth::user()->isAdmin() && count($projects))
		<select name="project" class="form-control">
			<option selected="true" value="">Project</option>
			@foreach ($projects as $project)
				<option value="{{ $project->name }}" {{ $project->name == $filter['project'] ? 'selected' : '' }} >{{ strtoupper($project->name) }}</option>
			@endforeach
			<option value="All" {{ "All" == $filter['project'] ? 'selected' : '' }} >All</option>
		</select>
	@endif

	@if(Auth::user()->isAdmin() || Auth::user()->isPM())
		<input name="name" type="text" class="form-control"  placeholder="Employee name" value="{{ $filter['name'] }}">
	@endif

	<select name="date" class="form-control">
		<option selected="true" value="">Period</option>
		@foreach (config('options.periods') as $date)
			<option value="{{ $date }}" {{ $date == $filter['date'] ? 'selected' : '' }} >{{ ucfirst($date) }}</option>
		@endforeach
	</select>

	<select name="validation" class="form-control">
		<option selected="true" value="">Validation</option>
		@foreach (config('options.validations') as $validation)
			<option value="{{ $validation }}"  {{ $validation == $filter['validation'] ? 'selected' : '' }}>{{ ucfirst($validation) }}</option>
		@endforeach
	</select>

	<button type="submit" title="Search" class="btn btn-default">
		<span class="glyphicon glyphicon-search"></span>
	</button>

</form> 

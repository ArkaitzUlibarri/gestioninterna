<form class="form-inline pull-right-sm" method="GET" action="{{ route('workingreports.index') }}">

	@if(Auth::user()->primaryRole() == 'manager' && count($projects))
		<select name="project" class="form-control input-sm">
			<option value="all" {{ "all" == $filter['project'] ? 'selected' : '' }}>All Projects</option>
			<option value="user" {{ "user" == $filter['project'] ? 'selected' : '' }} >My user</option>
			@foreach ($projects as $id => $project)
				<option value="{{ $id }}" {{ $id == $filter['project'] ? 'selected' : '' }} >{{ strtoupper($project) }}</option>
			@endforeach	
		</select>
	@endif

	@if(Auth::user()->primaryRole() == 'admin' && count($projects))
		<select name="project" class="form-control input-sm">
			<option value="all" {{ "all" == $filter['project'] ? 'selected' : '' }} >All Projects</option>
			<option value="user" {{ "user" == $filter['project'] ? 'selected' : '' }} >My user</option>
			@foreach ($projects as $project)
				<option value="{{ $project->id }}" {{ $project->id == $filter['project'] ? 'selected' : '' }} >{{ strtoupper($project->name) }}</option>
			@endforeach
			
		</select>
	@endif

	@if(Auth::user()->primaryRole() == 'admin' || Auth::user()->primaryRole() == 'manager')
		<input name="name" type="text" class="form-control input-sm"  placeholder="Employee name" value="{{ $filter['name'] }}">
	@endif

	<select name="date" class="form-control input-sm">
		<option selected="true" value="">Period</option>
		@foreach (config('options.periods') as $date)
			<option value="{{ $date }}" {{ $date == $filter['date'] ? 'selected' : '' }} >{{ ucfirst($date) }}</option>
		@endforeach
	</select>

	<!--
	<select name="validation" class="form-control input-sm">
		<option selected="true" value="">Validation</option>
		@foreach (config('options.validations') as $validation)
			<option value="{{ $validation }}"  {{ $validation == $filter['validation'] ? 'selected' : '' }}>{{ ucfirst($validation) }}</option>
		@endforeach
	</select>
	-->

	<button type="submit" title="Search" class="btn btn-default btn-sm">
		<span class="glyphicon glyphicon-search"></span> Search
	</button>

</form>
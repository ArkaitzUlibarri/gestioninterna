<form class="form-inline pull-right-sm" method="GET" action="{{ route('users.index') }}">

	<input type="hidden" name="admin" type="text" value="{{ Auth::user()->isAdmin() }}">
	<input type="hidden" name="pm" type="text" value="{{ Auth::user()->isPM() }}">

	<input type="text" class="form-control input-sm" name="name" placeholder="Employee name" value="{{ $filter['name'] }}">

	@if(Auth::user()->isAdmin())
		<select name="type" class="form-control input-sm">
			<option value="" {{ "" == $filter['type'] ? 'selected' : '' }}>Active</option>			
			<option value="inactive" {{ "inactive" == $filter['type'] ? 'selected' : '' }}>Inactive</option>
			<option value="all" {{ "all" == $filter['type'] ? 'selected' : '' }}>All</option>	
		</select>
	@endif

	<button type="submit" title="Search" class="btn btn-default btn-sm">
		<span class="glyphicon glyphicon-filter"></span> Filter
	</button>
</form> 

@extends('layouts.app')

@section('content')
	<div class="container">

		<dl class="dl-horizontal">
			<dt>Project</dt>
			<dd>{{ $group->project }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Group</dt>
			<dd>{{ $group->name }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Enabled</dt>
			<dd>{{ $group->enabled==1 ? "Yes" : "No" }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt><a class="btn btn-primary" href="{{ url('groups') }}">Back</a></dt>
		</dl>	
	</div>
@endsection
@extends('layouts.app')

@section('content')
	<div class="container">

		<dl class="dl-horizontal">
			<dt>Project name</dt>
			<dd>{{ strtoupper($project->name) }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Description</dt>
			<dd>{{ $project->description }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Customer</dt>
			<dd>{{ strtoupper($project->customer->name)}}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Start date</dt>
			<dd>{{ $project->start_date}}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>End date</dt>
			<dd>{{empty($project->end_date) ? "In progress" : $project->end_date}}</dd>
		</dl>	

		@include('groups.showProjects')

		<dl class="dl-horizontal">
			<dt><a class="btn btn-default" href="{{ url('projects') }}">Back</a></dt>
		</dl>	
	</div>
@endsection
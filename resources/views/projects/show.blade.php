@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('projects.card')
    </div>
</div>

@if(count($project->groups)>0)
	<div class="row">
		<div class="col-xs-12 col-sm-offset-1 col-sm-10">
			@include('groups.showProjects')
		</div>
	</div>
@endif

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <a title="Back" class="btn btn-default" href="{{ url('projects') }}">Back</a>
    </div>
</div>

@endsection
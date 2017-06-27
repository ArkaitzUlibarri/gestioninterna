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
			@include('projects.groups')
		</div>
	</div>
@endif

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <div class ="form-group pull-right">
            <a class="btn btn-default custom-btn-width" href="{{ url('projects') }}">Back</a>
        </div>
    </div>
</div>

@endsection
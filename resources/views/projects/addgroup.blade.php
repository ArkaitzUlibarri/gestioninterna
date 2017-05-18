@extends('layouts.app')

@section('content')	

		<div id="project" class ="container">

			<div class="row">

				<div class ="form-group col-xs-12 col-sm-4">
					<h1>{{ strtoupper($project->name) }}</h1>				
				</div>
		
			</div>

			<hr>
				<h3>Groups for this project</h3>
			<hr>

			<template v-for="(group, index) in groups">
				<group-template :group="group" :index="index"></group-template>
			</template>

			<hr>

			<div class="row">
				<div class ="form-group col-xs-12 col-sm-4">
					<span v-if="editIndex==-1">
						<label>Adding a new group</label>	
        			</span>		
        			<span v-if="editIndex!=-1">
						<label>Editing group @{{editIndex +1}}</label>	
        			</span>	
				</div>
				
			</div>

			<div class="row">		
				<div class="col-lg-4">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="checkbox" v-model="newGroup.enabled">
						</span>
						<input type="text" class="form-control" placeholder="Group Name" v-model="newGroup.name">
					</div>
				</div>
			</div>

			<br> 

			<div class="form-group">	
				<button title="Save Group" class="btn btn-primary" :disabled="newGroup.name==''" v-on:click="saveGroup">
					<span class="glyphicon glyphicon-floppy-disk"></span> Save
				</button>
				<button title="New Group" class="btn btn-primary" v-show="editIndex!=-1" v-on:click="initializeGroup">
					<span class="glyphicon glyphicon-plus"></span> New Group
				</button>
			</div>	

			<hr>
	
			<!--<a class="btn btn-primary" href="{{ url('projects') }}"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>-->
		</div>

		

@endsection

@push('script-bottom')
<script type="text/javascript">
	var id = '{{ $project->id }}';
</script>

<script src="{{ asset('js/projects.js') }}"></script>
@endpush
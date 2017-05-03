@extends('layouts.app')

@section('content')
<div id="report" class="container">

	<div class="row">

		<div class ="form-group col-xs-12 col-sm-4">
			<h1>Working Report</h1>				
		</div>

		<div class="form-group col-xs-12 col-sm-2 pull-right">
			<label>Type</label>
			<select class="form-control" v-model="newTask.jobType" >
					<option value="-">-</option>
					<option value="in-person">In-person</option>
					<option value="remote">Remote</option>
					<option value="teleworking">Teleworking</option>
			</select>
		</div>
		
		<div class="form-group col-xs-12 col-sm-2 pull-right">
			<label>Date</label>
			<input name="created_at" type ="date" class="form-control" value="{{$date}}">
		</div>
		
	</div>

	<hr>

	<div>
		<h3>Task</h3>

		<div class="row">
			<div class="form-group col-xs-12 col-sm-4 pull-left">
				<label>Activity</label>
				<select class="form-control" v-model="newTask.activity" v-on:change="refreshForm" >
					<option value="-">-</option>
					<option value="Absence">Absence</option>
					<option value="Project">Project</option>
					<option value="Training">Training</option>
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-4 ">
				<label>Project</label>
				<select class="form-control" :disabled="newTask.activity != 'Project'" v-model="newTask.project">
					<option value="-">-</option>
					<option value="VFE">VFE</option>
					<option value="ANE">ANE</option>
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-4">
				<label>Group</label>
				<select class="form-control" :disabled="newTask.activity != 'Project'" v-model="newTask.group">
					<option value="-">-</option>
					<option value="Diseno">Diseno</option>
					<option value="OPT">OPT</option>
				</select>
			</div>
		</div>

		<div class="row">

			<div class="form-group col-xs-12 col-sm-4">
				<label>Absence</label>
				<select class="form-control" :disabled="newTask.activity != 'Absence'" v-model="newTask.absence">
					<option value="-">-</option>
					<option value="Medico">Medico</option>
					<option value="Boda">Boda</option>
					<option value="Examen">Examen</option>
					<option value="Vacaciones">Vacaciones</option>
				</select>
			</div>	

			<div class="form-group col-xs-12 col-sm-4">
				<label>Training</label>
				<select class="form-control" :disabled="newTask.activity != 'Training'" v-model="newTask.training">
					<option value="-">-</option>
					<option value="Curso">Curso</option>
					<option value="Puesto">Puesto</option>
					<option value="Desarrollo">Desarrollo</option>
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-4 pull-right">
				<label>Time (15 mins)</label>
				<input type="number" min=0 max=33 class="form-control" placeholder="Time" v-model="newTask.time">
			</div>
		</div>

		<div class="form-group">
			<label>Comments</label>
			<textarea class="form-control" rows="2" v-model="newTask.comments"></textarea>
		</div>
		
		<div class="form-group">	
			<button title="New Task" class="btn btn-primary" :disabled="taskValidated==false" v-on:click="addTask" v-show="editIndex==-1">
				<span class="glyphicon glyphicon-plus"></span> New Task
			</button>
			<button title="Edit Task" class="btn btn-primary" :disabled="taskValidated==false" v-on:click="editTask" v-show="editIndex!=-1">
				<span class="glyphicon glyphicon-edit"></span> Edit Task
			</button>
		</div>	

	</div>

	<hr>
		<h3 class="dl-horizontal">			
			<label>Total Hours:</label>
			<label>@{{totalTime}}</label>
			<button title="Validate" class="btn btn-success pull-right" v-on:click="validateTask">
				<span class="glyphicon glyphicon-ok"></span> Validate
			</button>
		</h3>
		
	<hr>

	<template v-for="(task, index) in tasks">

		<template>
			<task-template :task="task" :index="index"></task-template>
		</template>

	</template>

	<hr>

	<a title="Cancel" class="btn btn-danger" href="{{ url('workingreports') }}">
		<span class="glyphicon glyphicon-arrow-left"></span> Cancel
	</a>
	
	<button title="Save" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>

	@include('layouts.errors')
</div>


@endsection

@push('script-bottom')
	<script type="text/javascript">
		var reportdate= '{{ $date }}';
		var user= '{{ $id }}';
	</script>
	
    <script src="{{ asset('js/reports.js') }}"></script>
@endpush

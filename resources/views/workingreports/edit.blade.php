@extends('layouts.app')

@section('content')

<div id="report" class="container">

	<div class="row">

		<div class ="form-group col-xs-12 col-sm-4">
			<h1>Working Report</h1>				
		</div>

		<div class="form-group col-xs-12 col-sm-2 pull-right">
			<label>Type</label>
			<select class="form-control" v-model="newTask.job_type" >
					<option value="-">-</option>
					@foreach(config('options.typeOfJob') as $type)				
						<option value="{{$type}}">{{ucfirst($type)}}</option>
					@endforeach
			</select>
		</div>
		
		<div class="form-group col-xs-12 col-sm-2 pull-right">
			<label>Date</label>
			<input name="created_at" type ="date" class="form-control" v-model="reportdate" v-on:change="fetchData">
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
					@foreach(config('options.activities') as $activity)				
						<option value="{{$activity}}">{{ucfirst($activity)}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-4 ">
				<label>Project</label>
				<select class="form-control" :disabled="newTask.activity != 'project'" v-on:change="groupsRefresh" v-model="newTask.project">
					<option value="-">-</option>
					<template v-for="(element, index) in projectList">
						<option :project="element" :index="index">@{{element}}</option>
					</template>
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-4">
				<label>Group</label>
				<select class="form-control" :disabled="newTask.activity != 'project'" v-model="newTask.group">
					<option value="-">-</option>
					<template v-for="(group, index) in groupList">
						<option :group="group" :index="index">@{{group}}</option>
					</template>	
				</select>
			</div>

		</div>

		<div class="row">

			<div class="form-group col-xs-12 col-sm-4">
				<label>Absence</label>
				<select class="form-control" :disabled="newTask.activity != 'absence'" v-model="newTask.absence">
					<option value="-">-</option>
					@foreach($absences as $absence)				
					<option value="{{$absence->name}}">{{ucfirst($absence->name)}}</option>
					@endforeach
				</select>
			</div>	

			<div class="form-group col-xs-12 col-sm-4">
				<label>Training</label>
				<select class="form-control" :disabled="newTask.activity != 'training'" v-model="newTask.training_type">
					<option value="-">-</option>
					@foreach(config('options.training') as $training)				
					<option value="{{$training}}">{{ucfirst($training)}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-4 pull-right">
				<label>Time (15 mins)</label>
				<input type="number" min=0 max=33 class="form-control" placeholder="Time" v-model="newTask.time_slots">
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
			<task-template :task="task" :index="index"></task-template>
	</template>

	<hr>

	<a title="Exit" class="btn btn-danger" href="{{ url('workingreports') }}">
		<span class="glyphicon glyphicon-arrow-left"></span> Exit
	</a>

	@include('layouts.errors')
	<pre>@{{$data.newTask}}</pre>

</div>


@endsection

@push('script-bottom')
	<script type="text/javascript">
		var reportdate= '{{ $date }}';
		var user= '{{ $user_id }}';
		var absences=<?php echo json_encode($absences);?>;
		var groupProjects=<?php echo json_encode($groupProjects);?>;
	</script>
	
    <script src="{{ asset('js/reports.js') }}"></script>
@endpush

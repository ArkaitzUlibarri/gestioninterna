@extends('layouts.app')

@section('content')

<div id="report" class="container">

	<div class="row">
		<div class ="form-group col-xs-12 col-sm-4">
			<h2>Working Report</h2>				
		</div>

		<div class="form-group col-xs-12 col-sm-2 pull-right">
			<label>Date</label>
			<input id="datefield" name="created_at" type ="date" class="form-control" min="2017-01-01" v-model="reportdate" v-on:change="fetchData">
		</div>

		<h3 class="dl-horizontal">			
			<span class="label label-danger">TOTAL HOURS: @{{totalTime}}</span>
		</h3>
	</div>


		<span v-for="(task, index) in tasks">
			<task-template :task="task" :index="index"></task-template>
		</span>


	<hr>

	<div class="row">
		<h4 v-if="editIndex==-1">Adding a new task</h4>	
		<h4 v-if="editIndex!=-1">Editing task @{{editIndex +1}}</h4>	

		<div class="row">
			<div class="form-group col-xs-12 col-sm-3">
				<label>Activity</label>
				<select class="form-control" v-model="newTask.activity" v-on:change="refreshForm" >
					<option value="">-</option>
					@foreach(config('options.activities') as $activity)				
						<option value="{{$activity}}">{{ucfirst($activity)}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3">
				<label>Time (Hours)</label>
				<input type="number" min=0 max=8.25 step="0.25" class="form-control" placeholder="Time" v-model="newTask.time">
			</div>

			<div class="form-group col-xs-12 col-sm-3 ">
				<label>Type</label>
				<select class="form-control" v-model="newTask.job_type" >
					<option value="">-</option>
					@foreach(config('options.typeOfJob') as $type)				
						<option value="{{$type}}">{{ucfirst($type)}}</option>
					@endforeach
				</select>
			</div>

		</div>

		<div class="row">

			<div class="form-group col-xs-12 col-sm-6 " v-show="newTask.activity == 'project'">
				<label>Project</label>
				<select class="form-control" v-on:change="groupsRefresh" v-model="newTask.project">
					<option value="">-</option>
					<template v-for="(element, index) in projectList">
						<option :project="element" :index="index">@{{element}}</option>
					</template>
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3" v-show="newTask.project != ''">
				<label>Group</label>
				<select class="form-control" v-on:change="categoriesLoad" v-model="newTask.group" >
					<option value="">-</option>
					<template v-for="(group, index) in groupList">
						<option :group="group" :index="index">@{{group}}</option>
					</template>	
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3" v-show=" newTask.group != '' ">
				<label>Category</label>
				<select class="form-control" v-model="newTask.category">
					<option value="">-</option>
					<template v-for="(element, index) in categoryList">
						<option :category="element" :index="index">@{{element}}</option>
					</template>	
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3" v-show="newTask.activity == 'absence'">
				<label>Absence</label>
				<select class="form-control" v-model="newTask.absence">
					<option value="">-</option>
					@foreach($absences as $absence)				
					<option value="{{$absence->name}}">{{ucfirst($absence->name)}}</option>
					@endforeach
				</select>
			</div>	

			<div class="form-group col-xs-12 col-sm-3" v-show="newTask.activity == 'training'">
				<label>Training</label>
				<select class="form-control" v-model="newTask.training_type">
					<option value="">-</option>
					@foreach(config('options.training') as $training)				
					<option value="{{$training}}">{{ucfirst($training)}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3" v-show="newTask.training_type == 'course'">
				<label>Course</label>
				<select class="form-control" disabled>
					<option value="">-</option>
				</select>
			</div>

		</div>

		<div class="form-group">
			<label>Comments</label>
			<textarea class="form-control" rows="2" v-model="newTask.comments"></textarea>
		</div>
		
		<div class="form-group">	
			<button title="Save Task" class="btn btn-primary" :disabled="formTaskFilled==false" v-on:click="addTask" v-show="editIndex==-1">
				<span class="glyphicon glyphicon-floppy-disk"></span> Save
			</button>
			<button title="Save Task" class="btn btn-primary" :disabled="formTaskFilled==false" v-on:click="editTask" v-show="editIndex!=-1">
				<span class="glyphicon glyphicon-floppy-disk"></span> Save
			</button>
			<button title="New Task" class="btn btn-primary" v-show="editIndex!=-1" v-on:click="initializeTask">
				<span class="glyphicon glyphicon-plus"></span> New Task
			</button>
		</div>	

	</div>

	<hr>


	<!--
	<a title="Exit" class="btn btn-danger" href="{{ url('workingreports') }}">
		<span class="glyphicon glyphicon-arrow-left"></span> Exit
	</a>
	-->

	@include('layouts.errors')
	<pre>@{{$data.newTask}}</pre>
	<pre>@{{$data.categoryList}}</pre>
	<pre>@{{$data.categories}}</pre>
</div>


@endsection

@push('script-bottom')
	<script type = "text/javascript">
		var reportdate    = '{{ $date }}';
		var user       = '{{ $user_id }}';
		var role          = '{{ $auth_user->role }}';
		var absences      = <?php echo json_encode($absences);?>;
		var groupProjects = <?php echo json_encode($groupProjects);?>;
		var categories    = <?php echo json_encode($categories);?>;
	</script>
	
    <script src="{{ asset('js/reports.js') }}"></script>
@endpush

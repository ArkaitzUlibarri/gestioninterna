@extends('layouts.app')

@section('content')

<div id="report" class="container">

	<div class="row">
		<div class ="col-xs-12 col-sm-4">
			<h2>Working Report</h2>				
		</div>
	</div>

	<span v-if="contract">	

		<div class="panel panel-primary">

			<div class="panel-heading">
			    Report Details
			 </div>

			<div class="panel-body">
				<div class="row">

					<div class="col-xs-12 col-sm-3">	
						<label>Employee</label>
						<input class="form-control" type="text" placeholder="{{$report_user->fullname}}" readonly>
					</div>	

					<div class="col-xs-12 col-sm-2">
						<label>Date</label>
						<input id="datefield" name="created_at" type ="date" class="form-control" min="2017-01-01" placeholder="yyyy-mm-dd" v-model="reportdate" v-on:blur="dateValidation">
					</div>				

					<div class="col-xs-12 col-sm-2">
						<label>Day</label>
						<input class="form-control" type="text" v-bind:placeholder="reportDayWeek" readonly>
					</div>	

					<div class="col-xs-12 col-sm-1">
						<label>Week</label>
						<input class="form-control" type="text" v-bind:placeholder="week" readonly>
					</div>	

					<!--
					<div class="col-xs-12 col-sm-1">
						<label>Exp.Hours</label>
						<input class="form-control" type="text" v-bind:placeholder="expHours" readonly>
					</div>	
					-->

					<div class="col-xs-12 col-sm-2" v-if="! validatedTasks">
						<label>Last tasks</label>
						<button class="btn btn-primary" title="Copy" v-on:click="copyTasks" v-bind:disabled="tasks.length != 0">
							Copy last report
						</button>
					</div>	

				</div>
			</div>
			
		</div>
			
		<div class="panel panel-primary">

			  <div class="panel-heading">
			    	Tasks
			  </div>

			  <div class="panel-body">

					<div class="row">
						<div class="col-xs-12">
							<span v-for="(task, index) in tasks">
								<span v-if="editIndex == index">
									<task-template :task="task" :index="index" :prop="true"></task-template>
								</span>
								<span v-else>
									<task-template :task="task" :index="index" :prop="false"></task-template>
								</span>
							</span>
						</div>
					</div>

			  </div>

			  <div class="panel-footer">
			  		<b>TOTAL HOURS: @{{totalTime}}</b>
			  </div>

		</div>

		<div class="row">
			<div class="col-xs-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<span v-if="editIndex==-1">Adding a new task</span>	
					<span v-if="editIndex!=-1">Editing task @{{editIndex +1}}</span>	
				</div>
			
				<div class="panel-body">
					<div class="row">
						<div class="form-group col-xs-12 col-sm-3">
							<label>Activity</label>
							<select class="form-control" v-model="newTask.activity" v-on:change="refreshForm" v-bind:disabled="validatedTasks">
								<option value="">-</option>
								@foreach(config('options.activities') as $activity)				
									<option value="{{$activity}}">{{ucfirst($activity)}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-xs-12 col-sm-3">
							<label>Time (Hours)</label>
							<input type="number" min=0 max=8.25 step="0.25" class="form-control" placeholder="Time" v-model="newTask.time" v-bind:disabled="validatedTasks">
						</div>

						<div class="form-group col-xs-12 col-sm-3 ">
							<label>Type</label>
							<select class="form-control" v-model="newTask.job_type" v-bind:disabled="validatedTasks">
								<option value="">-</option>
								@foreach(config('options.types') as $type)				
									<option value="{{$type}}">{{ucfirst($type)}}</option>
								@endforeach		
								@if($report_user->hasTeleworking())				
									<option value="teleworking">Teleworking</option>
								@endif
							</select>
						</div>

					</div>

					<div class="row">

						<div class="form-group col-xs-12 col-sm-6 " v-show="newTask.activity == 'project'">
							<label>Project</label>
							<select class="form-control" v-on:change="groupsRefresh" v-model="newTask.project" v-bind:disabled="validatedTasks">
								<option value="">-</option>
								<template v-for="(element, index) in projectList">
									<option :project="element" :index="index">@{{element}}</option>
								</template>
							</select>
						</div>

						<div class="form-group col-xs-12 col-sm-3" v-show="newTask.project != ''">
							<label>Group</label>
							<select class="form-control" v-model="newTask.group" v-bind:disabled="validatedTasks">
								<option value="">-</option>
								<template v-for="(group, index) in groupList">
									<option :group="group" :index="index">@{{group}}</option>
								</template>	
							</select>
						</div>

						<div class="form-group col-xs-12 col-sm-3" v-show=" newTask.group != '' ">
							<label>Level Position</label>
							<select class="form-control" v-model="newTask.category" v-bind:disabled="validatedTasks">
								<option value="">-</option>
								<template v-for="(element, index) in categoryList">
									<option :category="element" :index="index">@{{element}}</option>
								</template>	
							</select>
						</div>

						<div class="form-group col-xs-12 col-sm-3" v-show="newTask.activity == 'absence'">
							<label>Absence</label>
							<select class="form-control" v-model="newTask.absence" v-bind:disabled="validatedTasks">
								<option value="">-</option>
								@foreach($absences as $absence)				
								<option value="{{$absence->name}}">{{ucfirst($absence->name)}}</option>
								@endforeach
							</select>
						</div>	

						<div class="form-group col-xs-12 col-sm-3" v-show="newTask.activity == 'training'">
							<label>Training</label>
							<select class="form-control" v-model="newTask.training_type" v-bind:disabled="validatedTasks">
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
						<textarea class="form-control" rows="2" v-model="newTask.comments" v-bind:disabled="validatedTasks"></textarea>
					</div>
					
					<div class="form-group" v-if="! validatedTasks">	
						<button title="Save Task" class="btn btn-primary" :disabled="formTaskFilled==false" v-on:click="addTask" v-show="editIndex==-1">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save
						</button>
						<button title="Update Task" class="btn btn-primary" :disabled="formTaskFilled==false" v-on:click="editTask" v-show="editIndex!=-1">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save
						</button>
						<button title="New Task" class="btn btn-primary" v-show="editIndex!=-1" v-on:click="initializeTask">
							New Task
						</button>
					</div>
				</div>
			</div>
			</div>
		</div>

	</span>

	<span v-else="contract">

		<div class="panel panel-danger">

			  <div class="panel-heading">
			    	<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Error
			  </div>

			  <div class="panel-body">
			  		User without an active contract
			  </div>

		</div>
	
	</span>

	<a class="btn btn-default" href="{{ url('workingreports') }}">Back</a>

	@include('layouts.errors')

</div>


@endsection

@push('script-bottom')
	<script type = "text/javascript">
		var reportdate    = '{{ $date }}';
		var role          = '{{ $auth_user->role }}';
		var report_user   = <?php echo json_encode($report_user);?>;
		var absences      = <?php echo json_encode($absences);?>;
		var groupProjects = <?php echo json_encode($groupProjects);?>;
		var categories    = <?php echo json_encode($categories);?>;
		var user_contract = <?php echo json_encode($contract);?>;
	</script>
	
    <script src="{{ asset('js/reports.js') }}"></script>
@endpush

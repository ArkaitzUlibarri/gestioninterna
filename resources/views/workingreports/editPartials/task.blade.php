<div class="panel panel-primary">

	<div class="panel-heading">
		<span v-if="editIndex==-1">Adding a new task</span>	
		<span v-if="editIndex!=-1">Editing task</span>	
	</div>

	<div class="panel-body">

		<div class="row">
			<div class="form-group col-xs-12 col-sm-3">
				<label>Activity</label>
				<select class="form-control input-sm" v-model="newTask.activity" v-on:change="refreshForm" v-bind:disabled="validatedTasks">
					<option value="">-</option>
					@foreach(\App\WorkingReport::ACTIVITIES as $activity)
						<option value="{{$activity}}">{{ucfirst($activity)}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3">
				<label>Time (Hours)</label>
				<input type="number" min=0 max=8.25 step="0.25" class="form-control input-sm" placeholder="Time" v-model="newTask.time" v-bind:disabled="validatedTasks">
			</div>

			<div class="form-group col-xs-12 col-sm-3 " v-show="newTask.activity != 'absence'">
				<label>Type</label>
				<select class="form-control input-sm" v-model="newTask.job_type" v-bind:disabled="validatedTasks">
					<option value="">-</option>
					@foreach(config('options.types') as $type)				
						<option value="{{$type}}">{{ucfirst($type)}}</option>
					@endforeach						
					<option v-if="teleworking[reportDayWeek]" value="teleworking">Teleworking</option>							
				</select>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-xs-12 col-sm-6 " v-show="newTask.activity == 'project'">
				<label>Project</label>
				<select class="form-control input-sm" v-on:change="groupsRefresh" v-model="newTask.project" v-bind:disabled="validatedTasks">
					<option value="">-</option>
					<template v-for="(element, index) in projectList">
						<option :project="element" :index="index">@{{element}}</option>
					</template>
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3" v-show="newTask.project != ''">
				<label>Group</label>
				<select class="form-control input-sm" v-model="newTask.group" v-bind:disabled="validatedTasks">
					<option value="">-</option>
					<template v-for="(group, index) in groupList">
						<option :group="group" :index="index">@{{group}}</option>
					</template>	
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3" v-show=" newTask.group != ''">
				<label>Level Position</label>
				<select class="form-control input-sm" v-model="newTask.category" v-bind:disabled="validatedTasks">
					<option value="">-</option>
					<template v-for="(element, index) in categoryList">
						<option :value="element" :category="element" :index="index">@{{element}}</option>
					</template>	
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3" v-show="newTask.activity == 'absence'">
				<label>Absence</label>
				<select class="form-control input-sm" v-model="newTask.absence" v-bind:disabled="validatedTasks">
					<option value="">-</option>
					@foreach($absences as $absence)				
						<option value="{{$absence->name}}">{{ucfirst($absence->name)}}</option>
					@endforeach
				</select>
			</div>	

			<div class="form-group col-xs-12 col-sm-3" v-show="newTask.activity == 'training'">
				<label>Training</label>
				<select class="form-control input-sm" v-model="newTask.training_type" v-bind:disabled="validatedTasks">
					<option value="">-</option>
					@foreach(config('options.training') as $training)				
						<option value="{{$training}}">{{ucfirst($training)}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-xs-12 col-sm-3" v-show="newTask.training_type == 'course'">
				<label>Course</label>
				<select class="form-control input-sm" disabled>
					<option value="">-</option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<textarea class="form-control" rows="2" v-model="newTask.comments" v-bind:disabled="validatedTasks" placeholder="Comments"></textarea>
		</div>
		
		<div class="form-group" v-if="! validatedTasks">	
			<button class="btn btn-primary btn-sm" v-on:click="addUpdateTask" :disabled="formTaskFilled==false">
				<span class="glyphicon glyphicon-floppy-disk"></span>
				<span v-if="editIndex!=-1">Update</span>
				<span v-if="editIndex==-1">Save</span>
			</button>
			<button title="New Task" class="btn btn-primary btn-sm" v-on:click="initializeTask" v-show="editIndex!=-1">
				<span class="glyphicon glyphicon-plus-sign"></span> New Task
			</button>
		</div>
		
	</div>
</div>

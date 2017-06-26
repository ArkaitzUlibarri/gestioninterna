@extends('layouts.app')

@section('content')

<div id="report">

	<div class="row">
		<div class ="col-xs-12">
			<h2>Working Report</h2>
		</div>
	</div>

	<hr style="margin-top: 5px;">

	<span v-if="contract">	

		<div class="panel panel-primary">

			<div class="panel-heading">Report Details</div>

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
				@include('workingreports.task')
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

	@include('layouts.errors')

	<div class ="form-group pull-right">
		<a class="btn btn-default" href="{{ url('workingreports') }}">Back</a>
	</div>
</div>

@endsection

@push('script-bottom')
	<script type = "text/javascript">
		var reportdate = '{{ $date }}';
		var role = '{{ $auth_user->role }}';
		var report_user = <?php echo json_encode($report_user);?>;
		var teleworking = <?php echo json_encode($teleworking);?>;
		var absences = <?php echo json_encode($absences);?>;
		var groupProjects = <?php echo json_encode($groupProjects);?>;
		var categories = <?php echo json_encode($categories);?>;
		var user_contract = <?php echo json_encode($contract);?>;
	</script>
	
    <script src="{{ asset('js/reports.js') }}"></script>
@endpush

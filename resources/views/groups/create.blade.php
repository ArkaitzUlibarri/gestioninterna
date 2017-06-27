@extends('layouts.app')

@section('content')	

<div id="project" class ="container">

	<div class="row">
		<div class ="form-group col-xs-12 col-sm-4">
			<h2>{{ strtoupper($project->name) }}</h2>				
		</div>
	</div>

	<div class="panel panel-primary">

		<div class="panel-heading">
		    Project Details
		 </div>

		<div class="panel-body">
			<div class="row">

				<div class="col-xs-12 col-sm-6">	
					<label>Project</label>
					<input class="form-control" type="text" placeholder="{{$project->name}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-2">
					<label>Customer</label>
					<input class="form-control" type ="text" placeholder="{{strtoupper ($project->customer->name)}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-2">
					<label>Start date</label>
					<input class="form-control" type ="date" value="{{$project->start_date}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-2">
					<label>End date</label>
					<input class="form-control" type ="date" value="{{$project->end_date}}" readonly>
				</div>			

			</div>
		</div>
		
	</div>

	<div class="panel panel-primary">

		  <div class="panel-heading">
		    	Groups for this project
		  </div>

		  <div class="panel-body">
				<div class="row">
					
					<span v-for="(group, index) in groups">		
						<span v-if="editIndex == index">
							<group-template :group="group" :index="index" :prop="true"></group-template>
						</span>
						<span v-else>
							<group-template :group="group" :index="index" :prop="false"></group-template>
						</span>
					</span>
					
				</div>
		  </div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<span v-if="editIndex==-1">Adding a new group</span>		
        			<span v-if="editIndex!=-1">Editing group</span>	
				</div>

				<div class="panel-body">

					<div class="row">	

						<div class="form-group col-lg-4">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" v-model="newGroup.enabled">
								</span>
								<input type="text" class="form-control" placeholder="Group Name" v-model="newGroup.name">
							</div>
						</div>

						<div class="form-group">	
							<button title="Save Group" class="btn btn-primary" :disabled="newGroup.name==''" v-on:click="saveGroup">
								<span class="glyphicon glyphicon-floppy-disk"></span> Save
							</button>
							<button title="New Group" class="btn btn-primary" v-show="editIndex!=-1" v-on:click="initializeGroup">
								New Group
							</button>
						</div>	

					</div>

				</div>
			</div>
		</div>
	</div>

	<a class="btn btn-default" href="{{ url('projects') }}">Back</a>
</div>

@endsection

@push('script-bottom')
<script type="text/javascript">
	var url = "{{ url('/') }}";
	var id = '{{ $project->id }}';
</script>

<script src="{{ asset('js/projects.js') }}"></script>
@endpush
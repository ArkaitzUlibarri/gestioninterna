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
		    	Groups for this project
		  </div>

		  <div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<span v-for="(group, index) in groups">
							<group-template :group="group" :index="index"></group-template>
						</span>
					</div>
				</div>
		  </div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<span v-if="editIndex==-1">Adding a new group</span>		
        			<span v-if="editIndex!=-1">Editing group @{{editIndex +1}}</span>	
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

	<a class="btn btn-primary" href="{{ url('projects') }}">Back</a>
</div>

@endsection

@push('script-bottom')
<script type="text/javascript">
	var id = '{{ $project->id }}';
</script>

<script src="{{ asset('js/projects.js') }}"></script>
@endpush
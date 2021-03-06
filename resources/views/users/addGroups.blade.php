@extends('layouts.app')

@section('content')	

	<div id="groups" class ="container">

		@include('users.generalPartials.card')

		<div class="panel panel-primary">

			  <div class="panel-heading">Groups</div>

			  <div class="panel-body">
					<div class="row">
						<span v-for="(item, index) in array">
							<group-template :item="item" :index="index"></group-template>
						</span>
					</div>
			  </div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-primary">

					<div class="panel-heading">
						<span>Adding group to a user</span>	
					</div>

					<div class="panel-body">			

						<form class="form-inline">	
					
							<div class="form-group">
								<label>Project</label>
								<select class="form-control input-sm" v-on:change="groupsRefresh" v-model="newGroupUser.project">
									<option value="">-</option>
									<template v-for="(project, index) in projectList">
										<option :project="project" :index="index">@{{project}}</option>
									</template>
								</select>	
							</div>		
							
							<div class="form-group">
								<label>Group</label>
								<select class="form-control input-sm" v-model="newGroupUser.group">
									<option value="">-</option>
									<template v-for="(group, index) in groupList">
										<option :group="group" :index="index">@{{group}}</option>
									</template>	
								</select>	
							</div>		
							
							<div class="form-group">
								<button title="Save" class="btn btn-primary btn-sm" :disabled="formFilled==false" v-on:click.prevent="saveGroup">
									<span class="glyphicon glyphicon-floppy-disk"></span> Save
								</button>
							</div>
								
						</form>
						
					</div>
				</div>
			</div>
		</div>

		<div class ="form-group pull-right">
			<a class="btn btn-default btn-sm custom-btn-width" href="{{ url('users') }}">Back</a>
		</div>

	</div>

@endsection

@push('script-bottom')
	<script type="text/javascript">
		var url = "{{ url('/') }}";
		var user = <?php echo json_encode($user);?>;
		var groupProjects = <?php echo json_encode($groupProjects);?>;
	</script>

	<script src="{{ asset('js/groupsUser.js') }}"></script>
@endpush
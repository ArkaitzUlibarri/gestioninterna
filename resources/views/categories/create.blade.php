@extends('layouts.app')

@section('content')	

<div id="categories" class ="container">

	<div class="row">
		<div class ="form-group col-xs-12 col-sm-4">
			<h2>{{ ucfirst($user->fullname) }}</h2>				
		</div>
	</div>

	<div class="panel panel-primary">

		<div class="panel-heading">
		    User Details
		 </div>

		<div class="panel-body">
			<div class="row">

				<div class="col-xs-12 col-sm-6">	
					<label>Employee</label>
					<input class="form-control" type="text" placeholder="{{$user->fullname}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-4">
					<label>Email</label>
					<input class="form-control" type ="text" placeholder="{{$user->email}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-2">
					<label>Role</label>
					<input class="form-control" type ="text" placeholder="{{$user->role}}" readonly>
				</div>				

			</div>
		</div>
		
	</div>

	<div class="panel panel-primary">

		  <div class="panel-heading">
		    	Categories for this user
		  </div>

		  <div class="panel-body">
				<div class="row">

				</div>
		  </div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<span>Adding category to a user</span>		
				</div>

				<div class="panel-body">

					<div class="form-group">	
						<div class="row">	
							<div class="col-xs-12 col-sm-2">
								<label>Start date</label>
								<input id="startdatefield" name="start_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" v-model="newTeleworking.start_date">
							</div>	

							<div class="col-xs-12 col-sm-2">
								<label>End date</label>
								<input id="enddatefield" name="end_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" v-model="newTeleworking.end_date">
							</div>	
						</div>
					</div>
				
					<div class="form-group">	
						<button title="Save" class="btn btn-primary" :disabled="formFilled==false" v-on:click.prevent="save">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save
						</button>
					</div>	
					
				</div>
			</div>
		</div>
	</div>

	<a class="btn btn-primary" href="{{ url('users') }}">Back</a>

</div>


@endsection

@push('script-bottom')

@endpush
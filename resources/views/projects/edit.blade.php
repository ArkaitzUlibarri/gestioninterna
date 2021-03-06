@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10">

		<form method="POST" action="{{ url('projects', $project->id) }}">

			{{ csrf_field() }}
			{{ method_field('PATCH') }}

			<input type=hidden name="project_id" type="text" value="{{ $project->id }}">

			<div class="panel panel-primary">
				<div class="panel-heading">Editing a Project</div>

			 	<div class="panel-body">

			 		<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>Project name:</label>
							<input name="name" type ="text" class="form-control" placeholder="Project name" value="{{ ucwords($project->name) }}">
						</div>

						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>Customer:</label>
							<select name="customer_id" class="form-control">	
								@foreach ($customers as $customer)
									<option {{$customer->name==$project->customerName ? 'selected' : ''}} value="{{ $customer->id }}">{{ strtoupper($customer->name) }}</option>
								@endforeach	  
							</select>
						</div>

						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>Project Manager:</label>
							<select name="pm_id" class="form-control">	
								@foreach ($PM_Users as $user)
									<option {{$user->id==$project->pm_id ? 'selected' : ''}} value="{{ $user->id }}">{{ ucwords($user->fullname) }} ({{ $user->code}})</option>
								@endforeach	  
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>Start date:</label>
							<input name="start_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" value="{{ $project->start_date }}">
						</div>

						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>End date:</label>
							<input name="end_date" type ="date" class="form-control"  placeholder="yyyy-mm-dd" value="{{ $project->end_date }}">
						</div>		
					</div>

					<div class="form-group">
						<label>Description:</label>
						<textarea name="description" class="form-control" placeholder="Description" rows=5 >{{ ucfirst($project->description) }}</textarea>
					</div>

					@include('layouts.errors')

					<hr>

					<div class="form-group pull-right">		
						<a class="btn btn-default btn-sm custom-btn-width" href="{{ url('projects') }}">Cancel</a>
						<button type ="submit" class="btn btn-primary btn-sm custom-btn-width">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save
						</button>
					</div>

				</div>
			</div>
		</form>

		<form method="post" action="{{ url('projects', $project->id) }}">
	        {{ csrf_field() }}
	        {{ method_field('delete') }}

	      	<button class="btn btn-danger btn-sm custom-btn-width" type="submit"><span class="glyphicon glyphicon-trash"></span> Delete</button>

      	</form>

	</div>
</div>
@endsection


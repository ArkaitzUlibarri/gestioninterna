@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="POST" action="/projects/{{ $project->id }}">

			{{ csrf_field() }}
			{{ method_field('PATCH') }}

			<input type=hidden name="project_id" type="text" value="{{ $project->id }}">

			<div class="form-group">
				<label>Project name:</label>
				<input name="name" type ="text" class="form-control" placeholder="Project name" value="{{ strtoupper($project->name) }}">
			</div>

			<div class="form-group">
				<label>Description:</label>
				<textarea name="description" class="form-control" placeholder="Description" rows=5 >{{ $project->description }}</textarea>
			</div>

			<div class="form-group">
				<label>Customer:</label>
				<select name="customer_id" class="form-control">	
					@foreach ($customers as $customer)
						<option {{$customer->name==$project->customerName ? 'selected' : ''}} value="{{ $customer->id }}">{{ strtoupper($customer->name) }}</option>
					@endforeach	  
				</select>
			</div>	

			<div class  ="form-group">
				<label>Start date::</label>
				<input name="start_date" type ="date" class="form-control" value="{{ $project->start_date }}">
			</div>

			<div class  ="form-group">
				<label>End date:</label>
				<input name="end_date" type ="date" class="form-control"  value="{{ $project->end_date }}">
			</div>		

			<div class="form-group">
				<label>Project Manager:</label>
				<select name="pm_id" class="form-control">	
					@foreach ($PM_Users as $user)
						<option {{$user->id==$project->pm_id ? 'selected' : ''}} value="{{ $user->id }}">{{ strtoupper($user->fullname) }}</option>
					@endforeach	  
				</select>
			</div>

			<div class="form-group">		
				<a class="btn btn-primary" href="{{ url('projects') }}"><span class="glyphicon glyphicon-arrow-left"></span> Cancel</a>
				<button type ="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
			</div>
				
			@include('layouts.errors')

		</form>

	</div>
@endsection


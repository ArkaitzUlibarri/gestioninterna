@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="post" action="/projects">
			
			{{ csrf_field() }}

			<div class  ="form-group">
				<label>Project name:</label>
				<input name="name" type ="text" class="form-control" placeholder="Project" value="{{ old('name') }}" >
			</div>

			<div class  ="form-group">
				<label>Description:</label>
				<textarea name="description" class="form-control" placeholder="Description" rows=5 >{{ old('description') }}</textarea>
			</div>

			<div class  ="form-group">
				<label>Customer:</label>
				<select class="form-control" name="customer_id">	
					<option value="">-</option>	
					@foreach ($customers as $customer)
						<option value="{{ $customer->id }}" {{ (old('customer_id') == $customer->id ? "selected":"") }}>{{ strtoupper($customer->name) }}</option>
					@endforeach	  
				</select>
			</div>
					
			<div class  ="form-group">
				<label>Start date:</label>
				<input name="start_date" type ="date" class="form-control" value="{{ old('start_date') }}">
			</div>

			<div class  ="form-group">
				<label>End date:</label>
				<input name="end_date" type ="date" class="form-control" value="{{ old('end_date') }}">
			</div>	

			<div class="form-group">
				<label>Project Manager:</label>
				<select class="form-control" name="pm_id">	
					<option value="">-</option>	
					@foreach ($PM_Users as $user)
						<option value="{{ $user->id }}" {{ (old('pm_id') == $user->id ? "selected":"") }} >{{ strtoupper($user->fullname) }}</option>
					@endforeach	  
				</select>
			</div>	

			<div class  ="form-group">	
				<a class="btn btn-primary" href="{{ url('projects') }}"><span class="glyphicon glyphicon-arrow-left"></span> Cancel</a>
				<button type ="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
			</div>	

			@include('layouts.errors')

		</form>

	</div>
@endsection
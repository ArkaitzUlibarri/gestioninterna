@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10">

		<form method="post" action="{{ url('projects') }}">
			{{ csrf_field() }}

			<div class="panel panel-primary">
				<div class="panel-heading">NEW PROJECT</div>

			 	<div class="panel-body">

					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>Project name:</label>
							<input name="name" type ="text" class="form-control" placeholder="Project" value="{{ old('name') }}" >
						</div>

						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>Customer:</label>
							<select class="form-control" name="customer_id">	
								<option value="">-</option>	
								@foreach ($customers as $customer)
									<option value="{{ $customer->id }}" {{ (old('customer_id') == $customer->id ? "selected":"") }}>{{ strtoupper($customer->name) }}</option>
								@endforeach	  
							</select>
						</div>

						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>Project Manager:</label>
							<select class="form-control" name="pm_id">	
								<option value="">-</option>	
								@foreach ($PM_Users as $user)
									<option value="{{ $user->id }}" {{ (old('pm_id') == $user->id ? "selected":"") }} >{{ ucfirst($user->fullname) }}</option>
								@endforeach	  
							</select>
						</div>	
					</div>

					<div class="row">	
						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>Start date:</label>
							<input name="start_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" value="{{ old('start_date') }}">
						</div>

						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label>End date:</label>
							<input name="end_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" value="{{ old('end_date') }}">
						</div>	
					</div>

					<div class="form-group">
						<label>Description:</label>
						<textarea name="description" class="form-control" placeholder="Description" rows=5 >{{ old('description') }}</textarea>
					</div>

					@include('layouts.errors')

					<hr>

					<div align="right" class="form-group">		
						<a class="btn btn-default" href="{{ url('projects') }}">Cancel</a>
						<button type ="submit" class="btn btn-primary">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save
						</button>
					</div>
				</div>
			</div>
		</form>

	</div>
</div>
@endsection
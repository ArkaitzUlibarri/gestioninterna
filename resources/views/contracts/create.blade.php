@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="post" action="{{ url('contracts') }}">
		
			{{ csrf_field() }}

			<div class="panel panel-primary">

				<div class="panel-heading">
			    	Creating a Contract
			 	</div>

			 	<div class="panel-body">
					<div class="row">
						<div class ="form-group col-xs-12 col-sm-4">
							<label>Employee:</label>
							<select name="user_id" class="form-control">
								<option value="">-</option>	
								@foreach ($users as $user)
									<option value="{{ $user->id }}" {{ (old('user_id') == $user->id ? "selected":"") }}>{{ $user->full_name }}</option>		
								@endforeach	  
							</select>
						</div>
					</div>
						
					<div class="row">
						<div class ="form-group col-xs-12 col-sm-4">
							<label>Type of contract:</label>
							<select name="contract_type_id" class="form-control">
								<option value="">-</option>	
								@foreach ($contractTypes as $contractType)
									<option value="{{ $contractType->id }}" {{ (old('contract_type_id') == $contractType->id ? "selected":"") }}>{{$contractType->name}}</option>
								@endforeach			  
							</select>
						</div>
					</div>

					<div class="row">
						<div class ="form-group col-xs-12 col-sm-2">
							<label>Weekly working hours:</label>
							<input name="week_hours" type ="number" min="0" max="40" class="form-control" placeholder="Hours" value="{{old('week_hours')}}">
						</div>	
					</div>
					
					<div class="row">	
						<div class ="form-group col-xs-12 col-sm-4">
							<label>Start date:</label>
							<input name="start_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" value="{{ old('start_date') }}">
						</div>

						<div class ="form-group col-xs-12 col-sm-4">
							<label>Estimated end date:</label>
							<input name="estimated_end_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" value="{{ old('estimated_end_date') }}">
						</div>	

						<div class ="form-group col-xs-12 col-sm-4">
							<label>End date:</label>
							<input name="end_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" value="{{ old('end_date') }}">
						</div>	
					</div>

					<div class="row">
						<div class ="form-group col-xs-12 col-sm-4">
							<label>Bank holidays:</label>
							<select name="national_days_id" class="form-control">
								<option value="">-</option>	
								@foreach ($nationalDays as $nationalDay)
									<option value="{{ $nationalDay->id }}" {{ (old('national_days_id') == $nationalDay->id ? "selected":"") }}>{{$nationalDay->name}}</option>	
								@endforeach			  
							</select>
						</div>

						<div class ="form-group col-xs-12 col-sm-4">
							<label>Region bank holidays:</label>
							<select name="regional_days_id" class="form-control">
								<option value="">-</option>	
								@foreach ($regionalDays as $regionalDay)
									<option value="{{ $regionalDay->id }}" {{ (old('regional_days_id') == $regionalDay->id ? "selected":"") }}>{{$regionalDay->name}}</option>	
								@endforeach			  
							</select>
						</div>

						<div class ="form-group col-xs-12 col-sm-4">
							<label>Local bank holidays:</label>
							<select name="local_days_id" class="form-control">
								<option value="">-</option>	
								@foreach ($localDays as $localDay)
									<option value="{{ $localDay->id }}" {{ (old('local_days_id') == $localDay->id ? "selected":"") }}>{{$localDay->name}}</option>		
								@endforeach			  
							</select>
						</div>
					</div>

					<div class ="form-group">
						<a title="Cancel" class="btn btn-default" href="{{ url('contracts') }}">
							Cancel
						</a>
						<button type="submit" title="Save" class="btn btn-primary">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save
						</button>
					</div>
				</div>	
			</div>
				
			@include('layouts.errors')
		</form>

	</div>
@endsection
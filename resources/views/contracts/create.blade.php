@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="post" action="/contracts/">
		
			{{ csrf_field() }}

			<div class ="form-group">
				<label>Employee:</label>
				<select name="user_id" class="form-control">
					<option value="">-</option>	
					@foreach ($users as $user)
						<option value="{{ $user->id }}" {{ (old('user_id') == $user->id ? "selected":"") }}>{{ $user->full_name }}</option>		
					@endforeach	  
				</select>
			</div>

			<div class ="form-group">
				<label>Type of contract:</label>
				<select name="contract_type_id" class="form-control">
					<option value="">-</option>	
					@foreach ($contractTypes as $contractType)
						<option value="{{ $contractType->id }}" {{ (old('contract_type_id') == $contractType->id ? "selected":"") }}>{{$contractType->name}}</option>
					@endforeach			  
				</select>
			</div>

			<div class ="form-group">
				<label>Weekly working hours:</label>
				<input name="week_hours" type ="number" min="0" max="40" class="form-control" placeholder="Weekly working hours" value="{{old('week_hours')}}">
			</div>	
					
			<div class ="form-group">
				<label>Start date:</label>
				<input name="start_date" type ="date" class="form-control" value="{{ old('start_date') }}">
			</div>

			<div class ="form-group">
				<label>Estimated end date:</label>
				<input name="estimated_end_date" type ="date" class="form-control" value="{{ old('estimated_end_date') }}">
			</div>	

			<div class ="form-group">
				<label>End date:</label>
				<input name="end_date" type ="date" class="form-control" value="{{ old('end_date') }}">
			</div>	

			<div class ="form-group">
				<label>Bank holidays:</label>
				<select name="national_days_id" class="form-control">
					<option value="">-</option>	
					@foreach ($nationalDays as $nationalDay)
						<option value="{{ $nationalDay->id }}" {{ (old('national_days_id') == $nationalDay->id ? "selected":"") }}>{{$nationalDay->name}}</option>	
					@endforeach			  
				</select>
			</div>

			<div class ="form-group">
				<label>Region bank holidays:</label>
				<select name="regional_days_id" class="form-control">
					<option value="">-</option>	
					@foreach ($regionalDays as $regionalDay)
						<option value="{{ $regionalDay->id }}" {{ (old('regional_days_id') == $regionalDay->id ? "selected":"") }}>{{$regionalDay->name}}</option>	
					@endforeach			  
				</select>
			</div>

			<div class ="form-group">
				<label>Local bank holidays:</label>
				<select name="local_days_id" class="form-control">
					<option value="">-</option>	
					@foreach ($localDays as $localDay)
						<option value="{{ $localDay->id }}" {{ (old('local_days_id') == $localDay->id ? "selected":"") }}>{{$localDay->name}}</option>		
					@endforeach			  
				</select>
			</div>

			<div class ="form-group">
				<a title="Cancel" class="btn btn-primary" href="{{ url('contracts') }}"><span class="glyphicon glyphicon-arrow-left"></span> Cancel</a>
				<button type="submit" title="Save" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
			</div>

			@include('layouts.errors')
		</form>

	</div>
@endsection
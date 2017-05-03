@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="POST" action="/contracts/{{ $contract->id }}">

			{{ csrf_field() }}
			{{ method_field('PATCH') }}

			<input type=hidden name="contract_id" type="text" value="{{ $contract->id }}">

			<div class ="form-group">
				<label>Employee:</label>
				<select name="user_id" class="form-control">
					@foreach ($users as $user)
						<option value="{{ $user->id }}" {{ $contract->name==$user->name ? "selected" : "" }}>
							{{ $user->full_name }}
						</option>		
					@endforeach	  
				</select>
			</div>

			<div class ="form-group">
				<label>Type of contract:</label>
				<select name="contract_type_id" class="form-control">
					@foreach ($contractTypes as $contractType)		
						<option value="{{ $contractType->id }}" {{$contractType->name==$contract->contract_type ? "selected" : "" }}>
							{{$contractType->name}}
						</option>					
					@endforeach			  
				</select>
			</div>

			<div class ="form-group">
				<label>Weekly working hours:</label>
				<input name="week_hours" type ="number" min="0" max="40" class="form-control" placeholder="Horas semanales" value="{{ $contract->week_hours }}">
			</div>	
					
			<div class ="form-group">
				<label>Start date:</label>
				<input name="start_date" type ="date" class="form-control" value="{{ $contract->start_date }}">
			</div>

			<div class ="form-group">
				<label>Estimated end date:</label>
				<input name="estimated_end_date" type ="date" class="form-control" value="{{ $contract->estimated_end_date }}">
			</div>	

			<div class ="form-group">
				<label>End date:</label>
				<input name="end_date" type ="date" class="form-control" value="{{ $contract->end_date }}">
			</div>	

			<div class ="form-group">
				<label>Bank holidays:</label>
				<select name="national_days_id" class="form-control">
					@foreach ($nationalDays as $nationalDay)	
						<option value="{{ $nationalDay->id }}" {{$nationalDay->id==$contract->national_days_id ? "selected" : "" }}>
							{{$nationalDay->name}}
						</option>			
					@endforeach			  
				</select>
			</div>

			<div class ="form-group">
				<label>Region bank holidays:</label>
				<select name="regional_days_id" class="form-control">
					@foreach ($regionalDays as $regionalDay)	
						<option value="{{ $regionalDay->id }}" {{$regionalDay->id==$contract->regional_days_id ? "selected" : "" }}>
							{{$regionalDay->name}}
						</option>							
					@endforeach			  
				</select>
			</div>

			<div class ="form-group">
				<label>Local bank holidays:</label>
				<select name="local_days_id" class="form-control">
					@foreach ($localDays as $localDay)		
						<option value="{{ $localDay->id }}" {{$localDay->id==$contract->local_days_id ? "selected" : "" }}>
							{{$localDay->name}}
						</option>
					@endforeach			  
				</select>
			</div>

			<div class ="form-group">
				<a title="Cancel" class="btn btn-danger" href="{{ url('contracts') }}">
					<span class="glyphicon glyphicon-arrow-left"></span> Cancel
				</a>
				<button type="submit" title="Save" class="btn btn-success">
					<span class="glyphicon glyphicon-floppy-disk"></span> Save
				</button>
			</div>
			
			@include('layouts.errors')
		</form>

	</div>
@endsection
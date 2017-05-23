@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="POST" action="/contracts/{{ $contract->id }}">

			{{ csrf_field() }}
			{{ method_field('PATCH') }}

			<input type=hidden name="contract_id" type="text" value="{{ $contract->id }}">

			<div class="panel panel-primary">

				<div class="panel-heading">
			    	Editing a Contract
			 	</div>

			 	<div class="panel-body">

					<div class="row">
						<div class ="form-group col-xs-12 col-sm-4">
							<label>Employee:</label>
							<select name="user_id" class="form-control">
								@foreach ($users as $user)
									<option value="{{ $user->id }}" {{ $contract->name==$user->name ? "selected" : "" }}>
										{{ $user->full_name }}
									</option>		
								@endforeach	  
							</select>
						</div>

						<div class ="form-group col-xs-12 col-sm-4">
							<label>Type of contract:</label>
							<select name="contract_type_id" class="form-control">
								@foreach ($contractTypes as $contractType)		
									<option value="{{ $contractType->id }}" {{$contractType->name==$contract->contract_type ? "selected" : "" }}>
										{{$contractType->name}}
									</option>					
								@endforeach			  
							</select>
						</div>

						<div class ="form-group col-xs-12 col-sm-4">
							<label>Weekly working hours:</label>
							<input name="week_hours" type ="number" min="0" max="40" class="form-control" placeholder="Weekly working hours" value="{{ $contract->week_hours }}">
						</div>	
					</div>

					<div class="row">	
						<div class ="form-group col-xs-12 col-sm-4">
							<label>Start date:</label>
							<input name="start_date" type ="date" class="form-control" value="{{ $contract->start_date }}">
						</div>

						<div class ="form-group col-xs-12 col-sm-4">
							<label>Estimated end date:</label>
							<input name="estimated_end_date" type ="date" class="form-control" value="{{ $contract->estimated_end_date }}">
						</div>	

						<div class ="form-group col-xs-12 col-sm-4">
							<label>End date:</label>
							<input name="end_date" type ="date" class="form-control" value="{{ $contract->end_date }}">
						</div>	
					</div>	

					<div class="row">	
						<div class ="form-group col-xs-12 col-sm-4">
							<label>Bank holidays:</label>
							<select name="national_days_id" class="form-control">
								@foreach ($nationalDays as $nationalDay)	
									<option value="{{ $nationalDay->id }}" {{$nationalDay->id==$contract->national_days_id ? "selected" : "" }}>
										{{$nationalDay->name}}
									</option>			
								@endforeach			  
							</select>
						</div>

						<div class ="form-group col-xs-12 col-sm-4">
							<label>Region bank holidays:</label>
							<select name="regional_days_id" class="form-control">
								@foreach ($regionalDays as $regionalDay)	
									<option value="{{ $regionalDay->id }}" {{$regionalDay->id==$contract->regional_days_id ? "selected" : "" }}>
										{{$regionalDay->name}}
									</option>							
								@endforeach			  
							</select>
						</div>

						<div class ="form-group col-xs-12 col-sm-4">
							<label>Local bank holidays:</label>
							<select name="local_days_id" class="form-control">
								@foreach ($localDays as $localDay)		
									<option value="{{ $localDay->id }}" {{$localDay->id==$contract->local_days_id ? "selected" : "" }}>
										{{$localDay->name}}
									</option>
								@endforeach			  
							</select>
						</div>
					</div>

					<div class ="form-group">
						<a title="Cancel" class="btn btn-primary" href="{{ url('contracts') }}">
							<span class="glyphicon glyphicon-arrow-left"></span> Cancel
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
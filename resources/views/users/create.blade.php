@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="post" action="/users">
			{{ csrf_field() }}

			<div class="panel panel-primary">

				<div class="panel-heading">
					Creating a new user
			 	</div>

			 	<div class="panel-body">
					<div class="row">
						<div class="form-group col-xs-12 col-sm-4"" >
							<label>Name:</label>
							<input name="name" type ="text" class="form-control" placeholder="Name" value="{{ old('name') }}" >
						</div>

						<div class="form-group col-xs-12 col-sm-4"">
							<label>Lastname 1:</label>
							<input name="lastname_1" type ="text" class="form-control" placeholder="Lastname 1" value="{{ old('lastname_1') }}" >
						</div>

						<div class="form-group col-xs-12 col-sm-4"">
							<label>Lastname 2:</label>
							<input name="lastname_2" type ="text" class="form-control" placeholder="Lastname 2" value="{{ old('lastname_2') }}">
						</div>
					</div>

					<div class="row">
						<div class ="form-group col-xs-12 col-sm-4">
							<label>Email:</label>
							<input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
						</div>
								
						<div class ="form-group col-xs-12 col-sm-2">
							<label>Role:</label>
							<select class="form-control" name="role">
								<option value="">-</option>	
								@foreach ($roles as $role)
									<option value="{{$role}}" {{ (old('role') == $role ? "selected":"") }}>{{strtoupper($role)}}</option>					
								@endforeach			  
							</select>
						</div>
					</div>

			  		<div class ="form-group">	
						<a title="Cancel" class="btn btn-primary" href="{{ url('users') }}"><span class="glyphicon glyphicon-arrow-left"></span> Cancel</a>
						<button type="submit" title="Save" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					</div>
				</div>

			</div>
			
			@include('layouts.errors')
		</form>

	</div>
@endsection
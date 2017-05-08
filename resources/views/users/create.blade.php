@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="post" action="/users">
			{{ csrf_field() }}
			<div class ="form-group" >
				<label>Name:</label>
				<input name="name" type ="text" class="form-control" placeholder="Name" value="{{ old('name') }}" >
			</div>

			<div class ="form-group">
				<label>Lastname 1:</label>
				<input name="lastname_1" type ="text" class="form-control" placeholder="Lastname 1" value="{{ old('lastname_1') }}" >
			</div>

			<div class ="form-group">
				<label>Lastname 2:</label>
				<input name="lastname_2" type ="text" class="form-control" placeholder="Lastname 2" value="{{ old('lastname_2') }}">
			</div>

			<div class ="form-group">
				<label>Email:</label>
				<input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
			</div>
					
			<div class ="form-group">
				<label>Role:</label>
				<select class="form-control" name="role">
					<option value="">-</option>	
					@foreach ($roles as $role)
						<option value="{{$role}}" {{ (old('role') == $role ? "selected":"") }}>{{strtoupper($role)}}</option>					
					@endforeach			  
				</select>
			</div>

	  		<div class ="form-group">	
				<a title="Cancel" class="btn btn-danger" href="{{ url('users') }}"><span class="glyphicon glyphicon-arrow-left"></span> Cancel</a>
				<button type="submit" title="Save" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
			</div>
			
			@include('layouts.errors')
		</form>

	</div>
@endsection
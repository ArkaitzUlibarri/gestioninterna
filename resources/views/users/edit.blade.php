@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="POST" action="/users/{{ $user->id }}">

			{{ csrf_field() }}
			{{ method_field('PATCH') }}

			<input type=hidden name="user_id" type="text" value="{{ $user->id }}">

			<div class  ="form-group">
				<label>Name:</label>
				<input name="name" type ="text" class="form-control" placeholder="Name" value="{{ $user->name }}">
			</div>

			<div class  ="form-group">
				<label>Lastname 1:</label>
				<input name="lastname_1" type ="text" class="form-control" placeholder="Lastname 1" value="{{ $user->lastname_1 }}">
			</div>

			<div class  ="form-group">
				<label>Lastname 2:</label>
				<input name="lastname_2" type ="text" class="form-control" placeholder="Lastname 2" value="{{ $user->lastname_2 }}">
			</div>

			<div class  ="form-group">
				<label>Email:</label>
				<input name="email" type="email" class="form-control" placeholder="Email" value="{{ $user->email}}">
			</div>
					
			<div class  ="form-group">
				<label>Role:</label>
				<select name="role" class="form-control">
					@foreach ($roles as $role)
						<option {{$role==$user->role ? "selected" : "" }} >{{$role}}</option>				
					@endforeach			  
				</select>
			</div>

	  		<div class  ="form-group">	
				<a title="Cancel" class="btn btn-primary" href="{{ url('users') }}">
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
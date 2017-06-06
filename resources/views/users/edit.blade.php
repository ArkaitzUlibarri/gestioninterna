@extends('layouts.app')

@section('content')
	<div class="container">

		<form method="POST" action="/users/{{ $user->id }}">

			{{ csrf_field() }}
			{{ method_field('PATCH') }}

			<input type=hidden name="user_id" type="text" value="{{ $user->id }}">

			<div class="panel panel-primary">

			<div class="panel-heading">
		    	Editing a User
		 	</div>

		 	<div class="panel-body">

				<div class="row">
					<div class  ="form-group col-xs-12 col-sm-4">
						<label>Name:</label>
						<input name="name" type ="text" class="form-control" placeholder="Name" value="{{ $user->name }}">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-12 col-sm-4">
						<label>Lastname 1:</label>
						<input name="lastname_1" type ="text" class="form-control" placeholder="Lastname 1" value="{{ $user->lastname_1 }}">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-12 col-sm-4">
						<label>Lastname 2:</label>
						<input name="lastname_2" type ="text" class="form-control" placeholder="Lastname 2" value="{{ $user->lastname_2 }}">
					</div>
				</div>

				<div class="row">
					<div class  ="form-group col-xs-12 col-sm-4">
						<label>Email:</label>
						<input name="email" type="email" class="form-control" placeholder="Email" value="{{ $user->email}}">
					</div>
				</div>
						
				<div class="row">	
					<div class="form-group col-xs-12 col-sm-2">
						<label>Role:</label>
						<select name="role" class="form-control">
							@foreach ($roles as $role)
								<option {{$role==$user->role ? "selected" : "" }} >{{$role}}</option>				
							@endforeach			  
						</select>
					</div>
				</div>

		  		<div class="form-group">	
					<a title="Cancel" class="btn btn-primary" href="{{ url('users') }}">
						<span class="glyphicon glyphicon-arrow-left"></span> Cancel
					</a>
					<button type="submit" title="Save" class="btn btn-primary">
						<span class="glyphicon glyphicon-floppy-disk"></span> Save
					</button>
				</div>

			</div>
			
			@include('layouts.errors')
		</form>

		<div class ="form-group pull-right">
	        <a title="Categories" class="btn btn-primary btn-sm" type="button" 
	           href="{{ url('users/' . $user->id . '/categories') }}">
	           Categories
	        </a>
	        <a title="Groups" class="btn btn-primary btn-sm" type="button" 
	           href="{{ url('users/' . $user->id . '/groups') }}">
	            Groups
	        </a>
	    </div>

	</div>
@endsection
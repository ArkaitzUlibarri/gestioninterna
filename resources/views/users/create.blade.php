@extends('layouts.app')

@section('content')

<form method="post" action="{{ url('users') }}">
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
					<label>Lastname:</label>
					<input name="lastname" type ="text" class="form-control" placeholder="Lastname" value="{{ old('lastname') }}" >
				</div>
			</div>

			<div class="row">
				<div class ="form-group col-xs-12 col-sm-4">
					<label>Email:</label>
					<input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
				</div>
			</div>
			
			<!--
			<div class="row">
				<div class ="form-group col-xs-12 col-sm-4">
					<label>Email:</label>
					<div class="input-group">
				  		<input type="text" class="form-control" placeholder="Email" aria-describedby="basic-addon2">
				  		<span class="input-group-addon" id="basic-addon2">@3dbconsult.com</span>
					</div>
				</div>
			</div>	
			-->

			<div class="row">
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

			<div class="row">
			    <div class="form-group col-xs-12 col-sm-2 {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">Password:</label>
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group col-xs-12 col-sm-2">
                    <label for="password-confirm">Confirm Password:</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>                   
                </div>
            </div>

	  		<div class ="form-group">	
				<a title="Cancel" class="btn btn-default" href="{{ url('users') }}">
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

@endsection
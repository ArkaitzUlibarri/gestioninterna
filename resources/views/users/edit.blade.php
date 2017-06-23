@extends('layouts.app')

@section('content')

<form method="POST" action="{{ url('users', $user->id) }}">
	{{ csrf_field() }}
	{{ method_field('PATCH') }}

	<input type=hidden name="user_id" type="text" value="{{ $user->id }}">

	<div class="panel panel-primary">

		<div class="panel-heading">Editing a User</div>

	 	<div class="panel-body">

	 		@include('users.card')

			<div class="panel panel-default">
				<div class="panel-body">
					<div class="form-inline col-xs-12 col-sm-6">
						<div class="form-group">
							<label>Role:</label>
							<select name="role" style="width: 110px;" class="form-control input-sm">
								@foreach ($roles as $role)
									<option value="{{ $role }}" {{ $role==$user->role ? "selected" : "" }}>{{ ucfirst($role) }}</option>				
								@endforeach
							</select>
						</div>
					</div>

				</div>
			</div>

	  		<div class ="form-group pull-left">
                <a title="Categories"
                   class="btn btn-primary"
                   type="button"
                   href="{{ url('users/' . $user->id . '/categories') }}">Categories
            	</a>
            </div>

            <div class ="form-group pull-right">
				<a title="Cancel" class="btn btn-default" href="{{ url('users') }}">Cancel</a>

				<button type="submit" title="Save" class="btn btn-success">
					<span class="glyphicon glyphicon-floppy-disk"></span> Save
				</button>
		 	</div>

		</div>
	</div>
	
	@include('layouts.errors')

</form>

@endsection
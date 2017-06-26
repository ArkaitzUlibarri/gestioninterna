@extends('layouts.app')

@section('content')

<div class="panel panel-primary">

	<div class="panel-heading">Edit User</div>

 	<div class="panel-body">

 		@include('users.card')

		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-inline">
					<div class="form-group">
						<label>Role:</label>
						<select name="role"
								class="form-control input-sm"
								v-model="role"
								v-on:change="updateRole(role)">

							@foreach ($roles as $role)
								<option value="{{ $role }}">{{ ucfirst($role) }}</option>				
							@endforeach

						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="panel panel-primary">
	<div class="panel-body">
		<div class="form-inline">
			<select class="form-control input-sm" v-model="idxCategory">
				<option value="-1">Select category</option>
				<option :value="index" v-for="(category, index) in categoryList">@{{ category.name }} - @{{ category.description }}</option>
			</select>

			<button class="btn btn-primary btn-sm"
					:disabled="idxCategory==-1"
					v-on:click.prevent="addCategory"> Add</button>
			<hr style="margin-top: 10px; margin-bottom: 10px;">

			<ul class="list-group">
				<li class="list-group-item" v-for="(item, index) in categories">
					@{{ item.category }}
					<div class="pull-right" style="cursor: pointer;" v-on:click="deleteCategory(item.id)">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class ="form-group pull-right">
	<a title="Cancel" class="btn btn-default" href="{{ url('users') }}">Back</a>
</div>

@endsection

@push('script-bottom')
	<script type="text/javascript">
		var user = <?php echo json_encode($user);?>;
		var categories = <?php echo json_encode($categories);?>;
	</script>

	<script src="{{ asset('js/user.js') }}"></script>
@endpush

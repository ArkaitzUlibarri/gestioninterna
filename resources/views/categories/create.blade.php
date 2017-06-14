@extends('layouts.app')

@section('content')	

<div id="categories" class ="container">

	<div class="row">
		<div class ="form-group col-xs-12 col-sm-4">
			<h2>{{ ucfirst($user->fullname) }}</h2>				
		</div>
	</div>

	<div class="panel panel-primary">

		<div class="panel-heading">
		    User Details
		 </div>

		<div class="panel-body">
			<div class="row">

				<div class="col-xs-12 col-sm-6">	
					<label>Employee</label>
					<input class="form-control" type="text" placeholder="{{$user->fullname}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-4">
					<label>Email</label>
					<input class="form-control" type ="text" placeholder="{{$user->email}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-2">
					<label>Role</label>
					<input class="form-control" type ="text" placeholder="{{$user->role}}" readonly>
				</div>				

			</div>
		</div>
		
	</div>

	<div class="panel panel-primary">

		  <div class="panel-heading">
		    	Categories for this user
		  </div>

		  <div class="panel-body">
				<div class="row">
					<span v-for="(item, index) in array">
						<category-template :item="item" :index="index"></category-template>
					</span>
				</div>
		  </div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<span>Adding a new category to a user</span>		
				</div>

				<div class="panel-body">
					<form class="form-inline">

						<label>Category</label>
						<select class="form-control" v-model="newCategory.category">
							<option value="">-</option>
							<template v-for="(category, index) in categoryList">
								<option :category="category" :index="index">@{{category}}</option>
							</template>
						</select>				
	
						<button title="Save" class="btn btn-primary" :disabled="formFilled==false" v-on:click.prevent="saveCategory">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save
						</button>
						
					</form>	
				</div>
			</div>
		</div>
	</div>

	<a class="btn btn-default" href="{{ url('users') }}">Back</a>

</div>


@endsection

@push('script-bottom')
<script type="text/javascript">
	var user       = <?php echo json_encode($user);?>;
	var categories = <?php echo json_encode($categories);?>;
</script>

<script src="{{ asset('js/categories.js') }}"></script>
@endpush
@extends('layouts.app')

@section('content')	

<div id="reduction" class ="container">

	<div class="row">
		<div class ="form-group col-xs-12 col-sm-4">
			<h2>{{ ucfirst($contract->user->full_name) }}</h2>				
		</div>
	</div>

	<div class="panel panel-primary">

		<div class="panel-heading">
		    Contract Details
		 </div>

		<div class="panel-body">
			<div class="row">

				<div class="col-xs-12 col-sm-6">	
					<label>Employee</label>
					<input class="form-control" type="text" placeholder="{{$contract->user->fullname}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-2">
					<label>Start date</label>
					<input class="form-control" type ="date" value="{{$contract->start_date}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-2">
					<label>Estimated end date</label>
					<input class="form-control" type ="date" value="{{$contract->estimated_end_date}}" readonly>
				</div>	

				<div class="col-xs-12 col-sm-2">
					<label>End date</label>
					<input class="form-control" type ="date" value="{{$contract->end_date}}" readonly>
				</div>			

			</div>
		</div>
		
	</div>

	<div class="panel panel-primary">

		  <div class="panel-heading">
		    	Reduction for this user
		  </div>

		  <div class="panel-body">
				<div class="row">
					<span v-for="(item, index) in array">
						<reduction-template :item="item" :index="index"></reduction-template>
					</span>			
				</div>
		  </div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<span>Adding reduction to a contract</span>	
				</div>

				<div class="panel-body">

					<form class="form-inline">	

						<div class="form-group col-xs-12 col-sm-3">
							<label>Working Hours</label>
							<input type="number" min=0 max=40 class="form-control" placeholder="Time" v-model="newReduction.week_hours">
						</div>	

						<div class="form-group col-xs-12 col-sm-3">
							<label>Start date</label>
							<input id="startdatefield" name="start_date" name="created_at" type ="date" class="form-control" placeholder="yyyy-mm-dd" v-model="newReduction.start_date">
						</div>	

						<div class="form-group col-xs-12 col-sm-3">
							<label>End date</label>
							<input id="enddatefield" name="end_date" type ="date" class="form-control" placeholder="yyyy-mm-dd" v-model="newReduction.end_date">
						</div>	

						<div class="form-group col-xs-12 col-sm-3">	
							<button title="Save" class="btn btn-primary" :disabled="formFilled==false" v-on:click="save">
								<span class="glyphicon glyphicon-floppy-disk"></span> Save
							</button>
							<button title="New" class="btn btn-primary" v-show="editIndex!=-1" v-on:click="initialize">
								<span class="glyphicon glyphicon-plus-sign"></span> New
							</button>
						</div>	

					</form>
				

					
				</div>
			</div>
		</div>
	</div>

	<a class="btn btn-primary" href="{{ url('contracts') }}">Back</a>
</div>

@endsection

@push('script-bottom')
<script type="text/javascript">
	var contract    = <?php echo json_encode($contract);?>;
</script>

<script src="{{ asset('js/reductions.js') }}"></script>
@endpush
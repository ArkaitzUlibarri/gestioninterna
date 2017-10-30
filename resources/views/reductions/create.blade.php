@extends('layouts.app')

@section('content')	

<div id="reduction" class ="container">

	@include('reductions.header')			

	<div class="panel panel-primary">

		  <div class="panel-heading">
		    	Reduction for this user
		  </div>

		  <div class="panel-body">
				<div class="row">
					<span v-for="(item, index) in array">	
						<span v-if="editIndex == index">
							<reduction-template :item="item" :index="index" :prop="true"></reduction-template>
						</span>
						<span v-else>
							<reduction-template :item="item" :index="index" :prop="false"></reduction-template>
						</span>
					</span>			
				</div>
		  </div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<span v-if="editIndex==-1">Adding reduction to a contract</span>	
					<span v-if="editIndex!=-1">Editing reduction</span>	
				</div>

				<div class="panel-body">

					<form class="form-inline">	

						<div class="form-group">
							<label>Week Working Hours</label>
							<input type="number" min=0 max="{{($contract->week_hours)- 1}}" class="form-control input-sm" placeholder="Time" v-model="newReduction.week_hours" 
							v-on:blur="hoursValidation" id="hourfield">
						</div>	

						<div class="form-group">
							<label>Start date</label>
							<input id="startdatefield" name="start_date" name="created_at" type ="date" class="form-control input-sm" placeholder="yyyy-mm-dd" v-model="newReduction.start_date">
						</div>	

						<div class="form-group">
							<label>End date</label>
							<input id="enddatefield" name="end_date" type ="date" class="form-control input-sm" placeholder="yyyy-mm-dd" v-model="newReduction.end_date">
						</div>	

						<div class="form-group">	
							<button title="Save" class="btn btn-primary btn-sm" :disabled="formFilled==false" v-on:click.prevent="save">
								<span class="glyphicon glyphicon-floppy-disk"></span>
								<span v-if="editIndex!=-1">Update</span>
								<span v-if="editIndex==-1">Save</span>
							</button>
							<button title="New" class="btn btn-primary btn-sm" v-show="editIndex!=-1" v-on:click.prevent="initialize">
								<span class="glyphicon glyphicon-plus-sign"></span> New
							</button>
						</div>	

					</form>
								
				</div>
			</div>
		</div>
	</div>

	<div class ="form-group pull-right">
		<a class="btn btn-default btn-sm custom-btn-width" href="{{ url('contracts/' . $contract->id . '/edit')  }}" >Back</a>
	</div>

</div>

@endsection

@push('script-bottom')
<script type="text/javascript">
	var url = "{{ url('/') }}";
	var contract = <?php echo json_encode($contract);?>;
</script>

<script src="{{ asset('js/reductions.js') }}"></script>
@endpush
@extends('layouts.app')

@section('content')	

<div id="teleworking" class ="container">

	@include('reductions.header')	
		
	<div class="panel panel-primary">

		  <div class="panel-heading">
		    	Teleworking for this user
		  </div>

		  <div class="panel-body">
				<div class="row">
					<span v-for="(item, index) in array">					
						<span v-if="editIndex == index">
							<teleworking-template :item="item" :index="index" :prop="true"></teleworking-template>
						</span>
						<span v-else>
							<teleworking-template :item="item" :index="index" :prop="false"></teleworking-template>
						</span>
					</span>
				</div>
		  </div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<span v-if="editIndex==-1">Adding teleworking to a contract</span>	
					<span v-if="editIndex!=-1">Editing teleworking</span>	
				</div>

				<div class="panel-body">

					<label>Days</label>
					<div class="form-group">		
						<span v-for="day in daysWeek">
							<div class="checkbox">
						    	<label>
						      		<input type="checkbox" v-model="newTeleworking[day]"> @{{day.toUpperCase()}}
						    	</label>
						  	</div>				
						</span>
					</div>

					<div class="form-group">	
						<div class="row">	
							<div class="col-xs-12 col-sm-2">
								<label>Start date</label>
								<input id="startdatefield" name="start_date" type ="date" class="form-control input-sm" placeholder="yyyy-mm-dd" v-model="newTeleworking.start_date">
							</div>	

							<div class="col-xs-12 col-sm-2">
								<label>End date</label>
								<input id="enddatefield" name="end_date" type ="date" class="form-control input-sm" placeholder="yyyy-mm-dd" v-model="newTeleworking.end_date">
							</div>	
						</div>
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
	var daysWeek = <?php echo json_encode(config('options.daysWeek'));?>;
</script>

<script src="{{ asset('js/teleworking.js') }}"></script>
@endpush
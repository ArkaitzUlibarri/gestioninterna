@extends('layouts.app')

@section('content')	

<div id="teleworking" class ="container">

	<div class="row">
		<div class ="form-group col-xs-12 col-sm-4">
			<h2>{{ strtoupper($contract->user->full_name) }}</h2>				
		</div>
	</div>

	<div class="panel panel-primary">

		  <div class="panel-heading">
		    	Reduction for this user
		  </div>

		  <div class="panel-body">
				<div class="row">
					
					
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

					<div class="row">	
						<div class="col-xs-12 col-sm-2">
							<label>New Working Hours</label>
							<input type="number" min=0 max=40 class="form-control" placeholder="Time">
						</div>	

						<div class="col-xs-12 col-sm-2">
							<label>Start date</label>
							<input name="created_at" type ="date" class="form-control" min="2017-01-01" placeholder="yyyy-mm-dd">
						</div>	

						<div class="col-xs-12 col-sm-2">
							<label>End date</label>
							<input name="created_at" type ="date" class="form-control" min="2017-01-01" placeholder="yyyy-mm-dd">
						</div>	
					</div>
				
					<div class="form-group">	
						<button title="Save" class="btn btn-primary">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save
						</button>
						<button title="New" class="btn btn-primary">
							<span class="glyphicon glyphicon-plus-sign"></span> New
						</button>
					</div>	
					
				</div>
			</div>
		</div>
	</div>

	<a class="btn btn-primary" href="{{ url('contracts') }}">Back</a>
</div>

@endsection

@push('script-bottom')

@endpush
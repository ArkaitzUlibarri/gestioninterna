<div class="panel panel-primary">

	<div class="panel-heading">
	    Contract Details
	 </div>

	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12 col-sm-4">	
				<label>Employee</label>
				<input class="form-control input-sm" type="text" placeholder="{{ ucwords($contract->user->fullname) }}" readonly>
			</div>	

			<div class="col-xs-12 col-sm-2">
				<label>Week Working Hours</label>
				<input class="form-control input-sm" type ="text" value="{{$contract->week_hours}}" readonly>
			</div>	

			<div class="col-xs-12 col-sm-2">
				<label>Start date</label>
				<input class="form-control input-sm" type ="date" placeholder="yyyy-mm-dd" value="{{$contract->start_date}}" readonly>
			</div>	

			<div class="col-xs-12 col-sm-2">
				<label>Estimated end date</label>
				<input class="form-control input-sm" type ="date" placeholder="yyyy-mm-dd" value="{{$contract->estimated_end_date}}" readonly>
			</div>	

			<div class="col-xs-12 col-sm-2">
				<label>End date</label>
				<input class="form-control input-sm" type ="date" placeholder="yyyy-mm-dd" value="{{$contract->end_date}}" readonly>
			</div>	
		</div>
	</div>
	
</div>
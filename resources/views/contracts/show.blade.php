@extends('layouts.app')

@section('content')
	<div class="container">

		<dl class="dl-horizontal">
			<dt>Employee</dt>
			<dd>{{ $contract->user->fullname }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Type of contract</dt>
			<dd>{{ $contract->contractType->name }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Weekly working hours</dt>
			<dd>{{ $contract->week_hours}}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Start date</dt>
			<dd>{{ $contract->start_date}}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Estimated end date</dt>
			<dd>{{ empty($contract->estimated_end_date) ? "None": $contract->estimated_end_date }}</dd>
		</dl>	

		<dl class="dl-horizontal">
			<dt>End date</dt>
			<dd>{{ empty($contract->end_date) ? "In progress" : $contract->end_date}}</dd>
		</dl>
		
		<dl class="dl-horizontal">
			<dt>Bank holidays</dt>
			<dd>{{ $nationalDayName }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Region bank holidays</dt>
			<dd>{{ $regionalDayName }}</dd>
		</dl>

		<dl class="dl-horizontal">
			<dt>Local bank holidays</dt>
			<dd>{{ $localDayName }}</dd>
		</dl>
		

		@include ('contracts.teleworkingInfo')
		@include ('contracts.reductionInfo')
		
		<dl class="dl-horizontal">
			<dt><a title="Back" class="btn btn-primary" href="{{ url('contracts') }}">Back</a></dt>
		</dl>	

	</div>

@endsection
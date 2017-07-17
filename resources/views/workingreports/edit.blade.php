@extends('layouts.app')

@section('content')

<div class="row">
	<div class ="col-xs-12">
		<h2>Working Report</h2>
	</div>
</div>

<span v-if="contract">	
	@include('workingreports.partials.details')
		
	@include('workingreports.partials.tasks')

	@include('workingreports.partials.task')
</span>
<span v-else>
	<div class="panel panel-danger">
		  <div class="panel-heading">
		    	<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Error
		  </div>
		  <div class="panel-body">
		  		User without an active contract
		  </div>
	</div>
</span>

@include('layouts.errors')

<div class ="form-group pull-right">
	<a class="btn btn-default btn-sm custom-btn-width" href="{{ url('workingreports') }}">Back</a>
</div>

@endsection

@push('script-bottom')
	<script type = "text/javascript">
		var url = "{{ url('/') }}";
		var reportdate = '{{ $date }}';
		var role = '{{ Auth::user()->role }}';
		var report_user = <?php echo json_encode($reportUser);?>;
		var teleworking = <?php echo json_encode($teleworking);?>;
		var absences = <?php echo json_encode($absences);?>;
		var groupProjects = <?php echo json_encode($groupProjects);?>;
		var categories = <?php echo json_encode($categories);?>;
		var user_contract = <?php echo json_encode($contract);?>;
	</script>
	
    <script src="{{ asset('js/workingreports.js') }}"></script>
@endpush

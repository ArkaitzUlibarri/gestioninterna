@extends('layouts.app')

@section('content')

	<span v-if="contract">	

		<div class="row">
			<div class ="col-xs-12">
				<h2>Working Report</h2>
			</div>
		</div>

		@include('workingreports.editPartials.details')
		@include('workingreports.editPartials.tasks')
		@include('workingreports.editPartials.task')
		@include('layouts.errors')
		@include('workingreports.partials.back')
	</span>

	<div class="col-md-6 col-md-offset-3" v-else>
		@include('layouts.contractError')
		@include('layouts.errors')
		@include('workingreports.partials.back')
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
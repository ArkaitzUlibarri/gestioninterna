@extends('layouts.app')

@section('content')

	<div class="panel panel-primary">

	   	<div class="panel-heading">
	   		@include('evaluations.partials.filter')
			<h3 class="panel-title" style="margin-top: 7px;">PERFORMANCE EVALUATION</h3>
	        <div class="clearfix"></div>
	    </div>

	    <div class="panel-body" v-show="filter.employee != '' && filter.year != ''">

	    	<!--Formulario-->

			<!--Resto de Campos-->
			@if (Auth::user()->primaryRole() == 'admin' || Auth::user()->primaryRole() == 'manager')	 
				@component('evaluations.partials.forms.component')
					@slot('filter')
						@include('evaluations.partials.forms.filters.project')
					@endslot
					@slot('body')
						<tr v-for="criterion in generalform">
	                        @include('evaluations.partials.forms.row')
	                    </tr>
					@endslot
				@endcomponent
		    @endif

		    <!--Campo knowledge-->
		    @if (Auth::user()->primaryRole() == 'admin')	  
				@component('evaluations.partials.forms.component')
					@slot('filter')
					@endslot
					@slot('body')
	                    <tr v-for="criterion in knowledgeform">
	                        @include('evaluations.partials.forms.row')
	                    </tr>
					@endslot
				@endcomponent
		    @endif

		    <!--Tablas-->

		    <div v-show="reports.length != 0">
				<div v-for="element in reports">	
					@component('evaluations.partials.tables.component')
						@slot('title')
							@{{ getEmployee() }} | @{{ element.project }} | @{{ parseFloat(element.hours).toFixed(2) }} Hours |
						@endslot
						@slot('column')
							<th title="Average" class="active">Avg.</th>
						@endslot
						@slot('body')
							@include('evaluations.partials.tables.body.project')
						@endslot
					@endcomponent
				</div>
				@component('evaluations.partials.tables.component')
						@slot('title')
							@{{ getEmployee() }} | TOTAL | @{{ getTotalHours() }} Hours |
						@endslot
						@slot('column')
							<th title="Total" class="info">Total</th>
						@endslot
						@slot('body')
							@include('evaluations.partials.tables.body.total')
						@endslot
				@endcomponent
			</div>

	    </div>

	</div>

@endsection

@push('script-bottom')

<script type="text/javascript">
	var url = "{{ url('/') }}";
	var config = <?php echo json_encode(config('options.performance_evaluation'));?>;
	var auth_p = <?php echo json_encode($projects);?>;
	var months = <?php echo json_encode(config('options.months'));?>;
</script>

<script src="{{ asset('js/performance.js') }}"></script>

@endpush
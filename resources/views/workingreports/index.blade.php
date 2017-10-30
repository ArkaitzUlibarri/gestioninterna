@extends('layouts.app')

@section('content')
<div class="panel panel-primary"> 

	<div class="panel-heading"> 
		@include('workingreports.indexPartials.filter')
		<h3 class="panel-title" style="margin-top: 7px;">WORKING REPORTS</h3>
		<div class="clearfix"></div>
	</div>
		
	<div class="panel-body">
		
		<table class="table table-hover table-condensed">
			<thead>
				<th>Employee</th>
				<th>Week | Day</th>
				<th>Date</th>
				<th>Stated Hours</th>
				<th>PM</th>
				<th>ADMIN</th>
			</thead>
   
			<tbody>
				<tr v-for="(item, index) in reports" :class="{success:item.horas_validadas_admin == 0}">
					<td class="col-md-4">@{{ item.fullname.toUpperCase() }}</td>
					<td class="col-md-2">@{{ getWeek(1,item.created_at) }} | @{{ getDayWeek(item.created_at) }}</td>
					<td class="col-md-2">   
						<a id="ref" v-bind:href="makeUrl('{{ url('workingreports/add/') }}', [item.user_id, item.created_at])"  title="Edit" aria-hidden="true">
							@{{ item.created_at }}
						</a>               
					</td>
					<td class="col-md-2">@{{ item.horas_reportadas }}</td>  
					<td class="col-md-1">
						<div v-if="item.horas_validadas_pm != 0">
							<span class="glyphicon glyphicon-remove" style="color:red" aria-hidden="true"></span>
						</div>
						<div v-else>
							<span class="glyphicon glyphicon-ok" style="color:green" aria-hidden="true"></span>
						</div>                                  
					</td>
					<td class="col-md-1">
						<div v-if="item.horas_validadas_admin != 0">
							<span class="glyphicon glyphicon-remove" style="color:red" aria-hidden="true"></span>
						</div>
						<div v-else>
							<span class="glyphicon glyphicon-ok" style="color:green" aria-hidden="true"></span>
						</div>  
					</td>
				</tr>
			</tbody>
				 
		</table>
		
		<hr>

		<div class="btn-toolbar">

			<div class="btn-group">
				{{ $workingreports->links() }}
			</div>

			<div class="btn-group pull-right">

				@if(Auth::user()->primaryRole() == 'admin')			
					<select class="input-sm" v-model="user_report">
						@foreach($users as $user)				
							<option value="{{$user->id}}">{{ucwords($user->fullname)}}</option>
						@endforeach
					</select>	
				@endif

				<a type="button" class="btn btn-success btn-sm pull-right" v-bind:href="makeUrl('{{ url('workingreports/add/') }}', [user_report, getDate()])" >
					New Report
				</a>
				
			</div>

		</div>

	</div>	
</div>

@endsection

@push('script-bottom')

	<style>
		.pagination{
		    display: inline;
		    margin-left: 0.5em;
		    margin-right: 0.5em;
		}
	</style>

	<script type = "text/javascript">
		var url = "{{ url('/') }}";
		var workingreport = <?php echo json_encode($workingreports->items());?>;
		var auth_user = <?php echo json_encode(Auth::user());?>;
		var users = <?php echo json_encode($users);?>;
		var pm = "Auth::user()->primaryRole() == 'manager' ? 1 : 0";
	</script>

	<script src="{{ asset('js/day_validation.js') }}"></script>

@endpush
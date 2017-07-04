@extends('layouts.app')

@section('content')
<div class="panel panel-primary"> 

	<div class="panel-heading"> 
		@include('workingreports.partials.filter')
		<h3 class="panel-title" style="margin-top: 7px;">WORKING REPORTS</h3>
		<div class="clearfix"></div>
	</div>
		
	<div class="panel-body">
		<div class="table-responsive" style="margin: 2em 0 5em 0;">
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
					<tr v-for="(item, index) in reports">
						<td>@{{ item.fullname.toUpperCase() }}</td>
						<td>@{{ getWeek(1,item.created_at) }} | @{{ getDayWeek(item.created_at) }}</td>
						<td>   
							<a id="ref" v-bind:href="makeUrl('{{ url('workingreports/add/') }}', [item.user_id, item.created_at])"  title="Edit" aria-hidden="true">
								@{{ item.created_at }}
							</a>               
						</td>
						<td>@{{ item.horas_reportadas }}</td>  
						<td>
							<div v-if="item.horas_validadas_pm != 0">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</div>
							<div v-else>
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							</div>                                  
						</td>
						<td>
							<div v-if="item.horas_validadas_admin != 0">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</div>
							<div v-else>
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							</div>  
						</td>
					</tr>
				</tbody>
					 
			</table>
		</div>

		<hr>

		<div class="form-inline pull-right">

			@if(Auth::user()->primaryRole() == 'admin')
				<div class="form-group">			
					<select class="form-control input-sm" v-model="user_report">
						@foreach($users as $user)				
							<option value="{{$user->id}}">{{ucwords($user->fullname)}}</option>
						@endforeach
					</select>
				</div>
			@else
				<input class="form-control input-sm" type="text" placeholder="{{ ucwords(Auth::user()->fullname) }}" readonly>
			@endif

			<div class="form-group">
				<a type="button" class="btn btn-success btn-sm" v-bind:href="makeUrl('{{ url('workingreports/add/') }}', [user_report, getDate()])" >
					New Report
				</a>
			</div>

		</div>
	</div>	
</div>

@endsection

@push('script-bottom')
	<script type = "text/javascript">
		var url = "{{ url('/') }}";
		var workingreport = <?php echo json_encode($workingreports);?>;
		var auth_user = <?php echo json_encode(Auth::user());?>;
		var users = <?php echo json_encode($users);?>;
		var pm = "Auth::user()->primaryRole() == 'manager' ? 1 : 0";
	</script>

	<script src="{{ asset('js/validate.js') }}"></script>
@endpush
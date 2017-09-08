@extends('layouts.app')

@section('content')

	<div class="panel panel-primary">

	   	<div class="panel-heading">
	   		@include('evaluations.filter')
			<h3 class="panel-title" style="margin-top: 7px;">PERFORMANCE EVALUATION</h3>
	        <div class="clearfix"></div>
	    </div>

	    <div class="panel-body">
	    	<div class="table-responsive">
		        <table class="table table-hover table-condensed">

		            <thead>
		                <th>Criteria</th>
		                <th>Mark</th>   
		                <th>Comments</th>
		            </thead>

		            <tbody>
		            	@foreach (config('options.performance_evaluation') as $key => $options)
			                <tr>

			                    <td class="col-md-2">
			                    	<span data-toggle="tooltip" data-placement="top" title="{{$options['description']}}" class="glyphicon glyphicon-info-sign"></span> 
			                    	<span data-toggle="tooltip" data-placement="top" title="{{$options['name']}} (Peso: {{$options['percentage']}}%)">{{ ucwords($key) }}</span> 
			                    </td>

			                    <td class="col-md-1">
			                    	<select class="form-control input-sm" :disabled="validateFilter"> 
		                    			<option selected="true" value="">-</option>
	                    				@foreach ($options['points'] as $point)
											<option title="{{ $point['description'] }}" value="{{ $point['value'] }}">{{ $point['value'] }}</option>
										@endforeach
			                    	</select>
			                    </td>  

			                    <td class="col-md-9">		
				                    <div class="form-group">
										<textarea class="form-control input-sm" rows="1" placeholder="Comments" :disabled="validateFilter"></textarea>
									</div>
								</td>  

			                </tr>
		                @endforeach
		            </tbody>

		        </table>

		    </div>

    		<div class="pull-right">		
				<button title="Save" class="btn btn-primary btn-sm">
					<span class="glyphicon glyphicon-floppy-disk"></span> Save
				</button>
			</div>
	    </div>

	</div>

	<span v-show="filter.employee != '' && filter.project != ''">
		@include('evaluations.project')
	</span>

	<span v-show="filter.employee != ''">
		@include('evaluations.total')
	</span>

@endsection

@push('script-bottom')
	<script>
		
		var app = new Vue({
			el:'#app',

			data:{

				// User's data
            	user_id: '{!! Auth()->user()->id !!}',
            	auth_projects: <?php echo json_encode($projects);?>,

            	//List
				monthList: <?php echo json_encode(config('options.months'));?>,
				employeeList: [],
				projectList: [],

				reports:[],

				//Filter options
				filter:{
					employee:'',
					project:'',
					year: moment().year(),
					month: ''//moment().month() + 1
				},

				//Evaluation
				/*
				marks:{
					quality:'',
					eficiency:'',
					knowledge:'',
					availability:''
				},

				comments:{
					quality:'',
					eficiency:'',
					knowledge:'',
					availability:''
				}
				*/
			
				marks:[],
				comments:[]

			},

			mounted(){

			},

			computed:{

	            validateFilter() {
					return (this.filter.year == '' || this.filter.month == '' || this.filter.employee == '' || this.filter.project == '')
						? true
						: false;
				},

				validateSaveButton() {

				},

			},

			methods: {

				/**
	             * Fetch employees who have reported in pm projects
	             */
	            fetchEmployees () {
	                var vm = this;

	                this.projectList = [];
	                this.employeeList = [];
	                this.filter.project = '';
	                this.filter.employee = '';

	                axios.get('api/employees', {
	                        params: {
	                        	year: vm.filter.year,
	                            month: vm.filter.month,               
	                        }
	                    })
	                    .then(function (response) {   
	                        vm.employeeList = response.data;
	                    })
	                    .catch(function (error) {
	                       vm.showErrors(error.response.data)
	                    });
	            },

				/**
	             * Fetch projects to evaluate for a user
	             */
	            fetchProjects () {
	                var vm = this;

	                if(this.filter.employee == ''){
	                	return;
	                }

	                this.projectList = [];
	                this.filter.project = '';

	                axios.get('api/projects', {
	                        params: {
	                        	year: vm.filter.year,
	                            month: vm.filter.month,
	                            employee: vm.filter.employee                  
	                        }
	                    })
	                    .then(function (response) {   
	                        vm.reports = response.data
	                        vm.filterReports();
	                    })
	                    .catch(function (error) {
	                       vm.showErrors(error.response.data)
	                    });
	            },

		        filterReports(){
			        let vm = this;
					let setList = new Set();
					
					if(this.reports == null){
	                    return null;
	                }

	                this.projectList = [];
	                filtered = [];

					vm.reports.forEach(function(item) {						
						for (let key in vm.auth_projects) {				
							if(key == item.project_id){
								filtered.push([key,vm.auth_projects[key]]);
							}
						}
					});

					this.projectList = filtered;
		        },

	            monthStyle(value) {
	            	return this.filter.month == value ? 'danger':'';
	            },

	            getEmployee() {
	            	let vm = this;
	            	let name;

	            	if(this.filter.employee != ''){
		         		this.employeeList.forEach(function(item){
		            		if(item.id == vm.filter.employee){
		            			name = item.full_name;
		            		}
		            	});

		            	return name.toUpperCase();
	            	}
	            },

	           	getProject() {
	            	let vm = this;
	            	let name;
	            	
	            	if(this.filter.project != ''){

	            		for (let key in vm.auth_projects) {	
	            			if(key == vm.filter.project){
		            			name = vm.auth_projects[key];
		            		}
	            		}

		            	return name.toUpperCase();
	            	}            	
	            },

	            getHours(hours) {
	            	let vm = this;
	            	let amount;
	            	let total = 0;
	            
            		if(this.filter.project == ''){
            			return '';
            		}

        			this.reports.forEach(function(item){
        				if(vm.filter.project == item.project_id){
        					amount = item.hours;
        				} 
        				total += parseFloat(item.hours);
        			});	
            		
            		//Hours
            		if(hours){
            			return amount;
	            	}

	            	//Percentage
	            	return ((amount/total)*100).toFixed(2);
	            },

		         /**
	             * Visualizo mensajes de error
	             */
	            showErrors(errors) {
	                if(Array.isArray(errors)) {
	                    errors.forEach( (error) => {
	                        toastr.error(error);
	                    })
	                }
	                else {
	                    toastr.error(errors);
	                }
	            },

	            makeUrl(url, data = null) {
	                if (data != null) {
	                    return url + '/' + data.join('/');
	                }
	                return url;
	            },

			}

		});

	</script>
@endpush
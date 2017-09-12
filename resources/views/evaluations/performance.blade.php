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
		            	<tr v-for="criterion in criteria">

		            		<td class="col-md-2">
		                    	<span data-toggle="tooltip" data-placement="top" :title="criterion.description" class="glyphicon glyphicon-info-sign"></span> 
		                    	<span data-toggle="tooltip" data-placement="top" :title="criterion.name + ' (Peso: ' + criterion.percentage +'%)'">@{{ capitalizeFirstLetter(criterion.code) }}</span> 
		                    </td>

							<td class="col-md-1">
		                    	<select class="form-control input-sm" :disabled="validateFilter" v-model="criterion.mark"> 
	                    			<option selected="true" value="">-</option>
									<option v-for="point in criterion.points" :title="point.description" :value="point.value">@{{ point.value }}</option>					
		                    	</select>
		                    </td>  

		                    <td class="col-md-9">		
			                    <div class="form-group">
									<textarea class="form-control input-sm" rows="1" placeholder="Comments" v-model="criterion.comment" :disabled="validateFilter"></textarea>
								</div>
							</td>   

		                </tr>
		            </tbody>
		        </table>

		    </div>

    		<div class="pull-right">		
    			<button title="Clear" class="btn btn-primary btn-sm" :disabled="validateClearButton" v-on:click="clear()">
					<span class="glyphicon glyphicon-erase"></span> Clear
				</button>

				<button title="Save" class="btn btn-primary btn-sm" :disabled="validateSaveButton" v-on:click="save()">
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
				//Evaluation					
				criteria: [],
				evaluation: <?php echo json_encode(config('options.performance_evaluation'));?>,

				// User's data
            	//user_id: '{!! Auth()->user()->id !!}',
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
				}
	
			},

			mounted(){
				this.setForm();
			},

			computed:{

	            validateFilter() {
					return (this.filter.year == '' || this.filter.month == '' || this.filter.employee == '' || this.filter.project == '')
						? true
						: false;
				},

				validateSaveButton() {				
					let marksCount = 0;

					this.criteria.forEach(function(item){
						if( !(item.mark === '')){
							marksCount++;
						}
					});	

					return (marksCount == 4) ? false : true;				
										
				},

				validateClearButton() {

					let marksCount = 0;
					let commentsCount = 0;

					this.criteria.forEach(function(item){
						if( item.mark === ''){
							marksCount++;
						}
						if( item.comment === ''){
							commentsCount++;
						}
					});	

					return (marksCount == 4 && commentsCount == 4) ? true : false;
					
				},

			},

			methods: {

				setForm() {
					for (let i = this.evaluation.length - 1; i >= 0; i--) {
						this.criteria.push({
							code: this.evaluation[i]['code'],
							description : this.evaluation[i]['description'],
							name: this.evaluation[i]['name'],
							percentage: this.evaluation[i]['percentage'],
							points: this.evaluation[i]['points'],
							comment: '',
							mark: ''
						});
					}
				},

				save() {
					var vm = this;
					
					axios.post('api/performance_evaluation',vm.form)
					  .then(function (response) {
					  	console.log(response.data);
					  	//Mensaje de Guardado
					  	//toastr.success("Saved");
					  	//Actualizar tablas  
					  })
					  .catch(function (error) {
					    console.log(error);
					    //vm.showErrors(error.response.data)
					  });
				},

				/**
	             * Fetch employees who have reported in pm projects
	             */
	            fetchEmployees() {
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
	            fetchProjects() {
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

		        filterReports() {
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

		        clear() {

		        	this.criteria.forEach(function(item){
						item.mark = '';
						item.comment = '';
					});

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

				capitalizeFirstLetter(string) {
    				return string.charAt(0).toUpperCase() + string.slice(1);
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
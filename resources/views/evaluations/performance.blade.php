@extends('layouts.app')

@section('content')

	<div class="panel panel-primary">

	   	<div class="panel-heading">
	   		@include('evaluations.filter')
			<h3 class="panel-title" style="margin-top: 7px;">PERFORMANCE EVALUATION</h3>
	        <div class="clearfix"></div>
	    </div>

	    <div class="panel-body">
			@include('evaluations.form')
			@include('evaluations.footer')
	    </div>

	</div>

	<span v-show="filter.employee != '' && filter.project != ''">
		@include('evaluations.project')
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
				projectList: {},

				reports:[],

				//Filter options
				filter:{
					employee:'',
					project:'',
					year: moment().year(),
					month: ''//moment().month() + 1
				},

				//Tables
				pTable: [],
				pTableTotal:{},
				pTotal: 0,

				tTable:[],
				tTableTotal:[],
				tTotal: 0
	
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

				validateDeleteButton() {
					let idsCount = 0;

					this.criteria.forEach(function(item){
						if( !(item.id === '')){
							idsCount++;
						}
					});	

					return (idsCount == 4) ? false : true;	
				},

				validateSaveButton() {				
					let marksCount = 0;
					let errorsCount = 0;

					this.criteria.forEach(function(item){
						if( !(item.mark === '')){
							marksCount++;
						}
						if( item.code != 'knowledge' && item.mark != 2 && item.comment === ''){
							errorsCount++;
						}
					});	

					return (marksCount == 4 && errorsCount == 0) ? false : true;													
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
							id: '',
							code: this.evaluation[i]['code'],
							description : this.evaluation[i]['description'],
							name: this.evaluation[i]['name'],
							percentage: this.evaluation[i]['percentage'],
							points: this.evaluation[i]['points'],
							mark: '',
							comment: '',
							weight:'',
						});
					}
				},

				save() {
					var vm = this;

					vm.criteria.forEach(function(item){
						item.year = vm.filter.year;
						item.month = vm.filter.month;
						item.user_id = vm.filter.employee;
						item.project_id = vm.filter.project;
					});

					axios.post('/api/performance-evaluation', vm.criteria)
					  .then(function (response) {
						  	//Mensaje de Guardado
						  	toastr.success("SAVED");

						  	//Asignar ids
						  	for (var i = vm.criteria.length - 1; i >= 0; i--) {
						  		vm.criteria[i].id = response.data[i];
						  	}

						  	//Actualizar tablas  
						  	//TODO
						  	
						  	//Limpiar formulario
						  	//vm.clear();
					  })
					  .catch(function (error) {
					    	vm.showErrors(error.response.data)
					  });
				},

				erase() {		
					let vm = this;
					let ids = [];

					vm.criteria.forEach(function(item){
						ids.push(item.id);
					});

		            axios.delete('/api/performance-evaluation/'+ ids)
		                .then(function (response) {  

		                	//Mensaje de Guardado
						  	toastr.success(response.data);    
						  	
						  	//Limpiar formulario
		                	//vm.clear();
		                })
		                .catch(function (error) {
		                    vm.showErrors(error.response.data)
		                }); 
				},

				/**
	             * Fetch projects table performance by user,project and year
	             */
	            fetchProjectTable() {
	                var vm = this;

	                axios.get('api/project_table', {
	                        params: {
	                        	year: vm.filter.year,
	                            project: vm.filter.project,
	                            employee: vm.filter.employee                  
	                        }
	                    })
	                    .then(function (response) {    
							vm.pTable = response.data;    
							vm.pTableTotal = vm.calculateProjectTotals();  
							//vm.pTotal = vm.getProjectTableTotal();    
	                    })
	                    .catch(function (error) {	          
	                       	vm.showErrors(error.response.data)
	                    });
	            },

				/**
	             * Fetch employees who have reported in pm projects
	             */
	            fetchEmployees() {
	                var vm = this;

	                this.projectList = {};
	                this.employeeList = [];
	                this.filter.project = '';
	                this.filter.employee = '';
	                this.clear();

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

	                this.projectList = {};
	                this.filter.project = '';
	                this.clear();

	                if(this.filter.employee === ''){
	                	return;
	                }

	                axios.get('api/month_reports', {
	                        params: {
	                        	year: vm.filter.year,
	                            month: vm.filter.month,
	                            employee: vm.filter.employee                  
	                        }
	                    })
	                    .then(function (response) {   
	                        vm.reports = response.data;
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

	                this.projectList = {};
               
					vm.reports.forEach(function(item) {						
						for (let key in vm.auth_projects) {				
							if(key == item.project_id){
								if(vm.projectList[key] == undefined){
									vm.projectList[key] = {
										id: key,
										name: vm.auth_projects[key]
									};
								}						
							}
						}
					});									
		        },

		        /**
		         * Limpia el Formulario
		         */
		        clear() {

		        	this.criteria.forEach(function(item){
		        		item.id = '';
						item.mark = '';
						item.comment = '';
						item.weight = '';
					});

				},

				projectChange(){
					if(this.filter.project !=""){
						this.setWeight();
						this.fetchProjectTable();
					}	
				},

				setWeight(){
					let vm = this;
					this.criteria.forEach(function(item){
						item.weight = vm.getHours(false);
					});
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
	            	let amount = 0;
	            	let total = 0;
	            
            		if(this.filter.project == ''){
            			return '';
            		}

        			this.reports.forEach(function(item){
        				if(vm.filter.project == item.project_id){
        					amount += parseFloat(item.hours);
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

	            getMarkComment(key,description) {
	            	let output;   	

	            	if(this.pTable.length == 0 || this.pTable[key] == undefined ){
	            		return description == 'mark' ? '-' : ''; 
	            	}
	            	return description == 'mark' ? this.pTable[key].mark : this.pTable[key].comment;
	            },

	            getTotal(key) {
	            	let output;   	

	            	if(this.pTableTotal.length == 0 || this.pTableTotal[key] == undefined ){
	            		return  '-'; 
	            	}
	            	return this.pTableTotal[key].total;
	            },

	            getProjectTableTotal() {
	            	let vm = this;
	            	let result = 0;
            	
	            	if(this.pTableTotal != undefined){
		            	this.criteria.forEach(function(item){
		            		if(vm.pTableTotal[item.code] != undefined){
		            			let percentage = item.percentage;
		            			let total = vm.pTableTotal[item.code].total;
		            			let max_value = item.points.length - 1;
		            			let partial = total/max_value * percentage;
		            			//console.log("partial:"+ partial);
		            			result = parseFloat(result) + parseFloat(partial);
		            			//console.log("result:" + result.toFixed(2));
		            		}
		            	});

		            	return result.toFixed(2);
	            	}          	
	            },

	            calculateProjectTotals() {
  	
  					let criteria = "";
  					let index = 0;
  					let output = {};
  					let total = {
  						zeros: 0,
  						counter: 0,
  						sum: 0,
  						name: '',
  						total: 0
  					};

		            for(var key in this.pTable) {

		            	index++;

		            	if(criteria == ""){
		            		criteria = key.split("|")[0];
		            		total = {
		            			zeros: 0,
		  						counter: 0,
		  						sum: 0,
		  						name: criteria,
		  						total: 0
		  					};	
		            	}

		            	if(criteria != key.split("|")[0]){
		            		total.total = ((total.counter * Math.pow(0.66,total.zeros)) / total.sum).toFixed(2);
		            		//output.push(total);
		            		output[total.name] = total;
		            		criteria = key.split("|")[0];
		            		total = {
		            			zeros: 0,
		  						counter: 0,
		  						sum: 0,
		  						name: criteria,
		  						total: 0
		  					};	            		            		
		            	}	
		            	if(this.pTable[key].mark == 0){
		            		total.zeros += 1;
		            	}
		            	total.sum += 1;         
		            	total.counter += this.pTable[key].mark;

      					if(index == Object.keys(this.pTable).length){
      						total.total = ((total.counter * Math.pow(0.66,total.zeros)) / total.sum).toFixed(2);
      						//output.push(total);
      						output[total.name] = total;
      					}

		            }

		            return output;
	            },

				/**
	             * Marca de otro estilo el mes seleccionado
	             */
	            monthStyle(value) {
	            	return this.filter.month == value ? 'danger':'';
	            },

		         /**
	             * Convertir a Mayúsculas primera letra
	             */
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
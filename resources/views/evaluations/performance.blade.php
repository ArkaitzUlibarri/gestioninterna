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
		<span v-for="element in reports">	
			@include('evaluations.project')
		</span>
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
            	role: '{!! Auth()->user()->primaryRole() !!}',
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
					month: moment().month() + 1//''
				},

				//Tables
				pTable: [],
				pTableTotal:{},
				
				tTable:[],
				tTableTotal:[],
				
			},

			mounted(){
				this.setForm();
				this.fetchEmployees();//Quitar si filter.month no asignado
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

				/**
		         * Limpia el Formulario
		         */
		        clear() {
		        	this.criteria.forEach(function(item){
						item.mark = '';
						item.comment = '';
					});
				},

				/**
		         * Limpia el Formulario completo
		         */
		        fullclear() {
		        	this.criteria.forEach(function(item){
		        		item.id = '';
						item.mark = '';
						item.comment = '';
						item.weight = '';
					});
				},

				save() {
					var vm = this;
					let ids = [];

					vm.criteria.forEach(function(item){
						item.year = vm.filter.year;
						item.month = vm.filter.month;
						item.user_id = vm.filter.employee;
						item.project_id = vm.filter.project;
						item.weight = vm.getHours(false,item.project_id);
					});

					if(this.validateDeleteButton){

						axios.post('/api/performance-evaluation', vm.criteria)
						  .then(function (response) {

							  	//Mensaje de Guardado
							  	toastr.success("SAVED");

							  	//Asignar ids
							  	for (var i = vm.criteria.length - 1; i >= 0; i--) {
							  		vm.criteria[i].id = response.data[i];
							  	}

							  	//Actualizar tablas  
							  	vm.fetchProjectTable();
							  	
							  	//Limpiar formulario
							  	//vm.clear();

						  })
						  .catch(function (error) {
						    	vm.showErrors(error.response.data)
						    	vm.clear();
						  });

					}else{

						vm.criteria.forEach(function(item){
							ids.push(item.id);
						});

						axios.patch('/api/performance-evaluation/'+ ids, vm.criteria)
						  .then(function (response) {

							  	//Mensaje de Guardado
							  	toastr.success(response.data);

							  	//Actualizar tablas  
							  	vm.fetchProjectTable();
							  	
							  	//Limpiar formulario
							  	//vm.clear();

						  })
						  .catch(function (error) {
						    	vm.showErrors(error.response.data)
						    	vm.clear();
						  });
					}

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

						  	//Actualizar tablas  
							vm.fetchProjectTable();

						  	//Limpiar formulario
		                	vm.fullclear();
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
	                let key;

	                this.fullclear();

	                axios.get('api/project_table', {
	                        params: {
	                        	year: vm.filter.year,
	                            employee: vm.filter.employee                  
	                        }
	                    })
	                    .then(function (response) {   
	                    	//console.log(response.data) 
	                    	if(response.data.length != 0){
	                    		vm.criteria.forEach(function(item){
									key = vm.filter.project +"|"+ item.code +"|"+ vm.filter.month;
									if(response.data[key] != undefined){
										item.mark = response.data[key].mark;
										item.comment = response.data[key].comment;
										item.weight = response.data[key].weight;
										item.id = response.data[key].id;
									}
								});
		                    	vm.pTable = response.data;    
								vm.pTableTotal = vm.calculateTotalColumn(false);    
								vm.tTable = vm.getTotalTable();
								vm.tTableTotal = vm.calculateTotalColumn(true);
	                    	}                    	
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
	                this.fullclear();

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
	                this.fullclear();

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
               
					for (let project_id in vm.reports) {						
						for (let key in vm.auth_projects) {				
							if(key == project_id){
								if(vm.projectList[key] == undefined){
									vm.projectList[key] = {
										id: key,
										name: vm.auth_projects[key]
									};
								}						
							}
						}
					}								
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

	            getHours(hours, project_id = '') {
	            	let amount = 0;
	            	let total = 0;
	            
            		if(project_id === ''){
            			return '';
            		}

            		for(var key in this.reports) {
        				if(project_id == this.reports[key].project_id){
        					amount += this.reports[key].hours;
        				} 
        				total += parseFloat(this.reports[key].hours);
            		}
            		
            		//Hours
            		if(hours){
            			return amount;
	            	}

	            	//Percentage
	            	return ((amount/total)*100).toFixed(2);
	            },

	            getMarkComment(key,property,total) {
	            	let input;   	
	            	input = total ? this.tTable: this.pTable;

	            	if(input.length == 0 || input[key] == undefined ){
	            		return property == "mark" ? '-' : ''; 
	            	}

	            	if(property == "mark"){
	            		return input[key].mark.toFixed(1);
	            	}
	            	else if(property == "description"){
	            		return input[key].comment;
	            	}
	            	else if(property == "weight"){
	            		return input[key].weight;
	            	}
	            },

	            getTotalColumn(key,total) {	
	            	let input;   	
	            	input = total ? this.tTableTotal: this.pTableTotal;

	            	if(input == undefined ){
	            		return  '-'; 
	            	}
	            	else if(input[key] == undefined ){
	            		return  '-'; 
	            	}
	            	else if(input.length == 0 ){
	            		return  '-'; 
	            	}
	            	return input[key].total;
	            },

	            getTotalValue(project_id, total_table) {
	            	let vm = this;
	            	let result = 0;
	            	let error = "";
            		let input; 
            		let key;
            		let percentage;
            		let totalColumn;
            		let max_value;
            		let partial;

            		input = total_table ? this.tTableTotal: this.pTableTotal;

	            	if(input != undefined){
		            	this.criteria.forEach(function(item){
		            		key = total_table ? item.code : project_id + "|" + item.code;
		            		if(input[key] != undefined){
		            			percentage = item.percentage;
		            			totalColumn = input[key].total;
		            			if(totalColumn < parseFloat(1)){
		            				error = "error";
		            			}
		            			max_value = item.points.length - 1;
		            			partial = totalColumn/max_value * percentage;
		            			result = parseFloat(result) + parseFloat(partial);
		            		}
		            	});

		            	return error !="error" ? result.toFixed(2) : 0;
	            	}          	
	            },

	            calculateTotalColumn(total_table) {
  	
  					let criteria = "";
  					let project_id = "";
  					let output = {};
  					let input; 
  					let shortkey;
  					let obj = {
  						zeros: 0,
  						counter: 0,
  						sum: 0,
  						name: '',
  						project_id: 0,
  						total: 0
  					};

	            	input = total_table ? this.tTable : this.pTable;

		            for(var key in input) {

            			criteria = total_table ? key.split("|")[0] : key.split("|")[1];
		            	project_id = total_table ? 0 : key.split("|")[0];
		            	shortkey = total_table ? criteria : project_id + "|" + criteria;

		            	if(output != undefined){

			            	if(output[shortkey] === undefined){
			            		obj = {
			            			zeros: 0,
			  						counter: 0,
			  						sum: 0,
			  						name: criteria,
			  						project_id: project_id,
			  						total: 0
		  						};	
		  						output[shortkey] = obj;
			            	}

				           	if(input[key].mark == 0){
			            		output[shortkey].zeros += 1;
			            	}

			            	output[shortkey].sum += 1;
			            	output[shortkey].counter += input[key].mark;
			            	output[shortkey].total = (output[shortkey].name == 'knowledge' || !total_table)
		            			?(output[shortkey].counter/ output[shortkey].sum).toFixed(2)
		            			:((output[shortkey].counter * Math.pow((2/3),output[shortkey].zeros)) / output[shortkey].sum).toFixed(2);	
		            	}

		            }

		            return output;

	            },

	            getTotalTable() {	            	
	            	let vm = this;
  					let criteria;
  					let project_id
  					let key;
  					let shortkey;
  					let output = {};
  					let mark;
  					let weight;
	
  					for(let key in this.reports){
  						project_id = key;	

	  					vm.criteria.forEach(function(item){
	  						criteria = item.code;

			  				for (let month = 1; month <= 12; month++) {
			  					key = project_id + "|" + criteria + "|" + month;
								shortkey = criteria + "|" + month;
			  					if(vm.pTable[key] != undefined){
			  						weight = (vm.pTable[key].weight)/100;
			  						mark = vm.pTable[key].mark;
			  						if(output[shortkey] === undefined){
										output[shortkey] = {
			  								mark: 0 ,
			  								comment: '',
			  								weight: 0
			  							};
			  						}
			  						output[shortkey].mark += (parseFloat(mark) * parseFloat(weight));
			  					}	  
		  					}
	  					});	 	  					 					
  					}
  					return output;
	            },

				/**
	             * Marca de otro estilo el mes seleccionado
	             */
	            monthStyle(month_id) {
	            	return this.filter.month == month_id ? 'info' : '';
	            },

	            /**
	             * Marca el estilo de las celdas
	             */
	            cellStyleProject(key, total_column) {
	            	let input =  total_column ? this.pTableTotal : this.pTable ;
	            	let color = '';
	            	let criteria = key.split("|")[1];

	            	if(input[key] === undefined){
	            		return '';
	            	}
	            	if(criteria != 'knowledge'){
	            		if(total_column === false){
	            			color = input[key].mark == 3 ? 'success' : color;
	            			color = input[key].mark == 1 ? 'warning' : color;
	            			color = input[key].mark == 0 ? 'danger' : color;
	            		}
	            		else{
	            			color = input[key].total < parseFloat(1) ? 'danger' : color;
	            		}
	            	}
	            	return color;
	            },

	            	            /**
	             * Marca el estilo de las celdas
	             */
	            cellStyleTotal(key, total_column) {
	            	let input =  total_column ? this.tTableTotal : this.tTable ;
	            	let color = '';
	            	let criteria = key.split("|")[1];

	            	if(input[key] === undefined){
	            		return '';
	            	}
	            	if(criteria != 'knowledge'){
	            		if(total_column === false){
	            			color = input[key].mark == 3 ? 'success' : color;
	            			color = input[key].mark == 1 ? 'warning' : color;
	            			color = input[key].mark == 0 ? 'danger' : color;
	            		}
	            		else{
	            			color = input[key].total < parseFloat(1) ? 'danger' : color;
	            		}
	            	}
	            	return color;
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
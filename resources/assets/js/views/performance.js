/**
 * Registro los componentes necesarios.
 */

const app = new Vue({
	el:'#app',

	data:{

		//Evaluation					
		criteria: [],
		configuration: config,

		// User's data
    	auth_projects: auth_p,

    	//List
		monthList: months,
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
		this.fetchEmployees();
	},

	computed:{

		validateExportButton() {
			return (this.filter.year == '' || this.filter.employee == '') ? true : false;
		},

		knowledgeform() {
			let out = [];
			this.criteria.forEach(function(item){
				if(item.code == 'knowledge'){
					out.push(item);
				}
			});
			return out;
		},

		generalform() {
			let out = [];
			this.criteria.forEach(function(item){
				if(item.code != 'knowledge'){
					out.push(item);
				}
			});
			return out;
		},

	},

	methods: {

		/** ---------------------------BBDD --------------------------- **/
		/**
		 * Guardar criterio del formulario
		 */
		save(criterion) {
			var vm = this;

			//Assign filter values before save
			criterion.year = vm.filter.year;
			criterion.month = vm.filter.month;
			criterion.user_id = vm.filter.employee;
			criterion.project_id = criterion.code == 'knowledge' ? '' : vm.filter.project;
			criterion.weight = criterion.code == 'knowledge' ? '100' : vm.getHours(criterion.month, criterion.project_id);
			//criterion.comment = criterion.mark == 2 ? '': criterion.comment; 

			if(this.validateDeleteButton(criterion)){
				axios.post('/api/performance-evaluation', criterion)
				  .then(function (response) {
	
					  	toastr.success("SAVED");//Mensaje de Guardado
					  	criterion.id = response.data;//Asignar ids	  	 
					  	vm.fetchPerformance();//Actualizar tablas
					  	vm.loadFormValues();//Cargar valor
					  	//vm.clear(criterion);//Limpiar formulario

				  })
				  .catch(function (error) {
				    	vm.showErrors(error.response.data)
				    	vm.clear(criterion);
				  });
			}
		},

		/**
		 * Eliminar criterio del formulario
		 */
		erase(criterion) {		
			let vm = this;
			let ids = [];

            axios.delete('/api/performance-evaluation/'+ criterion.id)
                .then(function (response) {  
                	
				  	toastr.success(response.data);//Mensaje de Guardado
					vm.fetchPerformance();//Actualizar tablas 
                	vm.fullClear(criterion);//Limpiar formulario

                })
                .catch(function (error) {
                    vm.showErrors(error.response.data)
                }); 
		},

		/**
         * Fetch projects table performance by user and year
         */
        fetchPerformance() {
            var vm = this;
            let key;

            this.fullClear();
			this.perfomanceClear();

            axios.get('api/performance', {
                    params: {
                    	year: vm.filter.year,
                        employee: vm.filter.employee                  
                    }
                })
                .then(function (response) {   
                	//console.log(response.data) 
                	if(response.data.length != 0){
                    	vm.pTable = response.data;    
						vm.pTableTotal = vm.calculateTotalColumn(false);    
						vm.tTable = vm.getTotalTable();
						vm.tTableTotal = vm.calculateTotalColumn(true);
						vm.loadFormValues();											
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
            this.fullClear();

            axios.get('api/employees', {
                    params: {
                    	year: vm.filter.year,           
                    }
                })
                .then(function (response) {   
                    vm.employeeList = response.data;
                })
                .catch(function (error) {
                   vm.showErrors(error.response.data);
                });
	        },

		/**
         * Fetch reports to evaluate for a user
         */
        fetchReports() {
            var vm = this;

            this.projectList = {};
            this.filter.project = '';
            this.fullClear();

            if(this.filter.employee === ''){
            	return;
            }

            axios.get('api/employee_reports', {
                    params: {
                    	year: vm.filter.year,
                        employee: vm.filter.employee                  
                    }
                })
                .then(function (response) {   
                    vm.reports = response.data;
                    vm.filterProjects();//Filtrar proyectos por mes de reporte
                    vm.fetchPerformance();//Cargar evaluaciones                                     
                })
                .catch(function (error) {
                   vm.showErrors(error.response.data)
                });
	        },
        /** ---------------------------BBDD --------------------------- **/

		yearChange(){
			this.fullClear();//Limpiar formularios
			this.perfomanceClear();//Limpiar tablas
			this.reports = [];//Limpiar reportes
			this.filter.project = '';//Limpiar proyecto
			this.filter.month = moment().month() + 1;//Inicializar mes

			this.fetchEmployees();//Cargar empleados
		},

		employeeChange(){
			if(this.filter.employee != ''){
				this.fetchReports();//Cargar reportes
			}
			else{
				this.fullClear();//Limpiar formularios
				this.perfomanceClear();//Limpiar tablas
				this.reports = [];//Limpiar reportes
				this.filter.project = '';//Limpiar proyecto
				this.filter.month = moment().month() + 1;//Inicializar mes
			}
		},

		projectChange(){	
			if(this.filter.project !=""){
				this.fullClear();
				this.loadFormValues();
			}	
		},

		loadFormValues(){
			let vm = this;
    		vm.criteria.forEach(function(item){
				key = item.code == 'knowledge' 
					? "|"+ item.code +"|"+ vm.filter.month 
					: vm.filter.project +"|"+ item.code +"|"+ vm.filter.month;

				if(vm.pTable[key] != undefined){
					item.mark = vm.pTable[key].mark;
					item.comment = vm.pTable[key].comment;
					item.weight = vm.pTable[key].weight;
					item.id = vm.pTable[key].id;
				}
			});		
		},

		filterProjects(){
			let vm = this;
			
			if(this.reports == null){
                return null;
            }

            this.projectList = {};
            this.filter.project = '';
            this.fullClear();
            this.loadFormValues();
       	
			for (let project_id in this.reports) {						
				this.reports[project_id].groups.forEach(function(item){
					//Reportes/Proyectos del mes actual
					if(vm.filter.month == item.month){
						//Filtrar estos reportes en los proyectos que son del auth user
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
				});
			}	

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

        getTotalHours(month = ""){
			let totalMonth = 0;
			let total = 0;

			for(var key in this.reports) {         	
				//Todos
				this.reports[key].groups.forEach(function(item){
        			if(item.month == month){
        				totalMonth += parseFloat(item.hours);
        			}
        			total += parseFloat(item.hours);
        		});				
    		}

    		if(month === ""){
    			return (total == 0) ? 0.00 : total.toFixed(2);
    		}

        	return (totalMonth == 0) ? '-' : totalMonth.toFixed(2);
        },

        getHours(month,project_id,option ='') {
        	let amount = 0;
        	let total = 0;
        
    		if(project_id === ''){
    			return '';
    		}

    		for(var key in this.reports) {

    			//Mismo proyecto
				if(project_id == this.reports[key].project_id){
    				this.reports[key].groups.forEach(function(item){
        				if(item.month == month){
        					amount += parseFloat(item.hours);
        				}
        			});	
				} 
    	
				//Todos
				this.reports[key].groups.forEach(function(item){
        			if(item.month == month){
        				total += parseFloat(item.hours);
        			}
        		});	
		
    		}

    		if(option === ''){
        		return (amount == 0 || total == 0) ? '-' : ((amount/total)*100).toFixed(2);
    		}
    		
    		return (amount == 0) ? '-' : amount.toFixed(2);   		
        },

        getMarkComment(key,property,total) {
        	let input;   	
        	input = total ? this.tTable: this.pTable;

        	if(input.length == 0 || input[key] == undefined ){
        		return property == "mark" ? '-' : ''; 
        	}

        	if(property == "mark"){
        		return input[key].mark.toFixed(2);
        	}
        	else if(property == "description"){
        		return input[key].comment;
        	}
        },

        getWeight(incompleteKey){
        	let key;
        	let vm = this;
        	let weight;

        	if(this.pTable.length == 0){
        		return "";
        	}

        	this.configuration.forEach(function(item){
        		key = incompleteKey.split("|")[0] + "|" + item.code + "|" + incompleteKey.split("|")[1];
        		if(vm.pTable[key] != undefined){
        			if(vm.pTable[key].weight != ''){
        				weight = vm.pTable[key].weight;
        			}
        		}	       		
        	});

        	return weight;
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

            	if(! (project_id == '' && criteria == 'knowledge' && total_table == false)){
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
			
			let newkey;

				for(let key in this.reports){
					project_id = key;	

					//Recorrer por criterio
					vm.criteria.forEach(function(item){
						criteria = item.code;

						//Recorrer por mes
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

				//Añadir los knowledge
				for(let key in this.pTable){
					if(key.indexOf("|") == 0){
						newkey = key.slice(1);

					if(output[newkey] === undefined){
						output[newkey] = {
								mark: this.pTable[key].mark,
								comment: this.pTable[key].comment,
								weight: this.pTable[key].weight
							};
						}
					}						
				}

				return output;
        },

        /** ESTILOS **/

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
        	let criteria = key.split("|")[0];

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

						/** FUNCIONES DEL FORMULARIO **/
		validateComments(criterion){
			return criterion.mark != 2 || criterion.code =='knowledge' ? true : false;
		},

        validateFilter(criterion) {
        	if(criterion.code == 'knowledge'){
        		return (this.filter.year == '' || this.filter.month == '' || this.filter.employee == '') ? true : false;
        	}
			return (this.filter.year == '' || this.filter.month == '' || this.filter.employee == '' || this.filter.project == '') ? true : false;
		},

		/**
		 * (Des)habilita el botón de guardar
		 */
		validateSaveButton(criterion) {	
			return (criterion.mark === '' || ( criterion.code != 'knowledge' && criterion.mark != 2 && criterion.comment === '') || criterion.id != '') ? true : false;				
		},

		/**
		 * (Des)habilita el botón de borrar
		 */
		validateDeleteButton(criterion) {
			return (criterion.id === '') ? true : false;	
		},

		/**
		 * Inicializar campos del formulario
		 */
		setForm() {
			for (let i = this.configuration.length - 1; i >= 0; i--) {
				this.criteria.push({
					id: '',
					code: this.configuration[i]['code'],
					description : this.configuration[i]['description'],
					name: this.configuration[i]['name'],
					percentage: this.configuration[i]['percentage'],
					points: this.configuration[i]['points'],
					mark: '',
					comment: '',
					weight:'',
				});
			}
		},

		/**
         * Limpia el Formulario
         */
        clear(criterion) {
        	criterion.mark = '';
			criterion.comment = '';
		},

		/**
         * Limpia el Formulario completo
         */
        fullClear(criterion = "") {
        	if(criterion === "")
        	{
	     		this.criteria.forEach(function(item){
	        		item.id = '';
					item.mark = '';
					item.comment = '';
					item.weight = '';
				});
        	}
        	else{
        		criterion.id = '';
				criterion.mark = '';
				criterion.comment = '';
				criterion.weight = '';
        	}
		},

		/**
         * Limpia las tablas de rendimiento
         */
		perfomanceClear(){
			this.pTable = [];
			this.pTableTotal =  []; 
			this.tTable =  [];
			this.tTableTotal =  [];
		},

		/** OTRAS FUNCIONES **/

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
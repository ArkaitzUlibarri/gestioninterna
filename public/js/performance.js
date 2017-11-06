webpackJsonp([9],{

/***/ 175:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(176);


/***/ }),

/***/ 176:
/***/ (function(module, exports) {

/**
 * Registro los componentes necesarios.
 */

var app = new Vue({
	el: '#app',

	data: {

		//Evaluation					
		criteria: [],
		configuration: config,

		// User's data
		auth_projects: auth_p,

		//List
		monthList: months,
		employeeList: [],
		projectList: {},

		reports: [],

		//Filter options
		filter: {
			employee: '',
			project: '',
			year: moment().year(),
			month: moment().month() + 1 //''
		},

		//Tables
		pTable: [],
		pTableTotal: {},

		tTable: [],
		tTableTotal: []

	},

	mounted: function mounted() {
		this.setForm();
		this.fetchEmployees();
	},


	computed: {
		validateExportButton: function validateExportButton() {
			return this.filter.year == '' || this.filter.employee == '' ? true : false;
		},
		knowledgeform: function knowledgeform() {
			var out = [];
			this.criteria.forEach(function (item) {
				if (item.code == 'knowledge') {
					out.push(item);
				}
			});
			return out;
		},
		generalform: function generalform() {
			var out = [];
			this.criteria.forEach(function (item) {
				if (item.code != 'knowledge') {
					out.push(item);
				}
			});
			return out;
		}
	},

	methods: {

		/** ---------------------------BBDD --------------------------- **/
		/**
   * Guardar criterio del formulario
   */
		save: function save(criterion) {
			var vm = this;

			//Assign filter values before save
			criterion.year = vm.filter.year;
			criterion.month = vm.filter.month;
			criterion.user_id = vm.filter.employee;
			criterion.project_id = criterion.code == 'knowledge' ? '' : vm.filter.project;
			criterion.weight = criterion.code == 'knowledge' ? '100' : vm.getHours(criterion.month, criterion.project_id);
			//criterion.comment = criterion.mark == 2 ? '': criterion.comment; 

			if (this.validateDeleteButton(criterion)) {
				axios.post('api/performance-evaluation', criterion).then(function (response) {

					toastr.success("SAVED"); //Mensaje de Guardado
					criterion.id = response.data; //Asignar ids	  	 
					vm.fetchPerformance(); //Actualizar tablas
					vm.loadFormValues(); //Cargar valor
					//vm.clear(criterion);//Limpiar formulario
				}).catch(function (error) {
					vm.showErrors(error.response.data);
					vm.clear(criterion);
				});
			}
		},


		/**
   * Eliminar criterio del formulario
   */
		erase: function erase(criterion) {
			var vm = this;
			var ids = [];

			axios.delete('api/performance-evaluation/' + criterion.id).then(function (response) {

				toastr.success(response.data); //Mensaje de Guardado
				vm.fetchPerformance(); //Actualizar tablas 
				vm.fullClear(criterion); //Limpiar formulario
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},


		/**
         * Fetch projects table performance by user and year
         */
		fetchPerformance: function fetchPerformance() {
			var vm = this;
			var key = void 0;

			this.fullClear();
			this.perfomanceClear();

			axios.get('api/performance', {
				params: {
					year: vm.filter.year,
					employee: vm.filter.employee
				}
			}).then(function (response) {
				//console.log(response.data) 
				if (response.data.length != 0) {
					vm.pTable = response.data;
					vm.pTableTotal = vm.calculateTotalColumn(false);
					vm.tTable = vm.getTotalTable();
					vm.tTableTotal = vm.calculateTotalColumn(true);
					vm.loadFormValues();
				}
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},


		/**
         * Fetch employees who have reported in pm projects
         */
		fetchEmployees: function fetchEmployees() {
			var vm = this;

			this.projectList = {};
			this.employeeList = [];
			this.filter.project = '';
			this.filter.employee = '';
			this.fullClear();

			axios.get('api/employees', {
				params: {
					year: vm.filter.year
				}
			}).then(function (response) {
				vm.employeeList = response.data;
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},


		/**
         * Fetch reports to evaluate for a user
         */
		fetchReports: function fetchReports() {
			var vm = this;

			this.projectList = {};
			this.filter.project = '';
			this.fullClear();

			if (this.filter.employee === '') {
				return;
			}

			axios.get('api/employee_reports', {
				params: {
					year: vm.filter.year,
					employee: vm.filter.employee
				}
			}).then(function (response) {
				vm.reports = response.data;
				vm.filterProjects(); //Filtrar proyectos por mes de reporte
				vm.fetchPerformance(); //Cargar evaluaciones                                     
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},

		/** ---------------------------BBDD --------------------------- **/

		yearChange: function yearChange() {
			this.fullClear(); //Limpiar formularios
			this.perfomanceClear(); //Limpiar tablas
			this.reports = []; //Limpiar reportes
			this.filter.project = ''; //Limpiar proyecto
			this.filter.month = moment().month() + 1; //Inicializar mes

			this.fetchEmployees(); //Cargar empleados
		},
		employeeChange: function employeeChange() {
			if (this.filter.employee != '') {
				this.fetchReports(); //Cargar reportes
			} else {
				this.fullClear(); //Limpiar formularios
				this.perfomanceClear(); //Limpiar tablas
				this.reports = []; //Limpiar reportes
				this.filter.project = ''; //Limpiar proyecto
				this.filter.month = moment().month() + 1; //Inicializar mes
			}
		},
		projectChange: function projectChange() {
			if (this.filter.project != "") {
				this.fullClear();
				this.loadFormValues();
			}
		},
		loadFormValues: function loadFormValues() {
			var vm = this;
			vm.criteria.forEach(function (item) {
				key = item.code == 'knowledge' ? "|" + item.code + "|" + vm.filter.month : vm.filter.project + "|" + item.code + "|" + vm.filter.month;

				if (vm.pTable[key] != undefined) {
					item.mark = vm.pTable[key].mark;
					item.comment = vm.pTable[key].comment;
					item.weight = vm.pTable[key].weight;
					item.id = vm.pTable[key].id;
				}
			});
		},
		filterProjects: function filterProjects() {
			var _this = this;

			var vm = this;

			if (this.reports == null) {
				return null;
			}

			this.projectList = {};
			this.filter.project = '';
			this.fullClear();
			this.loadFormValues();

			var _loop = function _loop(project_id) {
				_this.reports[project_id].groups.forEach(function (item) {
					//Reportes/Proyectos del mes actual
					if (vm.filter.month == item.month) {
						//Filtrar estos reportes en los proyectos que son del auth user
						for (var _key in vm.auth_projects) {
							if (_key == project_id) {
								if (vm.projectList[_key] == undefined) {
									vm.projectList[_key] = {
										id: _key,
										name: vm.auth_projects[_key]
									};
								}
							}
						}
					}
				});
			};

			for (var project_id in this.reports) {
				_loop(project_id);
			}
		},
		getEmployee: function getEmployee() {
			var vm = this;
			var name = void 0;

			if (this.filter.employee != '') {
				this.employeeList.forEach(function (item) {
					if (item.id == vm.filter.employee) {
						name = item.full_name;
					}
				});

				return name.toUpperCase();
			}
		},
		getTotalHours: function getTotalHours() {
			var month = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "";

			var totalMonth = 0;
			var total = 0;

			for (var key in this.reports) {
				//Todos
				this.reports[key].groups.forEach(function (item) {
					if (item.month == month) {
						totalMonth += parseFloat(item.hours);
					}
					total += parseFloat(item.hours);
				});
			}

			if (month === "") {
				return total == 0 ? 0.00 : total.toFixed(2);
			}

			return totalMonth == 0 ? '-' : totalMonth.toFixed(2);
		},
		getHours: function getHours(month, project_id) {
			var option = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';

			var amount = 0;
			var total = 0;

			if (project_id === '') {
				return '';
			}

			for (var key in this.reports) {

				//Mismo proyecto
				if (project_id == this.reports[key].project_id) {
					this.reports[key].groups.forEach(function (item) {
						if (item.month == month) {
							amount += parseFloat(item.hours);
						}
					});
				}

				//Todos
				this.reports[key].groups.forEach(function (item) {
					if (item.month == month) {
						total += parseFloat(item.hours);
					}
				});
			}

			if (option === '') {
				return amount == 0 || total == 0 ? '-' : (amount / total * 100).toFixed(2);
			}

			return amount == 0 ? '-' : amount.toFixed(2);
		},
		getMarkComment: function getMarkComment(key, property, total) {
			var input = void 0;
			input = total ? this.tTable : this.pTable;

			if (input.length == 0 || input[key] == undefined) {
				return property == "mark" ? '-' : '';
			}

			if (property == "mark") {
				return input[key].mark.toFixed(2);
			} else if (property == "description") {
				return input[key].comment;
			}
		},
		getWeight: function getWeight(incompleteKey) {
			var key = void 0;
			var vm = this;
			var weight = void 0;

			if (this.pTable.length == 0) {
				return "";
			}

			this.configuration.forEach(function (item) {
				key = incompleteKey.split("|")[0] + "|" + item.code + "|" + incompleteKey.split("|")[1];
				if (vm.pTable[key] != undefined) {
					if (vm.pTable[key].weight != '') {
						weight = vm.pTable[key].weight;
					}
				}
			});

			return weight;
		},
		getTotalColumn: function getTotalColumn(key, total) {
			var input = void 0;
			input = total ? this.tTableTotal : this.pTableTotal;

			if (input == undefined) {
				return '-';
			} else if (input[key] == undefined) {
				return '-';
			} else if (input.length == 0) {
				return '-';
			}
			return input[key].total;
		},
		getTotalValue: function getTotalValue(project_id, total_table) {
			var vm = this;
			var result = 0;
			var error = "";
			var input = void 0;
			var key = void 0;
			var percentage = void 0;
			var totalColumn = void 0;
			var max_value = void 0;
			var partial = void 0;

			input = total_table ? this.tTableTotal : this.pTableTotal;

			if (input != undefined) {
				this.criteria.forEach(function (item) {
					key = total_table ? item.code : project_id + "|" + item.code;
					if (input[key] != undefined) {
						percentage = item.percentage;
						totalColumn = input[key].total;
						if (totalColumn < parseFloat(1)) {
							error = "error";
						}
						max_value = item.points.length - 1;
						partial = totalColumn / max_value * percentage;
						result = parseFloat(result) + parseFloat(partial);
					}
				});

				return error != "error" ? result.toFixed(2) : 0;
			}
		},
		calculateTotalColumn: function calculateTotalColumn(total_table) {

			var criteria = "";
			var project_id = "";
			var output = {};
			var input = void 0;
			var shortkey = void 0;
			var obj = {
				zeros: 0,
				counter: 0,
				sum: 0,
				name: '',
				project_id: 0,
				total: 0
			};

			input = total_table ? this.tTable : this.pTable;

			for (var key in input) {

				criteria = total_table ? key.split("|")[0] : key.split("|")[1];
				project_id = total_table ? 0 : key.split("|")[0];
				shortkey = total_table ? criteria : project_id + "|" + criteria;

				if (!(project_id == '' && criteria == 'knowledge' && total_table == false)) {
					if (output != undefined) {

						if (output[shortkey] === undefined) {
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

						if (input[key].mark == 0) {
							output[shortkey].zeros += 1;
						}

						output[shortkey].sum += 1;
						output[shortkey].counter += input[key].mark;
						output[shortkey].total = output[shortkey].name == 'knowledge' || !total_table ? (output[shortkey].counter / output[shortkey].sum).toFixed(2) : (output[shortkey].counter * Math.pow(2 / 3, output[shortkey].zeros) / output[shortkey].sum).toFixed(2);
					}
				}
			}

			return output;
		},
		getTotalTable: function getTotalTable() {

			var vm = this;
			var criteria = void 0;
			var project_id = void 0;
			var key = void 0;
			var shortkey = void 0;
			var output = {};
			var mark = void 0;
			var weight = void 0;

			var newkey = void 0;

			var _loop2 = function _loop2(_key3) {
				project_id = _key3;

				//Recorrer por criterio
				vm.criteria.forEach(function (item) {
					criteria = item.code;

					//Recorrer por mes
					for (var month = 1; month <= 12; month++) {
						_key3 = project_id + "|" + criteria + "|" + month;

						shortkey = criteria + "|" + month;
						if (vm.pTable[_key3] != undefined) {
							weight = vm.pTable[_key3].weight / 100;
							mark = vm.pTable[_key3].mark;
							if (output[shortkey] === undefined) {
								output[shortkey] = {
									mark: 0,
									comment: '',
									weight: 0
								};
							}
							output[shortkey].mark += parseFloat(mark) * parseFloat(weight);
						}
					}
				});
				_key2 = _key3;
			};

			for (var _key2 in this.reports) {
				_loop2(_key2);
			}

			//Añadir los knowledge
			for (var _key4 in this.pTable) {
				if (_key4.indexOf("|") == 0) {
					newkey = _key4.slice(1);

					if (output[newkey] === undefined) {
						output[newkey] = {
							mark: this.pTable[_key4].mark,
							comment: this.pTable[_key4].comment,
							weight: this.pTable[_key4].weight
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
		cellStyleProject: function cellStyleProject(key, total_column) {
			var input = total_column ? this.pTableTotal : this.pTable;
			var color = '';
			var criteria = key.split("|")[1];

			if (input[key] === undefined) {
				return '';
			}
			if (criteria != 'knowledge') {
				if (total_column === false) {
					color = input[key].mark == 3 ? 'success' : color;
					color = input[key].mark == 1 ? 'warning' : color;
					color = input[key].mark == 0 ? 'danger' : color;
				} else {
					color = input[key].total < parseFloat(1) ? 'danger' : color;
				}
			}
			return color;
		},


		/**
   * Marca el estilo de las celdas
   */
		cellStyleTotal: function cellStyleTotal(key, total_column) {
			var input = total_column ? this.tTableTotal : this.tTable;
			var color = '';
			var criteria = key.split("|")[0];

			if (input[key] === undefined) {
				return '';
			}
			if (criteria != 'knowledge') {
				if (total_column === false) {
					color = input[key].mark == 3 ? 'success' : color;
					color = input[key].mark == 1 ? 'warning' : color;
					color = input[key].mark == 0 ? 'danger' : color;
				} else {
					color = input[key].total < parseFloat(1) ? 'danger' : color;
				}
			}
			return color;
		},


		/**
  * Convertir a Mayúsculas primera letra
  */
		capitalizeFirstLetter: function capitalizeFirstLetter(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		},


		/** FUNCIONES DEL FORMULARIO **/
		validateComments: function validateComments(criterion) {
			return criterion.mark != 2 || criterion.code == 'knowledge' ? true : false;
		},
		validateFilter: function validateFilter(criterion) {

			if (criterion.id) {
				return true;
			}

			if (criterion.code == 'knowledge') {
				return this.filter.year == '' || this.filter.month == '' || this.filter.employee == '' ? true : false;
			}

			return this.filter.year == '' || this.filter.month == '' || this.filter.employee == '' || this.filter.project == '' ? true : false;
		},


		/**
   * (Des)habilita el botón de guardar
   */
		validateSaveButton: function validateSaveButton(criterion) {
			return criterion.mark === '' || criterion.code != 'knowledge' && criterion.mark != 2 && criterion.comment === '' || criterion.id != '' ? true : false;
		},


		/**
   * (Des)habilita el botón de borrar
   */
		validateDeleteButton: function validateDeleteButton(criterion) {
			return criterion.id === '' ? true : false;
		},


		/**
   * Inicializar campos del formulario
   */
		setForm: function setForm() {
			for (var i = this.configuration.length - 1; i >= 0; i--) {
				this.criteria.push({
					id: '',
					code: this.configuration[i]['code'],
					description: this.configuration[i]['description'],
					name: this.configuration[i]['name'],
					percentage: this.configuration[i]['percentage'],
					points: this.configuration[i]['points'],
					mark: '',
					comment: '',
					weight: ''
				});
			}
		},


		/**
         * Limpia el Formulario
         */
		clear: function clear(criterion) {
			criterion.mark = '';
			criterion.comment = '';
		},


		/**
         * Limpia el Formulario completo
         */
		fullClear: function fullClear() {
			var criterion = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "";

			if (criterion === "") {
				this.criteria.forEach(function (item) {
					item.id = '';
					item.mark = '';
					item.comment = '';
					item.weight = '';
				});
			} else {
				criterion.id = '';
				criterion.mark = '';
				criterion.comment = '';
				criterion.weight = '';
			}
		},


		/**
         * Limpia las tablas de rendimiento
         */
		perfomanceClear: function perfomanceClear() {
			this.pTable = [];
			this.pTableTotal = [];
			this.tTable = [];
			this.tTableTotal = [];
		},


		/** OTRAS FUNCIONES **/

		/**
  * Visualizo mensajes de error
  */
		showErrors: function showErrors(errors) {
			if (Array.isArray(errors)) {
				errors.forEach(function (error) {
					toastr.error(error);
				});
			} else {
				toastr.error(errors);
			}
		},
		makeUrl: function makeUrl(url) {
			var data = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

			if (data != null) {
				return url + '/' + data.join('/');
			}
			return url;
		}
	}

});

/***/ })

},[175]);
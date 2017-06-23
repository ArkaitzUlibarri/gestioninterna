webpackJsonp([4],{

/***/ 136:
/***/ (function(module, exports, __webpack_require__) {

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


/**
 * Registro los componentes necesarios.
 */
Vue.component('task-template', __webpack_require__(177));

var app = new Vue({

	el: '#report',

	data: {
		info: {
			origin: window.location.origin,
			serverPath: ""
		},

		contract: user_contract,
		expHours: 0,

		user: report_user,
		teleworking: teleworking,
		role: role,
		reportdate: reportdate,
		reportDayWeek: "",
		week: 0,

		categories: categories,
		groupProjects: groupProjects,
		absences: absences,

		projectList: [],
		groupList: [],
		categoryList: [],

		editIndex: -1,

		newTask: {
			id: -1,
			user_id: -1,
			created_at: '',
			activity: "",
			project_id: "",
			project: "",
			group_id: "",
			group: "",
			category_id: "",
			category: "",
			absence_id: "",
			absence: "",
			training_type: "",
			time_slots: 0,
			time: 0,
			job_type: "",
			comments: "",
			pm_validation: 0,
			admin_validation: 0
		},

		tasks: []
	},

	computed: {
		validatedTasks: function validatedTasks() {

			var output = true;

			if (this.tasks.length > 0) {
				this.tasks.forEach(function (item) {
					if (item.pm_validation == 0) {
						output = false;
						return output;
					}
				});
				if (output) {
					return true;
				}
			}
			return false;
		},
		totalTime: function totalTime() {
			var total = 0;

			if (this.tasks.length > 0) {
				this.tasks.forEach(function (item) {
					total += parseInt(item.time_slots) * 0.25;
				});
			}

			return total;
		},
		formTaskFilled: function formTaskFilled() {
			if (this.newTask.pm_validation == 0 && this.newTask.admin_validation == 0) {
				if (this.newTask.activity != "" && this.newTask.time != 0) {
					if (this.newTask.activity == 'project' && this.newTask.project != "" && this.newTask.group != "" && this.newTask.category != "" && this.newTask.job_type != "") {
						return true;
					}
					if (this.newTask.activity == 'absence' && this.newTask.absence != "") {
						return true;
					}
					if (this.newTask.activity == 'training' && this.newTask.training_type != "" && this.newTask.job_type != "") {
						return true;
					}
				}
			}

			return false;
		}
	},

	created: function created() {
		var _this = this;

		Event.$on('DeleteTask', function (index, task) {
			_this.delete(index);
			_this.tasks.splice(index, 1);
		});

		Event.$on('EditTask', function (index, task) {
			_this.newTask = {
				id: task.id,
				user_id: _this.user.id,
				created_at: _this.reportdate,
				activity: task.activity,
				project_id: task.project_id,
				project: task.project,
				group_id: task.group_id,
				group: task.group,
				category_id: task.category_id,
				category: task.category,
				absence_id: task.absence_id,
				absence: task.absence,
				training_type: task.training_type,
				time_slots: task.time_slots,
				time: parseInt(task.time_slots) * 0.25,
				job_type: task.job_type,
				comments: task.comments,
				pm_validation: task.pm_validation,
				admin_validation: task.admin_validation
			};

			_this.idTraduction();
			_this.groupsRefresh();

			_this.editIndex = index;
		});
	},
	mounted: function mounted() {
		this.info.serverPath = this.getPath();
		this.fetchData();
		this.project();
		this.categoriesLoad();
		this.setMaxDate();
	},


	methods: {
		getPath: function getPath() {
			var pathArray = window.location.pathname.split("/");
			var path = "";
			var position = 0;

			for (var i = pathArray.length - 1; i >= 0; i--) {
				if (pathArray[i] == "public") {
					position = i;
					break;
				}
			}

			if (position != 0) {
				for (var j = 0; j <= position; j++) {
					path = path + pathArray[j] + "/";
				}
				return path;
			}

			return "";
		},
		expectedHours: function expectedHours() {
			if (this.contract != null) {
				if (this.contract.week_hours == 40) {
					if (this.reportDayWeek == 'friday') {
						this.expHours = 7;
					} else if (this.reportDayWeek != 'saturday' && this.reportDayWeek != 'sunday') {
						this.expHours = 8.25;
					}
					this.expHours = 0;
				}
				this.expHours = parseInt(this.contract.week_hours / 5);
			}
		},
		dateValidation: function dateValidation() {
			var today = this.getDate();
			var datefield = document.getElementById("datefield").value;

			if (datefield.length == 10 && moment(datefield, "YYYY-MM-DD").isValid() && datefield <= today) {
				this.fetchData();
			} else {
				//console.log("Fecha mayor que hoy");
				toastr.error("Fecha mayor que hoy o con formato erróneo");
				this.reportdate = today;
				this.fetchData();
			}
		},
		getDate: function getDate() {
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1; //January is 0!
			var yyyy = today.getFullYear();

			if (dd < 10) {
				dd = '0' + dd;
			}
			if (mm < 10) {
				mm = '0' + mm;
			}
			today = yyyy + '-' + mm + '-' + dd;

			return today;
		},
		setMaxDate: function setMaxDate() {
			var today = this.getDate();

			if (document.getElementById('datefield') !== null) {
				document.getElementById("datefield").setAttribute("max", today);
			}
		},
		getWeek: function getWeek(dowOffset, stringDate) {

			var d = new Date(stringDate);

			dowOffset = typeof dowOffset == 'number' ? dowOffset : 0; //default dowOffset to zero
			var newYear = new Date(d.getFullYear(), 0, 1);
			var day = newYear.getDay() - dowOffset; //the day of week the year begins on
			day = day >= 0 ? day : day + 7;
			var daynum = Math.floor((d.getTime() - newYear.getTime() - (d.getTimezoneOffset() - newYear.getTimezoneOffset()) * 60000) / 86400000) + 1;
			var weeknum;
			//if the year starts before the middle of a week
			if (day < 4) {
				weeknum = Math.floor((daynum + day - 1) / 7) + 1;
				if (weeknum > 52) {
					nYear = new Date(d.getFullYear() + 1, 0, 1);
					nday = nYear.getDay() - dowOffset;
					nday = nday >= 0 ? nday : nday + 7;
					/*if the next year starts before the middle of
       the week, it is week #1 of that year*/
					weeknum = nday < 4 ? 1 : 53;
				}
			} else {
				weeknum = Math.floor((daynum + day - 1) / 7);
			}
			return weeknum;
		},
		getDayWeek: function getDayWeek(stringDate) {
			var d = new Date(stringDate);

			var weekday = new Array(7);
			weekday[0] = "sunday";
			weekday[1] = "monday";
			weekday[2] = "tuesday";
			weekday[3] = "wednesday";
			weekday[4] = "thursday";
			weekday[5] = "friday";
			weekday[6] = "saturday";

			return weekday[d.getDay()];
		},
		addTask: function addTask() {
			this.newTask.time_slots = this.newTask.time * 4;
			this.nameTraduction();
			this.save();
		},
		editTask: function editTask() {
			this.newTask.time_slots = this.newTask.time * 4;
			this.nameTraduction();
			this.save();
		},
		initializeTask: function initializeTask() {
			this.reportDayWeek = this.getDayWeek(this.reportdate);
			this.week = this.getWeek(1, this.reportdate);
			this.expectedHours();

			this.newTask = {
				id: -1,
				user_id: this.user.id,
				created_at: this.reportdate,
				activity: "",
				project_id: "",
				project: "",
				group_id: "",
				group: "",
				category_id: "",
				category: "",
				absence_id: "",
				absence: "",
				training_type: "",
				time_slots: 0,
				time: 0,
				job_type: "",
				comments: "",
				pm_validation: 0,
				admin_validation: 0
			};

			this.editIndex = -1;
		},
		refreshForm: function refreshForm() {
			this.newTask = {
				id: this.newTask.id,
				user_id: this.user.id,
				created_at: this.reportdate,
				activity: this.newTask.activity,
				project_id: "",
				project: "",
				group_id: "",
				group: "",
				category_id: "",
				category: "",
				absence_id: "",
				absence: "",
				training_type: "",
				time_slots: 0,
				time: 0,
				job_type: "",
				comments: "",
				pm_validation: this.newTask.pm_validation,
				admin_validation: this.newTask.admin_validation
			};

			if (this.newTask.activity == 'training') {
				this.newTask.job_type = 'on site work';
			}
		},
		project: function project() {
			var setList = new Set();

			this.groupProjects.forEach(function (item) {
				setList.add(item.project);
			});

			this.projectList = [].concat(_toConsumableArray(setList));
		},
		groupsRefresh: function groupsRefresh() {
			var vm = this;
			var setList = new Set();

			vm.groupProjects.forEach(function (item) {
				if (vm.newTask.project == item.project) {
					setList.add(item.group);
				}
			});

			this.groupList = [].concat(_toConsumableArray(setList));
		},
		categoriesLoad: function categoriesLoad() {

			var vm = this;
			var setList = new Set();

			vm.categories.forEach(function (item) {
				if (vm.user.id == item.user_id) {
					setList.add(item.description);
				}
			});

			this.categoryList = [].concat(_toConsumableArray(setList));
		},
		nameTraduction: function nameTraduction() {

			this.newTask.project_id = "";
			this.newTask.group_id = "";
			this.newTask.category_id = "";
			this.newTask.absence_id = "";

			//Ausencia
			if (this.newTask.activity == 'absence') {
				for (var i = this.absences.length - 1; i >= 0; i--) {
					if (this.absences[i].name == this.newTask.absence) {
						this.newTask.absence_id = this.absences[i].id;
					}
				}
			}

			//GrupoProyecto
			if (this.newTask.activity == 'project') {
				for (var key = this.categories.length - 1; key >= 0; key--) {
					if (this.categories[key].description == this.newTask.category) {
						this.newTask.category_id = this.categories[key].category_id;
					}
				}
				for (var key = this.groupProjects.length - 1; key >= 0; key--) {
					if (this.groupProjects[key].group == this.newTask.group && this.groupProjects[key].project == this.newTask.project) {
						this.newTask.group_id = this.groupProjects[key].group_id;
						this.newTask.project_id = this.groupProjects[key].project_id;
					}
				}
			}
		},
		idTraduction: function idTraduction() {

			this.newTask.project = "";
			this.newTask.group = "";
			this.newTask.category = "";
			this.newTask.absence = "";

			//Ausencia
			if (this.newTask.activity == 'absence') {
				for (var i = this.absences.length - 1; i >= 0; i--) {
					if (this.newTask.absence_id == this.absences[i].id) {
						this.newTask.absence = this.absences[i].name;
						break;
					}
				}
			}

			//GrupoProyecto
			if (this.newTask.activity == 'project') {
				for (var key = this.categories.length - 1; key >= 0; key--) {
					if (this.newTask.category_id == this.categories[key].category_id) {
						this.newTask.category = this.categories[key].description;
						break;
					}
				}
				for (var _key = this.groupProjects.length - 1; _key >= 0; _key--) {
					if (this.newTask.group_id == this.groupProjects[_key].group_id) {
						this.newTask.group = this.groupProjects[_key].group;
						this.newTask.project = this.groupProjects[_key].project;
						break;
					}
				}
			}
		},
		fetchData: function fetchData() {
			var vm = this;
			vm.tasks = [];

			vm.initializeTask();

			axios.get(vm.info.origin + vm.info.serverPath + '/api/reports', {
				params: {
					user_id: vm.user.id,
					created_at: vm.reportdate
				}
			}).then(function (response) {
				vm.tasks = response.data;
				//console.log(response.data);
			}).catch(function (error) {
				//console.log(error);

				if (Array.isArray(error.response.data)) {
					error.response.data.forEach(function (error) {
						toastr.error(error);
					});
				} else {
					toastr.error(error.response.data);
				}
			});
		},
		save: function save() {
			var vm = this;

			if (vm.newTask.id != -1) {
				axios.patch(vm.info.origin + vm.info.serverPath + '/api/reports/' + vm.newTask.id, vm.newTask).then(function (response) {
					console.log(response.data);
					toastr.success(response.data);
					//---------------------------------------
					var properties = Object.keys(vm.newTask);

					for (var i = properties.length - 1; i >= 0; i--) {
						vm.tasks[vm.editIndex][properties[i]] = vm.newTask[properties[i]];
					}
					vm.initializeTask();
					//---------------------------------------
				}).catch(function (error) {
					console.log(error);
					//****************************************
					if (Array.isArray(error.response.data)) {
						error.response.data.forEach(function (error) {
							toastr.error(error);
						});
					} else {
						toastr.error(error.response.data);
					}
					//****************************************
				});
				return;
			} else {

				axios.post(vm.info.origin + vm.info.serverPath + '/api/reports', vm.newTask).then(function (response) {
					console.log(response.data);
					toastr.success("Saved");
					//---------------------------------------
					vm.newTask.id = response.data;
					vm.tasks.push(vm.newTask);
					vm.initializeTask();
					//---------------------------------------	
				}).catch(function (error) {
					console.log(error);
					//****************************************
					if (Array.isArray(error.response.data)) {
						error.response.data.forEach(function (error) {
							toastr.error(error);
						});
					} else {
						toastr.error(error.response.data);
					}
					//****************************************
				});
				return;
			}
		},
		delete: function _delete(index) {
			var vm = this;

			axios.delete(vm.info.origin + vm.info.serverPath + '/api/reports/' + vm.tasks[index].id).then(function (response) {
				console.log(response.data);
				toastr.success(response.data);
			}).catch(function (error) {
				console.log(error);
				//****************************************
				if (Array.isArray(error.response.data)) {
					error.response.data.forEach(function (error) {
						toastr.error(error);
					});
				} else {
					toastr.error(error.response.data);
				}
				//****************************************
			});
		},
		copyTasks: function copyTasks() {
			var vm = this;

			axios.get(vm.info.origin + vm.info.serverPath + '/api/lastreport', {
				params: {
					user_id: vm.user.id,
					created_at: vm.reportdate
				}
			}).then(function (response) {
				console.log(response.data);
				toastr.success(response.data);
				vm.fetchData();
			}).catch(function (error) {
				console.log(error);
				//****************************************
				if (Array.isArray(error.response.data)) {
					error.response.data.forEach(function (error) {
						toastr.error(error);
					});
				} else {
					toastr.error(error.response.data);
				}
				//****************************************
			});
		}
	}
});

/***/ }),

/***/ 162:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    template: '#task-template',

    props: ['task', 'index', 'prop'],

    computed: {
        time: function time() {
            return parseInt(this.task.time_slots) * 0.25;
        }
    },

    methods: {
        deleteTask: function deleteTask() {
            Event.$emit('DeleteTask', this.index, this.task);
        },
        editTask: function editTask() {
            Event.$emit('EditTask', this.index, this.task);
        }
    }

});

/***/ }),

/***/ 170:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(2)();
exports.push([module.i, "\n.panel-right-corner {\n    position: absolute;\n    right: 2em;\n    top:1em;\n}\n.action {\n    cursor: pointer;\n    //display: block;\n    //margin: auto ;\n}\n.task-panel {\n    position:relative;\n    border-bottom: 1px solid #ccc;\n    padding:.4em\n}\n.validated-task {\n    background-color: #b0f2b8;\n}\n.selected-task {\n    //background-color: lightblue;\n    border-bottom: 1px dashed red;\n    border-top: 1px dashed red;\n}\n.validated-color {\n    color: #21d421;\n}\n\n", ""]);

/***/ }),

/***/ 177:
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(190)

var Component = __webpack_require__(3)(
  /* script */
  __webpack_require__(162),
  /* template */
  __webpack_require__(184),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/home/vagrant/Code/gestioninterna/resources/assets/js/components/Task.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Task.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-cec93342", Component.options)
  } else {
    hotAPI.reload("data-v-cec93342", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 184:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "task-panel",
    class: {
      'validated-task': _vm.task.admin_validation, 'selected-task': _vm.prop
    }
  }, [(!_vm.task.admin_validation) ? _c('div', {
    staticClass: "panel-right-corner"
  }, [(_vm.task.pm_validation) ? _c('div', {
    staticClass: "validated-color"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-ok",
    attrs: {
      "aria-hidden": "true"
    }
  })]) : _vm._e(), _vm._v(" "), (!_vm.task.pm_validation) ? _c('div', {
    staticClass: "task-delete action",
    on: {
      "click": _vm.deleteTask
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-trash",
    attrs: {
      "aria-hidden": "true"
    }
  })]) : _vm._e()]) : _vm._e(), _vm._v(" "), _c('div', {
    staticClass: "action",
    on: {
      "click": _vm.editTask
    }
  }, [(_vm.task.activity == 'project') ? _c('h5', [_c('span', [_c('b', [_vm._v(_vm._s(_vm.time) + " " + _vm._s(_vm.task.project.toUpperCase() + ' | ' + _vm.task.group.toUpperCase() + ' | ' + _vm.task.category.toUpperCase()))]), _vm._v(" | ")]), _vm._v(" "), (_vm.task.comments) ? _c('span', [_vm._v(_vm._s(_vm.task.comments.substring(0, 90))), (_vm.task.comments.length > 90) ? _c('span', [_vm._v("...")]) : _vm._e()]) : _vm._e()]) : _vm._e(), _vm._v(" "), (_vm.task.activity == 'absence') ? _c('h5', [_c('span', [_c('b', [_vm._v(_vm._s(_vm.time) + " " + _vm._s('ABSENCE | ' + _vm.task.absence.toUpperCase()))]), _vm._v(" | ")]), _vm._v(" "), (_vm.task.comments) ? _c('span', [_vm._v(_vm._s(_vm.task.comments.substring(0, 90))), (_vm.task.comments.length > 90) ? _c('span', [_vm._v("...")]) : _vm._e()]) : _vm._e()]) : _vm._e(), _vm._v(" "), (_vm.task.activity == 'training') ? _c('h5', [_c('span', [_c('b', [_vm._v(_vm._s(_vm.time) + " " + _vm._s('TRAINING | ' + _vm.task.training_type.toUpperCase()))]), _vm._v(" | ")]), _vm._v(" "), (_vm.task.comments) ? _c('span', [_vm._v(_vm._s(_vm.task.comments.substring(0, 90))), (_vm.task.comments.length > 90) ? _c('span', [_vm._v("...")]) : _vm._e()]) : _vm._e()]) : _vm._e()])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-cec93342", module.exports)
  }
}

/***/ }),

/***/ 190:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(170);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(4)("5d8786ba", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-cec93342!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Task.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-cec93342!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Task.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 199:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(136);


/***/ }),

/***/ 2:
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function() {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		var result = [];
		for(var i = 0; i < this.length; i++) {
			var item = this[i];
			if(item[2]) {
				result.push("@media " + item[2] + "{" + item[1] + "}");
			} else {
				result.push(item[1]);
			}
		}
		return result.join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};


/***/ }),

/***/ 3:
/***/ (function(module, exports) {

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  scopeId,
  cssModules
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  // inject cssModules
  if (cssModules) {
    var computed = options.computed || (options.computed = {})
    Object.keys(cssModules).forEach(function (key) {
      var module = cssModules[key]
      computed[key] = function () { return module }
    })
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 4:
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(5)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction) {
  isProduction = _isProduction

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[data-vue-ssr-id~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),

/***/ 5:
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ })

},[199]);
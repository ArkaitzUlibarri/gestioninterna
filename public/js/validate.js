webpackJsonp([4],{

/***/ 18:
/***/ (function(module, exports) {


/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


var app = new Vue({

	el: '#app',

	data: {
		user: "",
		role: "",
		reportdate: "",

		editIndex: -1,

		newTask: {
			id: -1,
			user_id: -1,
			created_at: '',
			activity: "",
			project_id: "",
			group_id: "",
			category_id: "",
			absence_id: "",
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

	methods: {
		fetchData: function fetchData() {},
		save: function save() {
			var vm = this;

			axios.patch('/api/reports/' + vm.newTask.id, vm.newTask).then(function (response) {
				console.log(response.data);
				//---------------------------------------
				var properties = Object.keys(vm.newTask);

				for (var i = properties.length - 1; i >= 0; i--) {
					vm.tasks[vm.editIndex][properties[i]] = vm.newTask[properties[i]];
				}
				vm.initializeTask();
				//---------------------------------------
			}).catch(function (error) {
				console.log(error);
			});
			return;
		}
	}
});

/***/ }),

/***/ 52:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(18);


/***/ })

},[52]);
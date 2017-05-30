webpackJsonp([4],{

/***/ 134:
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
		role: '',
		admin: 0,
		pm: 0,
		user_id: -1,

		reports: [],
		tasks: [],

		users: []
	},

	mounted: function mounted() {
		this.reports = workingreport;
		this.role = auth_user.role;
		this.admin = this.role == 'admin' ? 1 : 0;
		this.pm = pm;
		this.user_id = auth_user.id;
	},


	methods: {
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
			weekday[0] = "Sunday";
			weekday[1] = "Monday";
			weekday[2] = "Tuesday";
			weekday[3] = "Wednesday";
			weekday[4] = "Thursday";
			weekday[5] = "Friday";
			weekday[6] = "Saturday";

			return weekday[d.getDay()];
		},
		validate: function validate(flag) {
			var vm = this;
			var output = true;

			if (confirm("¿Estás seguro de que quieres validar el día?")) {

				console.log("Admin: " + vm.admin);
				console.log("PM: " + vm.pm);
				console.log("Flag: " + flag);

				vm.tasks.forEach(function (item) {

					console.log("pm_validation: " + item.pm_validation);
					console.log("admin_validation: " + item.admin_validation);

					if (vm.admin) {
						if (flag) {
							if (item.pm_validation == 1 && item.admin_validation == 0) {
								item.admin_validation = 1;
								console.log("A-Admin:" + item.admin_validation);
								return output;
							}
						} else {
							if (item.pm_validation == 1 && item.admin_validation == 1) {
								item.admin_validation = 0;
								console.log("B-Admin:" + item.admin_validation);
								return output;
							}
						}
						output = false;
						return output;
					} else if (vm.pm) {
						if (flag) {
							if (item.pm_validation == 0 && item.admin_validation == 0) {
								item.pm_validation = 1;
								console.log("C-PM:" + item.pm_validation);
								return output;
							}
						} else {
							if (item.pm_validation == 1 && item.admin_validation == 0) {
								item.pm_validation = 0;
								console.log("D-PM:" + item.pm_validation);
								return output;
							}
						}
						output = false;
						return output;
					} else {
						output = false;
						return output;
					}
				});
				console.log("Output: " + output);
				if (output) {
					return true;
				}
			}

			return false;
		},
		fetchData: function fetchData(user_id, created_at, index, flag) {
			var vm = this;
			vm.tasks = [];

			axios.get('/api/reports', {
				params: {
					user_id: user_id,
					created_at: created_at
				}
			}).then(function (response) {
				vm.tasks = response.data;
				console.log(response.data);
				if (vm.validate(flag)) {
					vm.save();
					vm.updateReport(index, flag);
				}
			}).catch(function (error) {
				console.log(error);
			});
		},
		save: function save() {
			var vm = this;

			vm.tasks.forEach(function (item) {

				axios.patch('/api/reports/' + item.id, item).then(function (response) {
					console.log(response.data);
				}).catch(function (error) {
					console.log(error);
				});
			});
		},
		updateReport: function updateReport(index, flag) {
			if (flag) {
				if (this.admin) {
					this.reports[index].horas_validadas_admin = "0.00";
				} else if (this.pm) {
					this.reports[index].horas_validadas_pm = "0.00";
				}
			} else {
				if (this.admin) {
					this.reports[index].horas_validadas_admin = this.reports[index].horas_reportadas;
				} else if (this.pm) {
					this.reports[index].horas_validadas_pm = this.reports[index].horas_reportadas;
				}
			}
		}
	}
});

/***/ }),

/***/ 170:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(134);


/***/ })

},[170]);
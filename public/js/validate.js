webpackJsonp([8],{

/***/ 137:
/***/ (function(module, exports) {


var app = new Vue({
	el: '#app',

	data: {
		url: url,

		role: '',
		admin: 0,
		pm: 0,
		user_id: -1,

		reports: [],
		tasks: [],

		users: [],
		user_report: -1
	},

	mounted: function mounted() {
		this.reports = workingreport;
		this.role = auth_user.role;
		this.admin = this.role == 'admin' ? 1 : 0;
		this.pm = pm;
		this.user_id = auth_user.id;
		this.user_report = auth_user.id;
	},


	methods: {
		makeUrl: function makeUrl(url) {
			var data = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

			if (data != null) {
				return url + '/' + data.join('/');
			}
			return url;
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

			vm.tasks.forEach(function (item) {

				if (vm.admin) {
					if (flag) {
						if (item.pm_validation == 1 && item.admin_validation == 0) {
							item.admin_validation = 1;
							return output;
						}
					} else {
						if (item.pm_validation == 1 && item.admin_validation == 1) {
							item.admin_validation = 0;
							return output;
						}
					}
					output = false;
					return output;
				} else if (vm.pm) {
					if (flag) {
						if (item.pm_validation == 0 && item.admin_validation == 0) {
							item.pm_validation = 1;
							return output;
						}
					} else {
						if (item.pm_validation == 1 && item.admin_validation == 0) {
							item.pm_validation = 0;
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

			if (output) {
				return true;
			}

			return false;
		},
		fetchData: function fetchData(user_id, created_at, index, flag) {
			var vm = this;
			vm.tasks = [];

			axios.get(vm.url + '/api/reports', {
				params: {
					user_id: user_id,
					created_at: created_at
				}
			}).then(function (response) {
				vm.tasks = response.data;
				if (vm.validate(flag)) {
					vm.save();
					vm.updateReport(index, flag);
				}
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},
		save: function save() {
			var vm = this;
			vm.tasks.forEach(function (item) {
				axios.patch(vm.url + '/api/reports/' + item.id, item).then(function (response) {
					toastr.success(response.data);
				}).catch(function (error) {
					vm.showErrors(error.response.data);
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
		},


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
		}
	}
});

/***/ }),

/***/ 196:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(137);


/***/ })

},[196]);
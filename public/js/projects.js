webpackJsonp([2],{

/***/ 12:
/***/ (function(module, exports) {


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');

var app = new Vue({

	el: '#project',

	data: {
		project_id: id,
		groups: [],
		newGroup: {
			group: '-',
			enabled: 'false'
		}
	},

	mounted: function mounted() {
		console.log("A");
		this.fetchData();
	},


	methods: {
		fetchData: function fetchData() {
			console.log("B");
			var vm = this;
			vm.groups = [];

			axios.get('/api/groups', {
				params: {
					project_id: vm.project_id
				}
			}).then(function (response) {
				console.log("C");
				vm.groups = response.data;
				console.log(response.data);
			}).catch(function (error) {
				console.log("D");
				console.log(error);
			});
		}
	}
});

/***/ }),

/***/ 14:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 44:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(12);
module.exports = __webpack_require__(14);


/***/ })

},[44]);
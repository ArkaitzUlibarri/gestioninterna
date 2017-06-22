webpackJsonp([8],{

/***/ 139:
/***/ (function(module, exports) {


/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


var app = new Vue({

    el: '#app',

    mounted: function mounted() {
        this.fetchData();
    },


    data: {
        filter: { name: '', validated: 'false' },
        reports: []
    },

    computed: {
        /**
         * Filter database request by project name.
         */
        filtered_reports: function filtered_reports() {
            var filtered = [];
            for (var key in this.reports) {
                for (var i in this.reports[key].items) {
                    if (this.reports[key].items[i].name.toLowerCase().includes(this.filter.name.toLowerCase())) {
                        filtered.push(this.reports[key]);
                        break;
                    }
                }
            }
            return filtered;
        }
    },

    methods: {
        /**
         * Validate a user week actions
         */
        validate: function validate(user, year, week) {
            var vm = this;

            axios.patch('api/validate', {
                user_id: user,
                year: year,
                week: week,
                value: vm.filter.validated
            }).then(function (response) {
                vm.fetchData();
            }).catch(function (error) {
                console.log(error);
            });
        },


        /**
         * Fetch reports
         */
        fetchData: function fetchData() {
            var vm = this;

            vm.reports = [];

            axios.get('api/validate', {
                params: {
                    validated: vm.filter.validated
                }
            }).then(function (response) {
                vm.reports = response.data;
                //console.log(response);
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
});

/***/ }),

/***/ 202:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(139);


/***/ })

},[202]);
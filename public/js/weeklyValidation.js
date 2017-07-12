webpackJsonp([7],{

/***/ 140:
/***/ (function(module, exports) {


var app = new Vue({
    el: '#app',

    data: {
        filter: { name: '', validated: 'false' },
        reports: []
    },

    mounted: function mounted() {
        this.fetchData();
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

/***/ 203:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(140);


/***/ })

},[203]);
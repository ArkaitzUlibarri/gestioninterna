
/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


const app = new Vue({

	el: '#app',

    mounted() {
        this.fetchData();
    },

    data: {
        filter: { name: '', validated: 'false' },
        reports: [],
    },

    computed: {
        /**
         * Filter database request by project name.
         */
        filtered_reports() {
            let filtered = [];
            for (let key in this.reports) {
                for(let i in this.reports[key].items) {
                    if(this.reports[key].items[i].name.toLowerCase().includes(this.filter.name.toLowerCase())) {
                        filtered.push(this.reports[key]);
                        break;
                    }
                }               
            }
            return filtered;
        }
    },

    methods:{
        /**
         * Validate a user week actions
         */
        validate(user, year, week) {
            let vm  = this;

            axios.patch('api/validate', {
                    user_id: user,
                    year: year,
                    week: week,
                    value: vm.filter.validated
                })
                .then(function (response) {
                    vm.fetchData();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        /**
         * Fetch reports
         */
        fetchData() {
            let vm  = this;

            vm.reports = [];

            axios.get('api/validate', {
                    params: {
                        validated: vm.filter.validated
                    }
                })
                .then(function (response) {
                    vm.reports = response.data;
                    //console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }

    }
});

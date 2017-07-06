@extends('layouts.app')

@section('content')

<div class="panel panel-primary">

    <div class="panel-heading">
        @include('validation.filter')
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        <table v-show="filtered_reports!=null">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th v-for="(day, index) in days">
                        @{{ weekdaysShort[index] }}, @{{ day.substr(5, 5) }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users">
                    <td>
                        <strong>@{{ user['name'] }}</strong>
                        <p>Total Week: @{{ user['total'] }}</p>
                        <!--<p><button class="btn btn-default btn-sm">Validate Week</button></p>-->
                    </td>

                    <td v-for="day in days">

                        <div v-if="hasCard(user['id'], day)"
                             v-on:click="validate(user['id']+'|'+day)"
                             class="card"
                             v-bind:class="getCardColor(filtered_reports[user['id']+'|'+day].admin_validation, filtered_reports[user['id']+'|'+day].pm_validation)">

                            <div v-for="item in filtered_reports[user['id']+'|'+day].items">
                                <p>- @{{ item.name }}, @{{ item.time_slot }}h</p>
                            </div>

                            <div style="margin-top: 10px;padding-top: 10px;border-top: 1px solid #9e9e9e;">
                                <strong>Total Hours:</strong> @{{ filtered_reports[user['id']+'|'+day].total }}
                                <span><div class="pull-right">@{{ filtered_reports[user['id']+'|'+day].manager }}</div></span>
                            </div>

                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div  style="text-align: center; margin-top: 50px; margin-bottom: 50px" v-show="filtered_reports==null">
            No data available...
        </div> 

    </div>
</div>

@endsection


@push('script-bottom')

<style>

.container {
    width: 100%;
}

table {
    border-collapse: collapse;
    width: 100%;
}

thead {
    border-bottom: 1px solid #ccc;
}

th {
    text-align: center;
    height: 3em;
}

td {
    width: 5em;
    height: 7em;
    padding: .2em;
}

table .card {
    font-size: 12px;
    width: 100%;
    height: 100%;
    padding: 1em;
    border: 1px solid #dddddd;
    cursor: pointer;
}

.card p {
    margin-bottom: 0px;
}

.validated {
    background: #E1F5FE;
}

.full-validated {
    background: #c5e1a5;
}

/*
.card p {
    margin-bottom: 2px;
}
*/
</style>


<script>
var app = new Vue({
    el: '#app',

    data: {
        // User's data
        //user_id: {!! Auth()->user()->id !!},
        role: '{!! Auth()->user()->primaryRole() !!}',

        // Filter options
        filter: { activity:'', user: '', year: moment().year(), week: moment().week() },

        // Data for the table of cards
        users: [],
        days: [],
        reports: null,
        weekdaysShort : ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']
    },

    mounted() {
        this.fetchData();
    },

    computed:{
        /**
         * Filter database request by project name.
         */
        filtered_reports() {
            let filtered = [];

            if(this.reports == null){
                return null;
            }

            for (let key in this.reports) {
                for(let i in this.reports[key].items) {
                    if(this.reports[key].items[i].name.toLowerCase().includes(this.filter.activity.toLowerCase())) {
                        //filtered.push(this.reports[key]);
                        filtered[key] = this.reports[key];
                        break;
                    }
                }               
            }
            return filtered;
        }
    },

    methods: {
        /**
         * Validate a single day tasks.
         */
        validate(key) {
            var vm = this;

            if (vm.role != 'admin' && vm.role != 'manager') {
                toastr.error("User does not have required permissions.");
                return;
            }

            if (vm.role == 'manager' && vm.reports[key].admin_validation == 1) {
                toastr.error("Cannot update the task's status.");
                return;
            }

            axios.patch('api/validate', {
                    user_id: this.reports[key].user_id,
                    day: this.reports[key].created_at,
                    admin_validation: vm.reports[key].admin_validation,
                    pm_validation: vm.reports[key].pm_validation,
                }).then(function (response) {
                    if ('admin_validation' in response.data)
                        vm.reports[key].admin_validation = response.data.admin_validation

                    if ('pm_validation' in response.data)
                        vm.reports[key].pm_validation = response.data.pm_validation

                    if ('manager_id' in response.data)
                        vm.reports[key].manager = response.data.manager_id

                }).catch(function (error) {
                    vm.showErrors(error.response.data)
                });
        },

        /**
         * Fetch reports and initialize users and days arrays.
         */
        fetchData () {
            var vm = this;
            vm.reports = null;
            vm.users = [];
            vm.days = [];

            axios.get('api/validate', {
                    params: {
                        name: vm.filter.user,
                        year: vm.filter.year,
                        week: vm.filter.week
                    }
                })
                .then(function (response) {
                    if(Object.keys(response.data).length > 0) {
                        vm.reports = response.data;
                        vm.getListOfUsers(response.data);
                        vm.days = vm.getWeekDays(vm.filter.week, vm.filter.year);
                    }
                })
                .catch(function (error) {
                   vm.showErrors(error.response.data)
                });
        },

        /**
         * Get the card's color if it is validated.
         */
        getCardColor(admin, pm) {
            if (admin && pm) {
                return 'full-validated';
            }

            if (pm) {
                return 'validated';
            }
        },

        /**
         * Fill the user id and names arrays.
         */
        getListOfUsers(data) {
            var temp = {};

            Object.values(data).forEach( (item) => {
                if (! (item['user_id'] in temp)) {
                    temp[item['user_id']] = {
                        'id': item['user_id'],
                        'name': item['user_name'],
                        'total' : item['total']
                    };
                }
                else {
                    temp[item['user_id']]['total'] += item['total'];
                }
            });

            Object.values(temp).forEach( (item) => {
                this.users.push(item);
            });
        },

        /**
         * Get days form given week number an year (optional).
         * If year not given, it use the current one.
         * 
         * @param  week
         * @param  year
         * @return array
         */
        getWeekDays(week, year = moment().year()) {
            let days = [];
            let begin = moment().year(year).week(week);

            for (let i = 0; i < 7; i++) {
                days.push(begin.weekday(i).format('YYYY-MM-DD'));
            }

            return days;
        },

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

        /**
         * Check if current user has reported any task on current day.
         */
        hasCard(user, day) {
            return (user + '|' + day) in this.filtered_reports
                ? true
                : false;
        },
    }
});

</script>

@endpush
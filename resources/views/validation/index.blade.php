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
                            <span v-bind:class ="getDayColor(day)" v-bind:title ="getDayTitle(day)">
                                @{{ weekdaysShort[index] }}, @{{ day.substr(5, 5) }}
                            </span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="user in users">
                        <td>
                            <strong>@{{ user['name'].toUpperCase() }}</strong>
                            <p>Total Week: @{{ user['total'] }}</p>
                            <!--<p><button class="btn btn-default btn-sm">Validate Week</button></p>-->
                        </td>

                        <td v-for="day in days">

                            <div v-if="hasCard(user['id'], day)"
                                 class="card"
                                 v-bind:class="getCardColor(filtered_reports[user['id']+'|'+day].admin_validation, filtered_reports[user['id']+'|'+day].pm_validation)">

                                <button
                                    style="position:relative; top:-12%; right:-5%;" 
                                    v-bind:title="getValidationButtonTitle(filtered_reports[user['id']+'|'+day].admin_validation,filtered_reports[user['id']+'|'+day].pm_validation,'title')"
                                    class="btn btn-default btn-xs pull-right clickable"
                                    v-on:click="validate(user['id']+'|'+day)">
                                    <span                                    
                                        v-bind:class="getValidationButtonTitle(filtered_reports[user['id']+'|'+day].admin_validation,filtered_reports[user['id']+'|'+day].pm_validation,'other')" aria-hidden="true">
                                    </span>
                                </button> 
                                <a type="button"     
                                    style="position:relative; top:-12%; right:-5%;" 
                                    title="See Report"
                                    class="btn btn-default btn-xs pull-right clickable"
                                    v-bind:href="makeUrl('{{ url('workingreports/add/') }}', [user['id'], day])">
                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                </a> 

                                <div v-for="item in filtered_reports[user['id']+'|'+day].items">
                                    <p>- @{{ item.name.toUpperCase() }}, @{{ item.time_slot }}h</p>
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

            <div style="text-align: center; margin-top: 50px; margin-bottom: 50px" v-show="filtered_reports==null">
                No data available...
            </div> 

        </div>

        <div class="panel-footer">
            @include('validation.footer')
            <div class="clearfix"></div>
        </div>

    </div>

    <div class="row front" v-show="upHere">
        @include('validation.key')
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
    }

    .clickable{
        cursor: pointer;
    }

    .card p {
        margin-bottom: 0px;
    }

    .validated {
        //background: #E1F5FE;
        background: #80d2f7;
    }

    .full-validated {
        background: #c5e1a5;
    }

    .legend{
        font-style: italic;
    }

    .cuadrado{
        width: 15px; 
        height: 15px; 
        display: inline-block;
        margin-right: 1em;
    }

    .bank-holiday{
        color: red;
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
            user_id: '{!! Auth()->user()->id !!}',
            role: '{!! Auth()->user()->primaryRole() !!}',

            //Group-Project
            groupsProjects: <?php echo json_encode($groupsProjects);?>,
            projectList: [] ,
            groupList:[] ,
            
            // Filter options
            filter: { 
                activity:'', 
                user: '', 
                year: moment().year(), 
                week: moment().week() ,
                project: '' , 
                group: '' , 
                weekType: 'reports' , 
                pendingUser: ''
            },

            //Modo holidays
            filteredGroupsProjects: [] ,
            userGroupList: [],
            holidaysPendingList: [],
            weekList: [],

            // Data for the table of cards
            users: [],
            days: [],
            reports: null,
            weekdaysShort : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],

            //Data to indicate the bank holidays
            bankHolidays: [],

            //Hover variable for Key info
            upHere : false
        },

        mounted() {
            this.fetchBankHolidays();//Bank Holidays
            this.projectsLoad();//Proyectos
            this.loadData();//Reports            
        },

        computed:{
            /**
             * Filter database request by activity name
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
            },
              
        },

        methods: {

            projectsLoad() {
                //console.log("projectsLoad")

                let setList = new Set();

                if(this.filter.weekType == 'reports'){
                    array = this.groupsProjects;
                }
                else{
                    array = this.filteredGroupsProjects;
                }

                array.forEach(function(item) {
                    setList.add(item.project);
                });

                this.projectList = [...setList];                
            },

            groupsRefresh(){
                //console.log("groupsRefresh")

                let vm = this;
                let setList = new Set();

                if(this.filter.weekType == 'reports'){
                    array = this.groupsProjects;
                }
                else{
                    array = this.filteredGroupsProjects;
                }
                
                array.forEach(function(item) {      
                    if( vm.filter.project.toLowerCase() == item.project.toLowerCase()){
                         setList.add(item.group);
                    } 
                });

                this.groupList = [...setList];
            },

            filterGroupsProjects(){
                //console.log("filterGroupsProjects")
                let vm = this;

                if(this.userGroupList.length != 0) {         
                    this.filteredGroupsProjects = [];
                    
                    this.userGroupList.forEach(function(item) {   
                        for (let key in vm.groupsProjects) {                   
                             if( vm.groupsProjects[key].group_id == item.group_id){
                                vm.filteredGroupsProjects.push(item);
                            }                                
                        }          
                    });

                    this.projectsLoad();               
                }      
            },

            loadData(){
                if(this.filter.weekType == 'reports'){
                    this.fetchData();
                }
                else if(this.filter.weekType == 'holidays'){
                    this.fetchConflicts();
                }
            },

            yearChange() {
                if(this.filter.weekType == 'holidays'){
                    this.fetchPendingUsers();
                }
                this.fetchBankHolidays();
            },

            pendingUserChange() {
                //console.log("pendingUserChange")
                this.filter.project = '';
                this.filter.group = '';
                this.filter.week = '';

                if(this.holidaysPendingList != [] && this.filter.pendingUser != '') {
                    this.fetchGroups();
                } 
            },

            /**
             * Get Bank Holidays of a year
             */
            fetchBankHolidays() {
                var vm = this;

                vm.bankHolidays = [];

                axios.get('/api/holidays', {
                        params: {
                            //user_id: vm.user_id,
                            year: vm.filter.year,
                            //week: vm.filter.week,                    
                        }
                    })
                    .then(function (response) {
                        vm.bankHolidays = response.data;        
                    })
                    .catch(function (error) {
                       vm.showErrors(error.response.data)
                    });
            },

            /**
             * Get users pending validation
             */
            fetchPendingUsers() {
                //console.log("fetchPendingUsers")

                if(this.filter.weekType == 'reports'){
                    //Filter
                    this.filter.pendingUser = '';
                    this.filter.group = '';
                    this.filter.project = '';
                    this.filter.user = '';
                    this.filter.week = moment().week();

                    this.userGroupList = [];
                    this.weekList = [];
                    this.holidaysPendingList = [];
                    this.projectsLoad();
                    return;
                }

                var vm = this;
                vm.users = [];
                vm.days = [];
                vm.reports = null;
                vm.filter.week ='';
                vm.filter.user ='';
                vm.holidaysPendingList = [];

                axios.get('/api/users', {
                    params: {
                        //user_id: vm.user_id,
                        year: vm.filter.year,
                        //week: vm.filter.week,                    
                    }
                })
                .then(function (response) {
                    vm.holidaysPendingList = response.data;    
                })
                .catch(function (error) {
                   vm.showErrors(error.response.data);
                });
            },

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
                        user_id: vm.reports[key].user_id,
                        day: vm.reports[key].created_at,
                        admin_validation: vm.reports[key].admin_validation,
                        pm_validation: vm.reports[key].pm_validation,
                    }).then(function (response) {
                        if ('admin_validation' in response.data){
                            vm.reports[key].admin_validation = response.data.admin_validation
                        }
                        if ('pm_validation' in response.data){
                            vm.reports[key].pm_validation = response.data.pm_validation
                        }
                        if ('manager_id' in response.data){
                            vm.reports[key].manager = response.data.manager_id
                        }
                        //vm.fetchPendingUsers();

                    }).catch(function (error) {
                        vm.showErrors(error.response.data)
                    });
            },

            /**
             * Fetch reports and initialize users and days arrays.
             */
            fetchData (week = null) {
                var vm = this;
                var inputWeek;
                vm.reports = null;
                vm.users = [];
                vm.days = [];

                inputWeek = week != null ? week : vm.filter.week;
                vm.filter.week = inputWeek;
                
                if(inputWeek < 1 || inputWeek > 53){
                    toastr.error("Week value out of range");
                    vm.filter.week = moment().week();
                    vm.filter.year = moment().year();
                    return;
                }

                axios.get('api/validate', {
                        params: {
                            project: vm.filter.project,
                            group: vm.filter.group,
                            name: vm.filter.user,
                            year: vm.filter.year,
                            week: inputWeek,                    
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
             * Fetch holidays and initialize users and days arrays.
             */
            fetchConflicts () {
                var vm = this;

                vm.reports = null;
                vm.users = [];
                vm.days = [];
                
                axios.get('api/conflicts', {
                        params: {
                            project: vm.filter.project,
                            group: vm.filter.group,
                            user_id: vm.filter.pendingUser,
                            year: vm.filter.year,
                            week: vm.filter.week,                    
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
             * Fetch user groups
             */
            fetchGroups () {
                //console.log("fetchGroups")

                var vm = this;

                vm.userGroupList = [];
                
                axios.get('api/usergroups', {
                        params: {
                            user: vm.filter.pendingUser,                 
                        }
                    })
                    .then(function (response) {
                        vm.userGroupList = response.data;//Grupos del usuario seleccionado
                        vm.getWeeksUser();//Semanas
                        vm.filterGroupsProjects();//Filtrar a los grupos los del usuario seleccionado
                    })
                    .catch(function (error) {
                       vm.showErrors(error.response)
                    });
            },

             /**
             * Get selected users pending validation holidays weeks
             */
            getWeeksUser(){
                //console.log("getWeeksUser")
                var vm = this;

                vm.holidaysPendingList.forEach(function(item){
                    if(item.user_id == vm.filter.pendingUser){
                        vm.weekList = item.weekdate.split("-");
                    }              
                }); 
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

            makeUrl(url, data = null) {
                if (data != null) {
                    return url + '/' + data.join('/');
                }
                return url;
            },

            /**
             * Check if current user has reported any task on current day.
             */
            hasCard(user, day) {
                return (user + '|' + day) in this.filtered_reports
                    ? true
                    : false;
            },

            /**
             * Get the card's color if it is validated
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
             * Get the validation button title if it is validated
             */
            getValidationButtonTitle(admin,pm,type) {
                if(this.role == 'admin'){
                    if(admin){
                        return type == 'title' ? 'Invalidate' : 'glyphicon glyphicon-remove';
                    }
                    return type == 'title' ? 'Validate' : 'glyphicon glyphicon-ok';
                }

                if(this.role == 'manager'){
                    if(admin){
                        return type == 'title' ? '' : 'glyphicon glyphicon-ban-circle';
                    }
                    if(pm){
                        return type == 'title' ? 'Invalidate' : 'glyphicon glyphicon-remove';
                    }
                    return type == 'title' ? 'Validate' : 'glyphicon glyphicon-ok';
                }
            },

            /**
             * Get the days's color if it is Bank Holiday
             */
            getDayColor(day){
                for (var i = this.bankHolidays.length - 1; i >= 0; i--) {
                    if(this.bankHolidays[i].date == day){
                        return 'bank-holiday';
                    }
                }
                return '';
            },

            /**
             * Get the days's color if it is Bank Holiday
             */
            getDayTitle(day){
                for (var i = this.bankHolidays.length - 1; i >= 0; i--) {
                    if(this.bankHolidays[i].date == day){
                        return "BANK HOLIDAY IN " + this.bankHolidays[i].name.toUpperCase();
                    }
                }
                return '';
            },

        }
    });

</script>

@endpush
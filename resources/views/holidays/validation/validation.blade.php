@extends('layouts.app')

@section('content')	
	<div class="panel panel-primary">

		<div class="panel-heading">		
			@include('holidays.validation.filter')
			<div class="clearfix"></div>
		</div>

		<div class="panel-body">
			@include('holidays.validation.table')
		</div>

		<div class="panel-footer">
			@include('holidays.validation.footer')
			<div class="clearfix"></div>
		</div>

	</div>
@endsection

@push('script-bottom')

<style>
	.container {
	    width: 100%;
	}
</style>

<script>
	var url = "{{ url('/') }}";
	var user = <?php echo json_encode($user);?>;
</script>

<script>
	
var app = new Vue({
    el: '#app',
    data: {
    	url: url,
    	user: user,

    	//Filter options
    	filter:{
    		year: moment().year(),
    		week: moment().week(),
    		week_type: 'all',
    		project: '',
    		group: '',
    	},

    	//Filtro Proyectos y grupos
    	groupsProjects: [],
    	projectList: [] ,
		groupList:[] ,
		weekList:[] ,

    	// Data for the table of cards
    	weekdaysShort : ['Mon.', 'Tues.', 'Wed.', 'Thurs.', 'Fri.', 'Sat.', 'Sun.'],
    	days: [],
    	//users: [],
    	holidays: [],
        reports: null
    },

    mounted(){
    	this.loadWeeks();//Holidays Weeks filter
    	this.loadFilters();//Groups and Projects
    	this.loadHolidays();//Bank Holidays
    	this.fetchData();//Holidays for the group of users
    },

    computed:{

    },

    methods:{
    	makeUrl(url, data = null) {
            if (data != null) {
                return url + '/' + data.join('/');
            }
            return url;
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

        projectsLoad() {
			let setList = new Set();

			this.groupsProjects.forEach(function(item) {
				setList.add(item.project_name);
			});

			this.projectList = [...setList];				
		},

        groupsRefresh(){
			let vm = this;
			let setList = new Set();
			
			vm.groupsProjects.forEach(function(item) {						
				if( vm.filter.project == item.project_name){
					 setList.add(item.group_name);
				}				
			});

			this.groupList = [...setList];
		},

		fetchData () {
			var vm = this;
            
            axios.get(vm.url + '/api/load', {
                    params: {
                        user_id: vm.user.id,
                        year: vm.filter.year,
                        week: vm.filter.week,                    
                    }
                })
                .then(function (response) {
                    console.log(response.data);
                    vm.reports = response.data;        
                })
                .catch(function (error) {
                   console.log(error.response);
                });
		},

        loadWeeks () {
            var vm = this;
            
            axios.get(vm.url + '/api/weeks', {
                    params: {
                        user_id: vm.user.id,
                        year: vm.filter.year,                   
                    }
                })
                .then(function (response) {
                    console.log(response.data);
                    vm.weekList = response.data;
                    vm.days = vm.getWeekDays(vm.filter.week, vm.filter.year);
                })
                .catch(function (error) {
                   console.log(error.response);
                });
        },

        loadFilters(){
        	var vm = this;
            
            axios.get(vm.url + '/api/filters', {
                    params: {
                        user_id: vm.user.id,                   
                    }
                })
                .then(function (response) {
                    console.log(response.data);
                   vm.groupsProjects = response.data;
                   vm.projectsLoad();
                })
                .catch(function (error) {
                   console.log(error.response);
                });
        },

        loadHolidays() {
        	var vm = this;
            
            axios.get(vm.url + '/api/holidays', {
                    params: {
                        user_id: vm.user.id,
                        year: vm.filter.year,
                        week: vm.filter.week,                    
                    }
                })
                .then(function (response) {
                    console.log(response.data);
                    vm.holidays = response.data;        
                })
                .catch(function (error) {
                   console.log(error.response);
                });
        },

    }
});
</script>

@endpush
@extends('layouts.app')

@section('content')	
	<div class="panel panel-primary">

		<div class="panel-heading">		
			@include('holidays.validation.filter')
			<div class="clearfix"></div>
		</div>

		<div class="panel-body">
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
    		week_type: 'all',
    		week: moment().week(),
    		project: '',
    		group: '',
    	},

    	// Data for the table of cards
    	weekdaysShort : ['Mon.', 'Tues.', 'Wed.', 'Thurs.', 'Fri.', 'Sat.', 'Sun.'],
    	users: [],
        days: [],
        reports: null
    },

    mounted(){
    	this.fetchData();
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
         * Fetch reports and initialize users and days arrays.
         */
        fetchData (week = null) {
            var vm = this;
            
            axios.get(vm.url + '/api/holidaysValidate', {
                    params: {
                        user_id: vm.user.id,
                        year: vm.filter.year,
                        week: vm.filter.week,                    
                    }
                })
                .then(function (response) {
                    console.log(response.data);
                })
                .catch(function (error) {
                   console.log(error.response);
                });
        },
    }
});
</script>

@endpush
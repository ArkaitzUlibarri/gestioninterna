
/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


const app = new Vue({

	el: '#app',

	data: {		
		role: '',	
		pm: 0,
		user_id: -1,	
		reports: [],
		tasks: [],
	},

	mounted() {
		this.reports = workingreport;
		this.role    = auth_user.role;
		this.user_id = auth_user.id;
		this.pm      = pm;
	},

	methods: {

		getDate() {
			var today = new Date();
			var dd    = today.getDate();
			var mm    = today.getMonth() + 1; //January is 0!
			var yyyy  = today.getFullYear();

			if(dd < 10){
				dd ='0'+dd
			} 
			if(mm < 10){
				mm ='0'+mm
			} 
			today = yyyy +'-'+ mm +'-'+ dd;

			return today;
		},

		getWeek(dowOffset,stringDate) {

			var d = new Date(stringDate);

			dowOffset   = typeof(dowOffset) == 'number' ? dowOffset : 0; //default dowOffset to zero
			var newYear = new Date(d.getFullYear(),0,1);
			var day     = newYear.getDay() - dowOffset; //the day of week the year begins on
			day         = (day >= 0 ? day : day + 7);
			var daynum  = Math.floor((d.getTime() - newYear.getTime() - 
		    (d.getTimezoneOffset()-newYear.getTimezoneOffset())*60000)/86400000) + 1;
		    var weeknum;
		    //if the year starts before the middle of a week
		    if(day < 4) {
		        weeknum = Math.floor((daynum+day-1)/7) + 1;
		        if(weeknum > 52) {
					nYear = new Date(d.getFullYear() + 1,0,1);
					nday  = nYear.getDay() - dowOffset;
					nday  = nday >= 0 ? nday : nday + 7;
					/*if the next year starts before the middle of
					the week, it is week #1 of that year*/
					weeknum = nday < 4 ? 1 : 53;
		        }
		    }
		    else {
		        weeknum = Math.floor((daynum+day-1)/7);
		    }
		    return weeknum;
		},

		getDayWeek(stringDate) {
			var d = new Date(stringDate);

			var weekday = new Array(7);
			weekday[0]  = "Sunday";
			weekday[1]  = "Monday";
			weekday[2]  = "Tuesday";
			weekday[3]  = "Wednesday";
			weekday[4]  = "Thursday";
			weekday[5]  = "Friday";
			weekday[6]  = "Saturday";

			return weekday[d.getDay()];
		},

		validate(user, date) {
			console.log("Validate: " + user + '|' +date);		
			this.fetchData(user,date);
		},

		supervise() {
			let vm  = this;

			if (confirm("¿Estás seguro de que quieres (des)validar el día?")) {
				vm.tasks.forEach(function (item) {

					if(vm.role == 'admin' && item.pm_validation == 1 || item.pm_validation == true){
						item.admin_validation = 1;
						console.log("Admin:" + item.admin_validation);
					}
					else if (vm.role == 'user'){
						item.pm_validation = 1;
						console.log("PM:" + item.pm_validation);
					}			
				});
			}
		},

		fetchData(user_id,created_at) {
			let vm   = this;
			vm.tasks = [];

			axios.get('/api/reports', {
				params: {
					user_id: user_id,
					created_at: created_at,
				}
			})
			.then(function (response) {
				vm.tasks = response.data;
				console.log(response.data);
				vm.supervise();
				vm.save();
			})
			.catch(function (error) {
				console.log(error);
			});
		},

		save(){
			let vm = this;

			vm.tasks.forEach(function (item) {
		
				axios.patch('/api/reports/' + item.id, item)
				.then(function (response) {
					console.log(response.data);
				})
				.catch(function (error) {
					console.log(error);
				});

			});

		},

	}
});

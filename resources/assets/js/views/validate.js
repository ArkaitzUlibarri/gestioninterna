
/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


const app = new Vue({

	el: '#app',

	data: {			
		user: "",
		role: "",
		reportdate: "",

		editIndex: -1,

		newTask: {
			id: -1,
			user_id: -1,
			created_at: '',
			activity: "",
			project_id: "",
			group_id: "",
			category_id:"",
			absence_id: "",
			training_type:"",
			time_slots: 0,
			time:0,
			job_type:"",
			comments: "",
			pm_validation: 0,
			admin_validation: 0,
		},

		tasks: []
	},

	methods: {
		fetchData(){

		},

		save(){
			let vm = this;
			
			axios.patch('/api/reports/' + vm.newTask.id, vm.newTask)
			.then(function (response) {
				console.log(response.data);
				//---------------------------------------
				let properties = Object.keys(vm.newTask);

				for (let i = properties.length - 1; i >= 0; i--) {
					vm.tasks[vm.editIndex][properties[i]] = vm.newTask[properties[i]];
				}
				vm.initializeTask();
				//---------------------------------------
			})
			.catch(function (error) {
				console.log(error);
			});
			return;

		},

	}
});

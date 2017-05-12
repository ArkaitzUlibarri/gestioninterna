
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');

const app = new Vue({
	
    el: '#project',

    data: {
    	project_id: id,

		groups: [],

		newGroup: {
			id: -1,
			project_id: "",
			name: "",
			enabled: 0,
		}
	},


	mounted() {
		console.log("A");
		this.fetchData();
	},

	methods: {
		addGroup(){

		},
		editGroup(){
			
		},
		fetchData() {
			console.log("B");
			let vm = this;
			vm.groups = [];

			axios.get('/api/groups', {
				params: {
					project_id: vm.project_id,
				}
			})
			.then(function (response) {
				console.log("C")
				vm.groups = response.data;
				console.log(response.data);
			})
			.catch(function (error) {
				console.log("D")
				console.log(error);
			});
		},

		save(){
			let vm = this;
			
			
			if(vm.newGroup.id != -1) {
				axios.patch('/api/groups/' + vm.newGroup.id, vm.newGroup)
				.then(function (response) {
					console.log(response.data);
				})
				.catch(function (error) {
					console.log(error);
				});
				return;
			}
			else{

				axios.post('/api/groups', vm.newGroup)
				.then(function (response) {
					console.log(response.data);
					vm.newGroup.id=response.data;
				})
				.catch(function (error) {
					console.log(error);
				});	
				return;

			}	
		},
	},
});


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


/**
 * Registro los componentes necesarios.
 */
Vue.component('group-template', require('../components/Group.vue'));


const app = new Vue({
	
    el: '#project',

    data: {
    	project_id: id,

		groups: [],

		editIndex: -1,

		newGroup: {
			id: -1,
			project_id: "",
			name: "",
			enabled: 0,
		}
	},

	created() {

		Event.$on('Edit', (index, group) => {
			this.newGroup = {
				id: group.id,
				project_id:group.project_id,
				name:group.name,
				enabled:group.enabled,
			};

			this.editIndex = index;
		});

	},

	mounted() {
		this.fetchData();
		this.newGroup.project_id = this.project_id;
	},

	methods: {

		addGroup(){
			console.log("addGroup");
			this.save();
		},

		editGroup(){
			console.log("editGroup");
			this.save();
		},

		initializeGroup(){
			this.newGroup = {
				id: -1,
				project_id:this.project_id,
				name:"",
				enabled:0,
			};
		},

		fetchData() {

			let vm = this;
			vm.groups = [];

			axios.get('/api/groups', {
				params: {
					project_id: vm.project_id,
				}
			})
			.then(function (response) {
				vm.groups = response.data;
				console.log(response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
		},

		save(){
			let vm = this;
						
			if(vm.newGroup.id != -1) {
				axios.patch('/api/groups/' + vm.newGroup.id, vm.newGroup)
				.then(function (response) {
					console.log(response.data);

					let properties = Object.keys(vm.newGroup);

					for (let i = properties.length - 1; i >= 0; i--) {
						vm.groups[vm.editIndex][properties[i]] = vm.newGroup[properties[i]];
					}

					vm.initializeGroup();
					vm.editIndex = -1;
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
					vm.groups.push(vm.newGroup);
					vm.initializeGroup();
				})
				.catch(function (error) {
					console.log(error);
				});	
				return;

			}	
		},

	},
});

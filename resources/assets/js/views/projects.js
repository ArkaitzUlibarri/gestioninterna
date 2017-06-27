
/**
 * Registro los componentes necesarios.
 */
Vue.component('group-template', require('../components/Group.vue'));

const app = new Vue({

    el: '#project',

    data: {		
    	url: url,

    	project_id: id,
		groups: [],
		editIndex: -1,
		newGroup: {	id: -1,	project_id: '',	name: '', enabled: 0 }
	},

	created() {
		Event.$on('Delete', (index, group) => {
			this.delete(index);
		});

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
		saveGroup(){
			this.save();
		},

		initializeGroup(){
			this.newGroup = {
				id: -1,
				project_id:this.project_id,
				name:"",
				enabled:0,
			};

			this.editIndex = -1;
		},

		fetchData() {
			let vm = this;
			vm.groups = [];

			axios.get(vm.url + '/api/groups', {
					params: {
						project_id: vm.project_id,
					}
				})
				.then(function (response) {
					vm.groups = response.data;	
					vm.groups.forEach(function(element,index,array) {
						if(element.name =='Default'){
							array.splice(index, 1);
						}
					});
				})
				.catch(function (error) {
					vm.showErrors(error.response.data)
				});
		},

		save(){
			let vm = this;
						
			if(vm.newGroup.id != -1) {
				axios.patch(vm.url + '/api/groups/' + vm.newGroup.id, vm.newGroup)
					.then(function (response) {
						toastr.success("Updated");
						let properties = Object.keys(vm.newGroup);
						for (let i = properties.length - 1; i >= 0; i--) {
							vm.groups[vm.editIndex][properties[i]] = vm.newGroup[properties[i]];
						}
						vm.initializeGroup();
					})
					.catch(function (error) {
						console.log(error);
					});
				return;
			}
			else {
				axios.post(vm.url + '/api/groups', vm.newGroup)
					.then(function (response) {
						toastr.success("Saved");
						vm.newGroup.id = response.data;
						vm.groups.push(vm.newGroup);
						vm.initializeGroup();
					})
					.catch(function (error) {
						vm.showErrors(error.response.data)
					});	
			}	
		},

		delete(index){
			let vm = this;
				
			axios.delete(vm.url + '/api/groups/' + vm.groups[index].id)
			.then(function (response) {
				if(response.data){
					toastr.success("Deleted");
					vm.groups.splice(index, 1);
					vm.initializeGroup();
				}
				else {
					toastr.warning("This group cannot be deleted");
				}
			})
			.catch(function (error) {
				vm.showErrors(error.response.data)
			});	
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
		}

	},
});

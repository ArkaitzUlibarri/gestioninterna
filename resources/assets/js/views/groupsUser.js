
/**
 * Registro los componentes necesarios.
 */
Vue.component('group-template', require('../components/ProjectGroup.vue'));

const app = new Vue({
	el: '#groups',

	data: {		
		url: url,

		user: user,
		groupProjects: groupProjects,
		projectList: [],
		groupList: [],
		groupProjectsList: [],
		newGroupUser: {
			id: -1,
			user_id: -1,
			project_id: -1,
			project: '',
			group_id: -1,
			group: '',
			enabled: 0,
		},

		array: [],
	},


	computed: {
		formFilled(){
			if(this.newGroupUser.group != ''  && this.newGroupUser.project != ''){
				return true;
			}		
			else{
				return false;
			}
		},
	},

	created() {
		Event.$on('Delete', (index, item) => {
			if(confirm("You are going to delete this entry,are you sure?")){
				this.delete(index);
				this.array.splice(index, 1);
			}
		});
	},

	mounted() {
		this.newGroupUser.user_id = this.user.id;
		this.fetchData();
	},

	methods: {	
		project() {
			let setList = new Set();

			this.groupProjects.forEach(function(item) {
				setList.add(item.project);
			});

			this.projectList = [...setList];
		},

		groupsRefresh(){
			let vm = this;
			let setList = new Set();
			
			vm.groupProjects.forEach(function(item) {						
				if( vm.newGroupUser.project == item.project){
					 setList.add(item.group);
				}				
			});

			this.groupList = [...setList];
		},

		nameTraduction(){	
			//GrupoProyecto
			for (var key = this.groupProjects.length - 1; key >= 0; key--) {
				if(this.groupProjects[key].group == this.newGroupUser.group && this.groupProjects[key].project == this.newGroupUser.project){
					this.newGroupUser.group_id    = this.groupProjects[key].id;
					this.newGroupUser.project_id  = this.groupProjects[key].project_id;
					this.newGroupUser.enabled  = this.groupProjects[key].enabled;
				}
			}
		},

		initialize(){
			this.newGroupUser = {
				id: -1,
				user_id: this.user.id,
				project_id: -1,
				project: '',
				group_id: -1,
				group: '',
				enabled: 0,
			};
		},

		saveGroup(){
			this.nameTraduction();
			this.save();
		},

		fetchData(){
			let vm   = this;
			vm.array = [];

			axios.get(vm.url + '/api/groupsUser', {
					params: {
						id: vm.user.id,
					}
				})
				.then(function (response) {
					vm.array = response.data;
					vm.project();

					for (let i = vm.array.length - 1; i >= 0; i--) {
						if(vm.projectList.indexOf(vm.array[i].project) == -1){	
							vm.array.splice(i,1);								
						}	
					}
				})
				.catch(function (error) {
					vm.showErrors(error.response.data)
				});
		},

		delete(index){
			let vm = this;
				
			axios.delete(vm.url + '/api/groupsUser/' + vm.array[index].id)
				.then(function (response) {
					toastr.success(response.data);
				})
				.catch(function (error) {
					vm.showErrors(error.response.data)
				});	
		},

		save(){
			let vm = this;

			axios.post(vm.url + '/api/groupsUser',vm.newGroupUser)
				.then(function (response) {
					toastr.success("Saved");
					vm.newGroupUser.id = response.data;
					vm.array.push(vm.newGroupUser);		
					vm.initialize();
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

	}
});

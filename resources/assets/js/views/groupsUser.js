
/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


/**
 * Registro los componentes necesarios.
 */
Vue.component('group-template', require('../components/ProjectGroup.vue'));

const app = new Vue({

	el: '#groups',

	data: {		

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
		this.project();
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

			axios.get('/api/groupsUser', {
				params: {
					id: vm.user.id,
				}
			})
			.then(function (response) {
				vm.array = response.data;
				console.log(response.data);
			})
			.catch(function (error) {
				console.log(error);
				//****************************************
				if(Array.isArray(error.response.data)) {
					error.response.data.forEach( (error) => {
						toastr.error(error);
					})
				}
				else {
					toastr.error(error.response.data);
				}
				//****************************************
			});
		},

		delete(index){

			let vm = this;
				
			axios.delete('/api/groupsUser/' + vm.array[index].id)
			.then(function (response) {
				console.log(response.data);
				toastr.success(response.data);
			})
			.catch(function (error) {
				console.log(error);
				//****************************************
				if(Array.isArray(error.response.data)) {
					error.response.data.forEach( (error) => {
						toastr.error(error);
					})
				}
				else {
					toastr.error(error.response.data);
				}
				//****************************************
			});	
		},

		save(){
			let vm = this;

			axios.post('/api/groupsUser',vm.newGroupUser)
			.then(function (response) {
				console.log(response.data);
				toastr.success("Saved");
				//---------------------------------------
				vm.newGroupUser.id = response.data;
				vm.array.push(vm.newGroupUser);		
				vm.initialize();
				//---------------------------------------	
			})
			.catch(function (error) {
				console.log(error);
				//****************************************
				if(Array.isArray(error.response.data)) {
					error.response.data.forEach( (error) => {
						toastr.error(error);
					})
				}
				else {
					toastr.error(error.response.data);
				}
				//****************************************
			});	
			return;

				
		}

	}
});


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
    	info:{
			origin:window.location.origin,
			serverPath:"",
		},

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
		this.info.serverPath = this.getPath();
		this.fetchData();
		this.newGroup.project_id = this.project_id;
	},

	methods: {
		getPath(){
			let pathArray = window.location.pathname.split("/");
			let path = "";
			let position = 0;

			for (let i = pathArray.length - 1; i >= 0; i--) {
				if (pathArray[i] == "public"){
					position = i;
					break;
				}
			}

			if(position != 0){
				for (let j = 0; j <= position; j++) {
					path = path + pathArray[j] + "/";
				}
				return path;
			}	

			return "";
		},
		
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

			axios.get(vm.info.origin + vm.info.serverPath + '/api/groups', {
				params: {
					project_id: vm.project_id,
				}
			})
			.then(function (response) {
				vm.groups = response.data;	
				console.log(response.data);		
				//****************************************************
				vm.groups.forEach(function(element,index,array) {
					if(element.name =='Default'){
						array.splice(index, 1);
					}
				});
				//****************************************************
			})
			.catch(function (error) {
				console.log(error);
				//********************************************
				if(Array.isArray(error.response.data)) {
					error.response.data.forEach( (error) => {
						toastr.error(error);
					})
				}
				else {
					toastr.error(error.response.data);
				}
				//**********************************************
			});
		},

		save(){
			let vm = this;
						
			if(vm.newGroup.id != -1) {
				axios.patch(vm.info.origin + vm.info.serverPath + '/api/groups/' + vm.newGroup.id, vm.newGroup)
				.then(function (response) {
					console.log(response.data);
					toastr.success("Updated");
					//---------------------------------------
					let properties = Object.keys(vm.newGroup);

					for (let i = properties.length - 1; i >= 0; i--) {
						vm.groups[vm.editIndex][properties[i]] = vm.newGroup[properties[i]];
					}
					vm.initializeGroup();
					//---------------------------------------
				})
				.catch(function (error) {
					console.log(error);
				});
				return;
			}
			else{

				axios.post(vm.info.origin + vm.info.serverPath + '/api/groups', vm.newGroup)
				.then(function (response) {
					console.log(response.data);
					toastr.success("Saved");
					//---------------------------------------
					vm.newGroup.id = response.data;
					vm.groups.push(vm.newGroup);
					vm.initializeGroup();
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
		},

		delete(index){
			let vm = this;
				
			axios.delete(vm.info.origin + vm.info.serverPath + '/api/groups/' + vm.groups[index].id)
			.then(function (response) {
				console.log(response.data);
				//---------------------------------------
				if(response.data){
					toastr.success("Deleted");
					vm.groups.splice(index, 1);
					vm.initializeGroup();
				}
				else {
					//console.log("No es posible borrar este grupo");
					toastr.warning("This group cannot be deleted");
				}
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
		}

	},
});

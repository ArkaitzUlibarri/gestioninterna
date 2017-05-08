
/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');


/**
 * Registro los componentes necesarios.
 */
Vue.component('task-template', require('../components/Task.vue'));

const app = new Vue({

	el: '#report',

	data: {			
		user: user,
		reportdate: reportdate,
		groupProjects: groupProjects,
		absences:absences,

		projectList: [] ,
		groupList:[] ,

		job_type:null,
		editIndex: -1,

		newTask: {
			id: -1,
			activity: null,
			project_id: null,
			project: null,
			group_id: null,
			group: null,
			absence_id: null,
			absence: null,
			training_type:null,
			time_slots: 0,
			job_type:null,
			comments: null,
			pm_validation: 0,
			admin_validation: 0,
		},

		tasks: []
	},


	computed: {
		totalTime() {
			let total = 0;
			
			if (this.tasks.length > 0) {
				this.tasks.forEach( (item) => {
					total += (parseInt(item.time_slots)*0.25);
				});
			}
					
			return total;
		},

		taskValidated(){
			if (this.newTask.activity != null && this.newTask.time_slots != 0 && this.newTask.comments != null) {
				if(this.newTask.activity == 'project' && this.newTask.project != null && this.newTask.group != null ){
					return true;
				}
				if(this.newTask.activity == 'absence' && this.newTask.absence != null){
					return true;
				}
				if(this.newTask.activity == 'training' && this.newTask.training_type != null){
					return true;
				}
			}

			return false;
		},

	},

	created() {
		Event.$on('DeleteTask', (index, task) => {
			console.log("Borrado el indice "+ index);
			this.tasks.splice(index, 1);
		});

		Event.$on('EditTask', (index, task) => {
			console.log("Editando el indice "+ index);
			this.newTask = {
				id: task.id,
				activity:task.activity,
				project_id:task.project_id,
				project:task.project,
				group_id:task.group_id,
				group:task.group,
				absence_id:task.absence_id,
				absence:task.absence,
				training_type:task.training_type,
				time_slots: task.time_slots,
				job_type:task.job_type,
				comments:task.comments,
				pm_validation: task.pm_validation,
				admin_validation: task.admin_validation,
			};

			this.idTraduction();
			this.groupsRefresh();

			this.editIndex = index;

		});
	},

	mounted() {
		this.fetchData();
		this.project();
	},

	methods: {

		addTask() {
			this.nameTraduction();
			this.tasks.push(this.newTask);
			this.initializeTask();
			
		},

		editTask() {
			console.log("Editado el indice "+ this.editIndex);

			let properties = Object.keys(this.newTask);

			for (let i = properties.length - 1; i >= 0; i--) {
				this.tasks[this.editIndex][properties[i]] = this.newTask[properties[i]];
			}

			this.initializeTask();

			this.editIndex = -1;
		},
		
		initializeTask(){
			this.newTask = {
				id: -1,
				activity: null,
				project_id: null,
				project: null,
				group_id: null,
				group: null,
				absence_id: null,
				absence: null,
				training_type:null,
				time_slots: 0,
				job_type:null,
				comments: null,
				pm_validation: 0,
				admin_validation: 0,
			};

		},

		validateTask(){

			if(confirm("¿Estás seguro de que quieres validar el día?")){

				console.log("Validando el dia");
				this.tasks.forEach( (item) => {
					item.pm_validation = 1;
					item.admin_validation = 1;
				});

				this.initializeTask();

			}

		},
		
		refreshForm(){
			this.newTask = {
				id: this.newTask.id,
				activity:this.newTask.activity,
				project_id: null,
				project: null,
				group_id: null,
				group: null,
				absence_id:null,
				absence:null,
				training_type: null,
				time_slots: 0,
				job_type:null,
				comments: null,
				pm_validation: 0,
				admin_validation: 0,
			};

		},

		fetchData() {
			let vm = this;
			vm.tasks = [];

			axios.get('/api/reports', {
				params: {
					user_id: vm.user,
					created_at: vm.reportdate,
				}
			})
			.then(function (response) {
				vm.tasks = response.data;
				console.log(response.data);
			})
			.catch(function (error) {
				console.log(error);
			});

		},

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
				if( vm.newTask.project == item.project){
					 setList.add(item.group);
				}				
			});

			this.groupList=[...setList];

		},

		nameTraduction(){

			this.newTask.project_id= null;
			this.newTask.group_id= null;
			this.newTask.absence_id= null;

			//Ausencia
			if(this.newTask.activity =='absence') {		
				for (let i = this.absences.length - 1; i >= 0; i--) {
					if(this.absences[i].name==this.newTask.absence){
						this.newTask.absence_id=this.absences[i].id;
					}
				}
			}
		
			//GrupoProyecto
			if(this.newTask.activity =='project') {		
				for (var key = this.groupProjects.length - 1; key >= 0; key--) {
					if(this.groupProjects[key].group==this.newTask.group){
						this.newTask.group_id=this.groupProjects[key].group_id;
						this.newTask.project_id=this.groupProjects[key].project_id;
					}
				}
			}

		},

		idTraduction(){

			this.newTask.project= null;
			this.newTask.group= null;
			this.newTask.absence= null;

			//Ausencia
			if(this.newTask.activity =='absence') {		
				for (let i = this.absences.length - 1; i >= 0; i--) {
					if(this.newTask.absence_id==this.absences[i].id){
						this.newTask.absence=this.absences[i].name;
						break;
					}
				}
			}
		
			//GrupoProyecto
			if(this.newTask.activity =='project') {		
				for (let key = this.groupProjects.length - 1; key >= 0; key--) {
					if(this.newTask.group_id == this.groupProjects[key].group_id ) {	
						this.newTask.group = this.groupProjects[key].group;
						this.newTask.project = this.groupProjects[key].project;
						break;
					}
				}
			}

		},

		save(){
		//TODO
		}

	}
});

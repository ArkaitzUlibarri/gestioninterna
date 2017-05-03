
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
		tasks: [],

		job_type:'in-person',

		newTask: {
			id: -1,
			activity:'-',
			project_id: '-',
			group_id: '-',
			absence_id:'-',
			training_type:'-',
			time_slots: 0,
			job_type:this.job_type,
			comments: '',
			pm_validation: false,
			admin_validation: false,
		},
		user: user,
		reportdate: reportdate,
		editIndex: -1
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
			if(this.job_type!='-'){
				if (this.newTask.activity!='-' && this.newTask.time_slots!=0 && this.newTask.comments!='') {
					if(this.newTask.activity=='Project' && this.newTask.project_id!='-' && this.newTask.group_id!='-' ){
						return true;
					}
					if(this.newTask.activity=='Absence' && this.newTask.absence_id!='-'){
						return true;
					}
					if(this.newTask.activity=='Training' && this.newTask.training_type!='-'){
						return true;
					}
				}
			}

			return false;
		}
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
				group_id:task.group_id,
				absence_id:task.absence_id,
				training_type:task.training_type,
				time_slots: task.time_slots,
				job_type:task.job_type,
				comments:task.comments,
				pm_validation: task.pm_validation,
				admin_validation: task.admin_validation,
			};
			this.editIndex = index;

		});
	},

	mounted() {
		console.log("A")
		this.fetchData();
	},
	methods: {
		addTask() {
			this.tasks.push(this.newTask);
			this.newTask = {
				id: -1,
				activity:'-',
				project_id: '-',
				group_id: '-',
				absence_id:'-',
				training_type:'-',
				time_slots: 0,
				job_type:this.job_type,
				comments: '',
				pm_validation: false,
				admin_validation: false,
			};
		},


		editTask() {
			console.log("Editado el indice "+ this.editIndex);

			let properties = Object.keys(this.newTask);

			for (let i = properties.length - 1; i >= 0; i--) {
				this.tasks[this.editIndex][properties[i]] = this.newTask[properties[i]];
			}

			this.newTask = {
				id: -1,
				activity:'-',
				project_id: '-',
				group_id: '-',
				absence_id:'-',
				training_type:'-',
				time_slots: 0,
				job_type:this.job_type,
				comments: '',
				pm_validation: false,
				admin_validation: false,
			};

			this.editIndex = -1;
		},
		
		validateTask(){

			console.log("Validando el dia");

			this.tasks.forEach( (item) => {
				item.pm_validation=true;
				item.admin_validation=true;
			});

			this.newTask = {
				id: -1,
				activity:'-',
				project_id: '-',
				group_id: '-',
				absence_id:'-',
				training_type:'-',
				time_slots: 0,
				job_type:this.job_type,
				comments: '',
				pm_validation: false,
				admin_validation: false,
			};

			console.log("A")
			this.fetchData();
			
		},
		
		refreshForm(){
			this.newTask = {
				id: this.newTask.id,
				activity:this.newTask.activity,
				project_id: '-',
				group_id: '-',
				absence_id:'-',
				training_type:'-',
				time_slots: 0,
				job_type:this.job_type,
				comments: '',
				pm_validation: false,
				admin_validation: false,
			};
		},
		fetchData() {
			console.log("B")
			let vm = this;
			vm.tasks = [];

			axios.get('/api/reports', {
				params: {
					user_id: vm.user,
					created_at: vm.reportdate,
				}
			})
			.then(function (response) {
				console.log("C")		
				vm.tasks = response.data;
				console.log(response.data);
			})
			.catch(function (error) {
				console.log("D")
				console.log(error);
			});

		},
		postData(){

		}
	}
});

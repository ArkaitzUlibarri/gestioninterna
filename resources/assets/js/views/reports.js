
/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


/**
 * Registro los componentes necesarios.
 */
Vue.component('task-template', require('../components/Task.vue'));

const app = new Vue({

	el: '#report',

	data: {		
		info:{
			origin:window.location.origin,
			serverPath:"",
		},

		contract: user_contract,
		expHours: 0,

		user: report_user,
		teleworking : teleworking,
		role: role,
		reportdate: reportdate,
		reportDayWeek: "",
		week: 0,

		categories: categories,
		groupProjects: groupProjects,
		absences:absences,

		projectList: [] ,
		groupList:[] ,
		categoryList: [] ,

		editIndex: -1,

		newTask: {
			id: -1,
			user_id: -1,
			created_at: '',
			activity: "",
			project_id: "",
			project: "",
			group_id: "",
			group: "",
			category_id:"",
			category:"",
			absence_id: "",
			absence: "",
			training_type:"",
			time_slots: 0,
			time:0,
			job_type:"",
			comments: "",
			pm_validation: 0,
			admin_validation: 0,
		},

		tasks: [],
	},


	computed: {

		validatedTasks(){

			let output = true;

			if (this.tasks.length > 0) {
				this.tasks.forEach( (item) => {
					if(item.pm_validation == 0){
						output = false;
						return output;
					}
				});
				if(output){
					return true;
				}
			}
			return false;
				
		},

		totalTime() {
			let total = 0;
			
			if (this.tasks.length > 0) {
				this.tasks.forEach( (item) => {
					total += (parseInt(item.time_slots)*0.25);
				});
			}
					
			return total;
		},

		formTaskFilled(){
			if(this.newTask.pm_validation == 0 && this.newTask.admin_validation == 0){
				if (this.newTask.activity != "" && this.newTask.time != 0) {
					if(this.newTask.activity == 'project' && this.newTask.project != "" && this.newTask.group != "" && this.newTask.category != "" && this.newTask.job_type != ""){
						return true;
					}
					if(this.newTask.activity == 'absence' && this.newTask.absence != ""){
						return true;
					}
					if(this.newTask.activity == 'training' && this.newTask.training_type != "" && this.newTask.job_type != ""){
						return true;
					}
				}
			}		

			return false;
		},

	},

	created() {
		Event.$on('DeleteTask', (index, task) => {
			this.delete(index);
			this.tasks.splice(index, 1);
		});

		Event.$on('EditTask', (index, task) => {
			this.newTask = {
				id: task.id,
				user_id:this.user.id,
				created_at:this.reportdate,
				activity:task.activity,
				project_id:task.project_id,
				project:task.project,
				group_id:task.group_id,
				group:task.group,
				category_id:task.category_id,
				category:task.category,
				absence_id:task.absence_id,
				absence:task.absence,
				training_type:task.training_type,
				time_slots: task.time_slots,
				time:(parseInt(task.time_slots)*0.25),
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
		this.info.serverPath = this.getPath();
		this.fetchData();
		this.project();
		this.categoriesLoad();
		this.setMaxDate();
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

		expectedHours(){
			if(this.contract != null){
				if(this.contract.week_hours == 40){
					if(this.reportDayWeek == 'friday'){
						this.expHours = 7;
					}
					else if(this.reportDayWeek != 'saturday' && this.reportDayWeek != 'sunday'){
						this.expHours = 8.25;
					}
					this.expHours = 0;
				}
				this.expHours = parseInt(this.contract.week_hours / 5);
			}

		},

		dateValidation() {
			var today = this.getDate();
			var datefield = document.getElementById("datefield").value;

			if (datefield.length == 10 && moment(datefield,"YYYY-MM-DD").isValid() &&  datefield <= today) {
				this.fetchData();
			}
			else{
				//console.log("Fecha mayor que hoy");
				toastr.error("Fecha mayor que hoy o con formato erróneo");
				this.reportdate = today;
				this.fetchData();
			}
					
		},

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

		setMaxDate(){
			var today = this.getDate();
			
			if(document.getElementById('datefield') !== null){
				document.getElementById("datefield").setAttribute("max", today);
			}	
		},

		getWeek(dowOffset,stringDate) {

			var d = new Date(stringDate);

			dowOffset = typeof(dowOffset) == 'number' ? dowOffset : 0; //default dowOffset to zero
		    var newYear = new Date(d.getFullYear(),0,1);
		    var day = newYear.getDay() - dowOffset; //the day of week the year begins on
		    day = (day >= 0 ? day : day + 7);
		    var daynum = Math.floor((d.getTime() - newYear.getTime() - 
		    (d.getTimezoneOffset()-newYear.getTimezoneOffset())*60000)/86400000) + 1;
		    var weeknum;
		    //if the year starts before the middle of a week
		    if(day < 4) {
		        weeknum = Math.floor((daynum+day-1)/7) + 1;
		        if(weeknum > 52) {
		            nYear = new Date(d.getFullYear() + 1,0,1);
		            nday = nYear.getDay() - dowOffset;
		            nday = nday >= 0 ? nday : nday + 7;
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
			weekday[0] = "sunday";
			weekday[1] = "monday";
			weekday[2] = "tuesday";
			weekday[3] = "wednesday";
			weekday[4] = "thursday";
			weekday[5] = "friday";
			weekday[6] = "saturday";

			return weekday[d.getDay()];
		},

		addTask() {
			this.newTask.time_slots = this.newTask.time*4;
			this.nameTraduction();
			this.save();
		},

		editTask() {
			this.newTask.time_slots = this.newTask.time*4;
			this.nameTraduction();
			this.save();
		},
		
		initializeTask(){
			this.reportDayWeek = this.getDayWeek(this.reportdate);
			this.week = this.getWeek(1,this.reportdate);
			this.expectedHours();

			this.newTask = {
				id: -1,
				user_id:this.user.id,
				created_at:this.reportdate,
				activity: "",
				project_id: "",
				project: "",
				group_id: "",
				group: "",
				category_id:"",
				category:"",
				absence_id: "",
				absence: "",
				training_type:"",
				time_slots: 0,
				time: 0,
				job_type:"",
				comments: "",
				pm_validation: 0,
				admin_validation: 0,
			};

			this.editIndex = -1;
		},
		
		refreshForm(){
			this.newTask = {
				id: this.newTask.id,
				user_id:this.user.id,
				created_at:this.reportdate,
				activity:this.newTask.activity,
				project_id: "",
				project: "",
				group_id: "",
				group: "",
				category_id:"",
				category:"",
				absence_id:"",
				absence:"",
				training_type: "",
				time_slots: 0,
				time:0,
				job_type:"",
				comments: "",
				pm_validation: this.newTask.pm_validation,
				admin_validation: this.newTask.admin_validation,
			};

			if (this.newTask.activity == 'training'){
				this.newTask.job_type = 'on site work';
			}

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

			this.groupList = [...setList];

		},

		categoriesLoad(){
			
			let vm = this;
			let setList = new Set();
			
			vm.categories.forEach(function(item) {						
				if( vm.user.id == item.user_id){
					 setList.add(item.description);
				}				
			});

			this.categoryList = [...setList];
		},

		nameTraduction(){

			this.newTask.project_id  = "";
			this.newTask.group_id    = "";
			this.newTask.category_id = "";
			this.newTask.absence_id  = "";

			//Ausencia
			if(this.newTask.activity == 'absence') {		
				for (let i = this.absences.length - 1; i >= 0; i--) {
					if(this.absences[i].name == this.newTask.absence){
						this.newTask.absence_id = this.absences[i].id;
					}
				}
			}
		
			//GrupoProyecto
			if(this.newTask.activity == 'project') {		
				for (var key = this.categories.length - 1; key >= 0; key--) {
					if(this.categories[key].description == this.newTask.category){
						this.newTask.category_id = this.categories[key].category_id;
					}
				}
				for (var key = this.groupProjects.length - 1; key >= 0; key--) {
					if(this.groupProjects[key].group == this.newTask.group && this.groupProjects[key].project == this.newTask.project){
						this.newTask.group_id    = this.groupProjects[key].group_id;
						this.newTask.project_id  = this.groupProjects[key].project_id;
					}
				}
			}

		},

		idTraduction(){

			this.newTask.project  = "";
			this.newTask.group    = "";
			this.newTask.category = "";
			this.newTask.absence  = "";

			//Ausencia
			if(this.newTask.activity =='absence') {		
				for (let i = this.absences.length - 1; i >= 0; i--) {
					if(this.newTask.absence_id == this.absences[i].id){
						this.newTask.absence = this.absences[i].name;
						break;
					}
				}
			}
		
			//GrupoProyecto
			if( this.newTask.activity == 'project' ) {		
				for (let key = this.categories.length - 1; key >= 0; key--) {
					if( this.newTask.category_id == this.categories[key].category_id ) {	
						this.newTask.category = this.categories[key].description;
						break;
					}
				}
				for (let key = this.groupProjects.length - 1; key >= 0; key--) {
					if( this.newTask.group_id == this.groupProjects[key].group_id ) {	
						this.newTask.group    = this.groupProjects[key].group;
						this.newTask.project  = this.groupProjects[key].project;
						break;
					}
				}
			}

		},

		fetchData() {
			let vm   = this;
			vm.tasks = [];

			vm.initializeTask();

			axios.get(vm.info.origin + vm.info.serverPath + '/api/reports', {
					params: {
						user_id: vm.user.id,
						created_at: vm.reportdate,
					}
				})
				.then(function (response) {
					vm.tasks = response.data;
					//console.log(response.data);
				})
				.catch(function (error) {
					//console.log(error);

					if(Array.isArray(error.response.data)) {
						error.response.data.forEach( (error) => {
							toastr.error(error);
						})
					}
					else {
						toastr.error(error.response.data);
					}
				});
		},

		save(){
			let vm = this;
			
			
			if(vm.newTask.id != -1) {
				axios.patch(vm.info.origin + vm.info.serverPath + '/api/reports/' + vm.newTask.id, vm.newTask)
				.then(function (response) {
					console.log(response.data);
					toastr.success(response.data);
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
			else{

				axios.post(vm.info.origin + vm.info.serverPath + '/api/reports', vm.newTask)
				.then(function (response) {
					console.log(response.data);
					toastr.success("Saved");
					//---------------------------------------
					vm.newTask.id = response.data;
					vm.tasks.push(vm.newTask);
					vm.initializeTask();	
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
				
			axios.delete(vm.info.origin + vm.info.serverPath + '/api/reports/' + vm.tasks[index].id)
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

		copyTasks(){
			let vm = this;

			axios.get(vm.info.origin + vm.info.serverPath + '/api/lastreport', {
				params: {
					user_id: vm.user.id,
					created_at: vm.reportdate,
				}
			})
			.then(function (response) {
				console.log(response.data);
				toastr.success(response.data);
				vm.fetchData();
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
	}
});

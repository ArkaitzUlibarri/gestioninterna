
/**
 * Registro los componentes necesarios.
 */
Vue.component('teleworking-template', require('../components/TeleworkingPeriod.vue'));

const app = new Vue({
	el: '#teleworking',

	data: {		
		url: url,

		contract: contract,
		daysWeek: [],
		editIndex: -1,
		newTeleworking: {
			id: -1,
			contract_id: -1,
			start_date: '',
			end_date: '',
			monday: 0,
			tuesday: 0,
			wednesday: 0,
			thursday: 0,
			friday: 0,
			saturday: 0,
			sunday: 0,
		},

		array: [],
	},


	computed: {
		formFilled(){
			if((this.newTeleworking.monday == 1 || 
				this.newTeleworking.tuesday   == 1 || 
				this.newTeleworking.wednesday == 1 || 
				this.newTeleworking.thursday  == 1 ||
				this.newTeleworking.friday    == 1 ||
				this.newTeleworking.saturday  == 1 ||
				this.newTeleworking.sunday    == 1) &&
				this.newTeleworking.start_date != ''){
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

		Event.$on('Edit', (index, item) => {
			this.newTeleworking = {
				id: item.id,
				contract_id: item.contract_id,
				start_date: item.start_date,
				end_date: item.end_date,
				monday: item.monday,
				tuesday: item.tuesday,
				wednesday: item.wednesday,
				thursday: item.thursday,
				friday: item.friday,
				saturday: item.saturday,
				sunday: item.sunday,
			};

			this.editIndex = index;

		});
	
	},

	mounted() {
		this.newTeleworking.contract_id = this.contract.id;
		this.setDateLimits();
		this.fetchData();
		this.daysWeek = daysWeek;
	},

	methods: {	
		setDateLimits(){
			document.getElementById("startdatefield").setAttribute("min", this.contract.start_date);
			document.getElementById("enddatefield").setAttribute("min", this.contract.start_date);

			if(this.contract.estimated_end_date != null){
				document.getElementById("startdatefield").setAttribute("max", this.contract.estimated_end_date);
				document.getElementById("enddatefield").setAttribute("max", this.contract.estimated_end_date);
			}
		},

		initialize(){
			this.newTeleworking = {
				id: -1,
				contract_id: this.contract.id,
				start_date: '',
				end_date: '',
				monday: 0,
				tuesday: 0,
				wednesday: 0,
				thursday: 0,
				friday: 0,
				saturday: 0,
				sunday: 0,
			};

			this.editIndex = -1;
		},

		fetchData(){
			let vm   = this;
			vm.array = [];

			vm.initialize();

			axios.get(vm.url + '/api/teleworking', {
					params: {
						id: vm.contract.id,
					}
				})
				.then(function (response) {
					vm.array = response.data;
				})
				.catch(function (error) {
					vm.showErrors(error.response.data)
				});
		},

		delete(index){

			let vm = this;
				
			axios.delete(vm.url + '/api/teleworking/' + vm.array[index].id)
				.then(function (response) {
					toastr.success(response.data);
				})
				.catch(function (error) {
					vm.showErrors(error.response.data)
				});	
		},

		save(){
			let vm = this;
			
			//CHANGE:ARRAY_MERGE
			if(vm.newTeleworking.id != -1) {
				axios.patch(vm.url +'/api/teleworking/' + vm.newTeleworking.id, {
						contract_start_date: vm.contract.start_date,
						contract_estimated_end_date: vm.contract.estimated_end_date,
						id: vm.newTeleworking.id,
						contract_id: vm.newTeleworking.contract_id,
						start_date: vm.newTeleworking.start_date,
						end_date: vm.newTeleworking.end_date,
						monday: vm.newTeleworking.monday,
						tuesday: vm.newTeleworking.tuesday,
						wednesday: vm.newTeleworking.wednesday,
						thursday: vm.newTeleworking.thursday,
						friday: vm.newTeleworking.friday,
						saturday: vm.newTeleworking.saturday,
						sunday: vm.newTeleworking.sunday,	
					})
					.then(function (response) {
						toastr.success(response.data);
						let properties = Object.keys(vm.newTeleworking);
						for (let i = properties.length - 1; i >= 0; i--) {
							vm.array[vm.editIndex][properties[i]] = vm.newTeleworking[properties[i]];
						}
						vm.initialize();
					})
					.catch(function (error) {
						vm.showErrors(error.response.data)
					});
				return;
			}
			else {
				axios.post(vm.url +'/api/teleworking',{
						contract_start_date: vm.contract.start_date,
						contract_estimated_end_date: vm.contract.estimated_end_date,
						id: vm.newTeleworking.id,
						contract_id: vm.newTeleworking.contract_id,
						start_date: vm.newTeleworking.start_date,
						end_date: vm.newTeleworking.end_date,
						monday: vm.newTeleworking.monday,
						tuesday: vm.newTeleworking.tuesday,
						wednesday: vm.newTeleworking.wednesday,
						thursday: vm.newTeleworking.thursday,
						friday: vm.newTeleworking.friday,
						saturday: vm.newTeleworking.saturday,
						sunday: vm.newTeleworking.sunday,			
					})
					.then(function (response) {
						toastr.success("Saved");
						vm.newTeleworking.id = response.data;
						vm.array.push(vm.newTeleworking);
						vm.initialize();	
					})
					.catch(function (error) {
						vm.showErrors(error.response.data)
					});	
			}	
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

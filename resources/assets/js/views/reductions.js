
/**
 * Registro los componentes necesarios.
 */
Vue.component('reduction-template', require('../components/Reduction.vue'));

const app = new Vue({
	el: '#reduction',

	data: {		
		url: url,

		contract: contract,
		editIndex: -1,
		newReduction: {	id: -1,	contract_id: -1, start_date: '', end_date: '', week_hours: 0 },
		array: [],
	},


	computed: {
		formFilled(){
			if(this.newReduction.week_hours != 0  && this.newReduction.start_date != ''){
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
			this.newReduction = {
				id: item.id,
				contract_id: item.contract_id,
				start_date: item.start_date,
				end_date: item.end_date,
				week_hours: item.week_hours
			};

			this.editIndex = index;

		});
	
	},

	mounted() {
		this.newReduction.contract_id = this.contract.id;
		this.setDateLimits();
		this.fetchData();
	},

	methods: {
		hoursValidation(){
			var hourfield = document.getElementById("hourfield").value;

			if(this.newReduction.week_hours >= this.contract.week_hours) {
				toastr.error("Reduction hours are greater than contract hours");
				this.newReduction.week_hours = 0;
			}
		},

		setDateLimits(){
			document.getElementById("startdatefield").setAttribute("min", this.contract.start_date);
			document.getElementById("enddatefield").setAttribute("min", this.contract.start_date);

			if(this.contract.estimated_end_date != null){
				document.getElementById("startdatefield").setAttribute("max", this.contract.estimated_end_date);
				document.getElementById("enddatefield").setAttribute("max", this.contract.estimated_end_date);
			}
		},

		initialize(){
			this.newReduction = {
				id: -1,
				contract_id: this.contract.id,
				start_date: '',
				end_date: '',
				week_hours: 0,
			};

			this.editIndex = -1;
		},

		fetchData(){
			let vm   = this;
			vm.array = [];

			vm.initialize();

			axios.get(vm.url + '/api/reductions', {
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
				
			axios.delete(vm.url + '/api/reductions/' + vm.array[index].id)
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
			if(vm.newReduction.id != -1) {
				axios.patch(vm.url + '/api/reductions/' + vm.newReduction.id, {
						contract_start_date: vm.contract.start_date,
						contract_estimated_end_date: vm.contract.estimated_end_date,
						id: vm.newReduction.id,
						contract_id: vm.newReduction.contract_id,
						start_date: vm.newReduction.start_date,
						end_date: vm.newReduction.end_date,
						week_hours: vm.newReduction.week_hours,
					})
					.then(function (response) {
						toastr.success(response.data);
						let properties = Object.keys(vm.newReduction);

						for (let i = properties.length - 1; i >= 0; i--) {
							vm.array[vm.editIndex][properties[i]] = vm.newReduction[properties[i]];
						}
						vm.initialize();
					})
					.catch(function (error) {
						vm.showErrors(error.response.data)
					});
				return;
			}
			else{

				axios.post(vm.url + '/api/reductions',{
						contract_start_date: vm.contract.start_date,
						contract_estimated_end_date: vm.contract.estimated_end_date,
						id: vm.newReduction.id,
						contract_id: vm.newReduction.contract_id,
						start_date: vm.newReduction.start_date,
						end_date: vm.newReduction.end_date,
						week_hours: vm.newReduction.week_hours,
					})
					.then(function (response) {
						toastr.success("Saved");
						vm.newReduction.id = response.data;
						vm.array.push(vm.newReduction);
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

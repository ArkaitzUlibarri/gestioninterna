
/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


/**
 * Registro los componentes necesarios.
 */
Vue.component('category-template', require('../components/Category.vue'));

const app = new Vue({

	el: '#categories',

	data: {		

		user: user,
		categories: categories,
		categoryList: [] ,

		newCategory: {
			id: -1,
			user_id: -1,
			category_id: -1,
			category: '',
		},

		array: [],
	},


	computed: {
		formFilled(){
			if(this.newCategory.category != ''){
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
		this.newCategory.user_id = this.user.id;
		this.fetchData();
	},

	methods: {

		initialize(){
			
			this.newCategory = {
				id: -1,
				user_id: -1,
				category_id: -1,
				category: '',
			};

		},

		refreshCategory() {
			let setList = new Set();
			let vm = this;

			vm.categories.forEach(function(category) {
				setList.add(category.name + " - " + category.description);
			});

			vm.array.forEach(function(item) {
				setList.delete(item.category);
			});
			
			vm.categoryList = [...setList];
		},
	
		nameTraduction(){	
			//Category
			for (var key = this.categories.length - 1; key >= 0; key--) {
				if((this.categories[key].name +" - "+ this.categories[key].description) == this.newCategory.category){
					this.newCategory.category_id    = this.categories[key].id;
				}
			}

		},
		
		saveCategory(){
			this.nameTraduction();
			this.save();
		},

		fetchData(){
			let vm   = this;
			vm.array = [];

			axios.get('/api/categories', {
				params: {
					id: vm.user.id,
				}
			})
			.then(function (response) {
				vm.array = response.data;
				//console.log(response.data);
				vm.refreshCategory();
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
				
			axios.delete('/api/categories/' + vm.array[index].id)
			.then(function (response) {
				console.log(response.data);
				toastr.success(response.data);
				vm.refreshCategory();
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

			axios.post('/api/categories',vm.newCategory)
			.then(function (response) {
				console.log(response.data);
				toastr.success("Saved");
				//---------------------------------------
				vm.newCategory.id = response.data;
				vm.array.push(vm.newCategory);		
				vm.refreshCategory();
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

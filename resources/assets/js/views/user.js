
const app = new Vue({
	el: '#app',

	data: {
		url: url,

		user: user,
		roleBackup: user.role,
		role: user.role,

		idxCategory: -1,
		categories: [],
		categoryList: categories,
	},

	mounted() {
		this.fetchUserCategories();
	},

	methods: {
		/**
		 * Añado una categoria al usuario
		 */
		addCategory() {
			if (this.idxCategory == -1) return;

			let category = this.categoryList[this.idxCategory];
			category = { id: '', category_id: category.id, category: category.name + ' - ' + category.description};
			
			if (this.hasCategory(category)) {
				this.showErrors('The user already has the category.');
				return;
			}

			this.saveCategory(category);
		},

		/**
		 * Filtra las categorias del usuario
		 */
		fetchUserCategories(){
			let vm   = this;
			vm.categories = [];

			axios.get(vm.url + '/api/categories?id=' + vm.user.id)
				.then(function (response) {
					vm.categories = response.data;
				})
				.catch(function (error) {
					vm.showErrors(error.response.data);
				});
		},

		/**
		 * Guarda la nueva categoria del usuario
		 */
		saveCategory(category){
			let vm = this;

			axios.post(vm.url + '/api/categories', {
					user_id: vm.user.id,
					category_id: category.category_id
				})
				.then(function (response) {
					category.id = response.data;
					vm.categories.push(category);
					vm.idxCategory = -1;
				})
				.catch(function (error) {
					vm.showErrors(error.response.data);
				});	
		},

		/**
		 * Update user's role
		 */
		updateRole(role) {
			let vm = this;
			
			axios.patch(vm.url + '/api/users/' + vm.user.id, {
					role: vm.role
				})
				.then(function (response) {
					vm.roleBackup = vm.role;
				})
				.catch(function (error) {
					vm.showErrors(error.response.data);
					vm.role = vm.roleBackup;
				});	
		},

		/**
		 * Borra la categoria del usuario
		 */
		deleteCategory(id) {
			let vm = this;

			axios.delete(vm.url + '/api/categories/' + id)
				.then(function (response) {
					vm.deleteCategoryFromArray(id);
				})
				.catch(function (error) {
					vm.showErrors(error.response.data);
				});
		},

		/**
		 * Compruebo si el usuario dispone de la categoria
		 */
		hasCategory(category) {
			for (let i=this.categories.length-1; i>=0; i--) {
				if(this.categories[i]['category_id'] == category['category_id']) {
					return true;
				}
			}
			return false;
		},

		/**
		 * Borro la categoria del array de categorias del usuario
		 */
		deleteCategoryFromArray(id) {
			for (let i=this.categories.length-1; i>=0; i--) {
				if(this.categories[i]['id'] == id) {
					this.categories.splice(i, 1);
					return;
				}
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
	},
})
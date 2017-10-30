webpackJsonp([8],{

/***/ 146:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(147);


/***/ }),

/***/ 147:
/***/ (function(module, exports) {


var app = new Vue({
	el: '#app',

	data: {
		url: url,

		user: user,
		roleBackup: user.role,
		role: user.role,

		idxCategory: -1,
		categories: [],
		categoryList: categories
	},

	mounted: function mounted() {
		this.fetchUserCategories();
	},


	methods: {
		/**
   * Añado una categoria al usuario
   */
		addCategory: function addCategory() {
			if (this.idxCategory == -1) return;

			var category = this.categoryList[this.idxCategory];
			category = { id: '', category_id: category.id, category: category.name + ' - ' + category.description };

			if (this.hasCategory(category)) {
				this.showErrors('The user already has the category.');
				return;
			}

			this.saveCategory(category);
		},


		/**
   * Filtra las categorias del usuario
   */
		fetchUserCategories: function fetchUserCategories() {
			var vm = this;
			vm.categories = [];

			axios.get(vm.url + '/api/categories?id=' + vm.user.id).then(function (response) {
				vm.categories = response.data;
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},


		/**
   * Guarda la nueva categoria del usuario
   */
		saveCategory: function saveCategory(category) {
			var vm = this;

			axios.post(vm.url + '/api/categories', {
				user_id: vm.user.id,
				category_id: category.category_id
			}).then(function (response) {
				category.id = response.data;
				vm.categories.push(category);
				vm.idxCategory = -1;
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},


		/**
   * Update user's role
   */
		updateRole: function updateRole(role) {
			var vm = this;

			axios.patch(vm.url + '/api/users/' + vm.user.id, {
				role: vm.role
			}).then(function (response) {
				vm.roleBackup = vm.role;
			}).catch(function (error) {
				vm.showErrors(error.response.data);
				vm.role = vm.roleBackup;
			});
		},


		/**
   * Borra la categoria del usuario
   */
		deleteCategory: function deleteCategory(id) {
			var vm = this;

			axios.delete(vm.url + '/api/categories/' + id).then(function (response) {
				vm.deleteCategoryFromArray(id);
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},


		/**
   * Compruebo si el usuario dispone de la categoria
   */
		hasCategory: function hasCategory(category) {
			for (var i = this.categories.length - 1; i >= 0; i--) {
				if (this.categories[i]['category_id'] == category['category_id']) {
					return true;
				}
			}
			return false;
		},


		/**
   * Borro la categoria del array de categorias del usuario
   */
		deleteCategoryFromArray: function deleteCategoryFromArray(id) {
			for (var i = this.categories.length - 1; i >= 0; i--) {
				if (this.categories[i]['id'] == id) {
					this.categories.splice(i, 1);
					return;
				}
			}
		},


		/**
   * Visualizo mensajes de error
   */
		showErrors: function showErrors(errors) {
			if (Array.isArray(errors)) {
				errors.forEach(function (error) {
					toastr.error(error);
				});
			} else {
				toastr.error(errors);
			}
		}
	}
});

/***/ })

},[146]);
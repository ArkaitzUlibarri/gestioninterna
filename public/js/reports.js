webpackJsonp([1],{

/***/ 13:
/***/ (function(module, exports, __webpack_require__) {

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

/**
 * First we will load all of this project_id's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('../bootstrap');


/**
 * Registro los componentes necesarios.
 */
Vue.component('task-template', __webpack_require__(36));

var app = new Vue({

	el: '#report',

	data: {
		user: user,
		role: role,
		reportdate: reportdate,

		categories: categories,
		groupProjects: groupProjects,
		absences: absences,

		projectList: [],
		groupList: [],
		categoryList: [],

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
			absence_id: "",
			absence: "",
			training_type: "",
			time_slots: 0,
			time: 0,
			job_type: "",
			comments: "",
			pm_validation: 0,
			admin_validation: 0
		},

		tasks: []
	},

	computed: {
		totalTime: function totalTime() {
			var total = 0;

			if (this.tasks.length > 0) {
				this.tasks.forEach(function (item) {
					total += parseInt(item.time_slots) * 0.25;
				});
			}

			return total;
		},
		taskValidated: function taskValidated() {
			if (this.newTask.activity != "" && this.newTask.time != 0) {
				if (this.newTask.activity == 'project' && this.newTask.project != "" && this.newTask.group != "" && this.newTask.job_type != "") {
					return true;
				}
				if (this.newTask.activity == 'absence' && this.newTask.absence != "") {
					return true;
				}
				if (this.newTask.activity == 'training' && this.newTask.training_type != "" && this.newTask.job_type != "") {
					return true;
				}
			}

			return false;
		}
	},

	created: function created() {
		var _this = this;

		Event.$on('DeleteTask', function (index, task) {
			_this.delete(index);
			_this.tasks.splice(index, 1);
		});

		Event.$on('EditTask', function (index, task) {
			_this.newTask = {
				id: task.id,
				user_id: _this.user,
				created_at: _this.reportdate,
				activity: task.activity,
				project_id: task.project_id,
				project: task.project,
				group_id: task.group_id,
				group: task.group,
				absence_id: task.absence_id,
				absence: task.absence,
				training_type: task.training_type,
				time_slots: task.time_slots,
				time: parseInt(task.time_slots) * 0.25,
				job_type: task.job_type,
				comments: task.comments,
				pm_validation: task.pm_validation,
				admin_validation: task.admin_validation
			};

			_this.idTraduction();
			_this.groupsRefresh();
			_this.categoriesRefresh();

			_this.editIndex = index;
		});
	},
	mounted: function mounted() {
		this.fetchData();
		this.project();
	},


	methods: {
		addTask: function addTask() {
			this.newTask.time_slots = this.newTask.time * 4;
			this.nameTraduction();
			this.categoriesRefresh();
			this.save();
		},
		editTask: function editTask() {
			this.newTask.time_slots = this.newTask.time * 4;
			this.save();
		},
		initializeTask: function initializeTask() {
			this.newTask = {
				id: -1,
				user_id: this.user,
				created_at: this.reportdate,
				activity: "",
				project_id: "",
				project: "",
				group_id: "",
				group: "",
				absence_id: "",
				absence: "",
				training_type: "",
				time_slots: 0,
				time: 0,
				job_type: "",
				comments: "",
				pm_validation: 0,
				admin_validation: 0
			};
		},
		refreshForm: function refreshForm() {
			this.newTask = {
				id: this.newTask.id,
				user_id: this.user,
				created_at: this.reportdate,
				activity: this.newTask.activity,
				project_id: "",
				project: "",
				group_id: "",
				group: "",
				absence_id: "",
				absence: "",
				training_type: "",
				time_slots: 0,
				time: 0,
				job_type: "",
				comments: "",
				pm_validation: 0,
				admin_validation: 0
			};

			if (this.newTask.activity == 'training') {
				this.newTask.job_type = 'on site work';
			}
		},
		project: function project() {
			var setList = new Set();

			this.groupProjects.forEach(function (item) {
				setList.add(item.project);
			});

			this.projectList = [].concat(_toConsumableArray(setList));
		},
		groupsRefresh: function groupsRefresh() {
			var vm = this;
			var setList = new Set();

			vm.groupProjects.forEach(function (item) {
				if (vm.newTask.project == item.project) {
					setList.add(item.group);
				}
			});

			this.groupList = [].concat(_toConsumableArray(setList));
		},
		categoriesRefresh: function categoriesRefresh() {
			var vm = this;
			var setList = new Set();

			vm.categories.forEach(function (item) {
				if (vm.newTask.group_id == item.group_id) {
					setList.add(item.name);
				}
			});

			this.categoryList = [].concat(_toConsumableArray(setList));
		},
		validateTask: function validateTask() {

			if (confirm("¿Estás seguro de que quieres validar el día?")) {
				if (this.role == 'admin') {
					this.tasks.forEach(function (item) {
						if (item.pm_validation == 1) {
							item.admin_validation = 1;
						}
					});
				} else {
					this.tasks.forEach(function (item) {
						item.pm_validation = 1;
					});
				}
				this.initializeTask();
			}
		},
		nameTraduction: function nameTraduction() {

			this.newTask.project_id = "";
			this.newTask.group_id = "";
			this.newTask.absence_id = "";

			//Ausencia
			if (this.newTask.activity == 'absence') {
				for (var i = this.absences.length - 1; i >= 0; i--) {
					if (this.absences[i].name == this.newTask.absence) {
						this.newTask.absence_id = this.absences[i].id;
					}
				}
			}

			//GrupoProyecto
			if (this.newTask.activity == 'project') {
				for (var key = this.groupProjects.length - 1; key >= 0; key--) {
					if (this.groupProjects[key].group == this.newTask.group) {
						this.newTask.group_id = this.groupProjects[key].group_id;
						this.newTask.project_id = this.groupProjects[key].project_id;
					}
				}
			}
		},
		idTraduction: function idTraduction() {

			this.newTask.project = "";
			this.newTask.group = "";
			this.newTask.absence = "";

			//Ausencia
			if (this.newTask.activity == 'absence') {
				for (var i = this.absences.length - 1; i >= 0; i--) {
					if (this.newTask.absence_id == this.absences[i].id) {
						this.newTask.absence = this.absences[i].name;
						break;
					}
				}
			}

			//GrupoProyecto
			if (this.newTask.activity == 'project') {
				for (var key = this.groupProjects.length - 1; key >= 0; key--) {
					if (this.newTask.group_id == this.groupProjects[key].group_id) {
						this.newTask.group = this.groupProjects[key].group;
						this.newTask.project = this.groupProjects[key].project;
						break;
					}
				}
			}
		},
		fetchData: function fetchData() {
			var vm = this;
			vm.tasks = [];

			vm.initializeTask();

			axios.get('/api/reports', {
				params: {
					user_id: vm.user,
					created_at: vm.reportdate
				}
			}).then(function (response) {
				vm.tasks = response.data;
				console.log(response.data);
			}).catch(function (error) {
				console.log(error);
			});
		},
		save: function save() {
			var vm = this;

			if (vm.newTask.id != -1) {
				axios.patch('/api/reports/' + vm.newTask.id, vm.newTask).then(function (response) {
					console.log(response.data);

					var properties = Object.keys(vm.newTask);

					for (var i = properties.length - 1; i >= 0; i--) {
						vm.tasks[vm.editIndex][properties[i]] = vm.newTask[properties[i]];
					}

					vm.initializeTask();

					vm.editIndex = -1;
				}).catch(function (error) {
					console.log(error);
				});
				return;
			} else {

				axios.post('/api/reports', vm.newTask).then(function (response) {
					console.log(response.data);
					vm.newTask.id = response.data;
					vm.tasks.push(vm.newTask);
					vm.initializeTask();
				}).catch(function (error) {
					console.log(error);
				});
				return;
			}
		},
		delete: function _delete(index) {
			var vm = this;

			axios.delete('/api/reports/' + vm.tasks[index].id).then(function (response) {
				console.log(response.data);
			}).catch(function (error) {
				console.log(error);
			});
		}
	}
});

/***/ }),

/***/ 32:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    template: '#task-template',

    props: ['task', 'index'],

    computed: {
        time: function time() {
            return parseInt(this.task.time_slots) * 0.25;
        }
    },

    methods: {
        deleteTask: function deleteTask() {
            Event.$emit('DeleteTask', this.index, this.task);
        },
        editTask: function editTask() {
            Event.$emit('EditTask', this.index, this.task);
        }
    }

});

/***/ }),

/***/ 34:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(35)();
exports.push([module.i, "\n.task-panel {\n    position:relative;\n    margin-bottom: .5em;\n    background-color: #fff;\n    border: 1px solid #777777;\n    border-radius: 0px;\n    box-shadow: 0 3px 1px rgba(0, 0, 0, .05);\n    padding: .7em;\n}\n.panel-right-corner {\n    position: absolute;\n    top: .4em;\n    right: 1em;\n}\n.task-action-icon {\n    font-weight: bold;\n    cursor: pointer;\n    display: block;\n    margin: auto ;\n}\n.validated-task {\n    border-style: double;\n    border-color: #21d421;\n}\n.validated-pm-text {\n    color: #98FB98;\n}\n.validated-admin-text {\n    color: #21d421;\n}\n", ""]);

/***/ }),

/***/ 35:
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function() {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		var result = [];
		for(var i = 0; i < this.length; i++) {
			var item = this[i];
			if(item[2]) {
				result.push("@media " + item[2] + "{" + item[1] + "}");
			} else {
				result.push(item[1]);
			}
		}
		return result.join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};


/***/ }),

/***/ 36:
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(39)

var Component = __webpack_require__(37)(
  /* script */
  __webpack_require__(32),
  /* template */
  __webpack_require__(38),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/home/vagrant/Code/gestioninterna/resources/assets/js/components/Task.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Task.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-cec93342", Component.options)
  } else {
    hotAPI.reload("data-v-cec93342", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 37:
/***/ (function(module, exports) {

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  scopeId,
  cssModules
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  // inject cssModules
  if (cssModules) {
    var computed = options.computed || (options.computed = {})
    Object.keys(cssModules).forEach(function (key) {
      var module = cssModules[key]
      computed[key] = function () { return module }
    })
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 38:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "task-panel",
    class: {
      'validated-task': _vm.task.pm_validation
    }
  }, [_c('div', {
    staticClass: "panel-right-corner"
  }, [(_vm.task.pm_validation) ? _c('div', {
    staticClass: "task-action-icon validated-pm-text"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-ok",
    attrs: {
      "aria-hidden": "true"
    }
  })]) : _vm._e(), _vm._v(" "), (_vm.task.admin_validation) ? _c('div', {
    staticClass: "task-action-icon validated-admin-text"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-ok",
    attrs: {
      "aria-hidden": "true"
    }
  })]) : _vm._e(), _vm._v(" "), (!_vm.task.pm_validation) ? _c('div', {
    staticClass: "task-action-icon ",
    on: {
      "click": _vm.deleteTask
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-trash",
    attrs: {
      "aria-hidden": "true"
    }
  })]) : _vm._e(), _vm._v(" "), (!_vm.task.pm_validation) ? _c('div', {
    staticClass: "task-action-icon",
    on: {
      "click": _vm.editTask
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-edit",
    attrs: {
      "aria-hidden": "true"
    }
  })]) : _vm._e()]), _vm._v(" "), (_vm.task.activity == 'absence') ? _c('span', [_c('h4', [_c('b', [_vm._v(" " + _vm._s(_vm.time) + " ")]), _vm._v(" " + _vm._s('ABSENCE \\ ' + _vm.task.absence.toUpperCase()) + "  ")]), _vm._v(" "), _c('p', [_vm._v(_vm._s(_vm.task.comments))])]) : _vm._e(), _vm._v(" "), (_vm.task.activity == 'project') ? _c('span', [_c('h4', [_c('b', [_vm._v(" " + _vm._s(_vm.time) + " ")]), _vm._v(" " + _vm._s(_vm.task.project.toUpperCase() + ' \\ ' + _vm.task.group.toUpperCase()) + "  ")]), _vm._v(" "), _c('p', [_vm._v(_vm._s(_vm.task.comments))])]) : _vm._e(), _vm._v(" "), (_vm.task.activity == 'training') ? _c('span', [_c('h4', [_c('b', [_vm._v(" " + _vm._s(_vm.time) + " ")]), _vm._v("  " + _vm._s('TRAINING \\ ' + _vm.task.training_type.toUpperCase()) + "  ")]), _vm._v(" "), _c('p', [_vm._v(_vm._s(_vm.task.comments))])]) : _vm._e()])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-cec93342", module.exports)
  }
}

/***/ }),

/***/ 39:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(34);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(40)("5d8786ba", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-cec93342!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Task.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-cec93342!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Task.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 40:
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(41)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction) {
  isProduction = _isProduction

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[data-vue-ssr-id~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),

/***/ 41:
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),

/***/ 45:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(13);


/***/ })

},[45]);
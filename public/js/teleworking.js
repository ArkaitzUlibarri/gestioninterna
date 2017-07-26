webpackJsonp([3],{

/***/ 150:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(151);


/***/ }),

/***/ 151:
/***/ (function(module, exports, __webpack_require__) {


/**
 * Registro los componentes necesarios.
 */
Vue.component('teleworking-template', __webpack_require__(152));

var app = new Vue({
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
			sunday: 0
		},

		array: []
	},

	computed: {
		formFilled: function formFilled() {
			if ((this.newTeleworking.monday == 1 || this.newTeleworking.tuesday == 1 || this.newTeleworking.wednesday == 1 || this.newTeleworking.thursday == 1 || this.newTeleworking.friday == 1 || this.newTeleworking.saturday == 1 || this.newTeleworking.sunday == 1) && this.newTeleworking.start_date != '') {
				return true;
			} else {
				return false;
			}
		}
	},

	created: function created() {
		var _this = this;

		Event.$on('Delete', function (index, item) {
			if (confirm("You are going to delete this entry,are you sure?")) {
				_this.delete(index);
				_this.array.splice(index, 1);
			}
		});

		Event.$on('Edit', function (index, item) {
			_this.newTeleworking = {
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
				sunday: item.sunday
			};

			_this.editIndex = index;
		});
	},
	mounted: function mounted() {
		this.newTeleworking.contract_id = this.contract.id;
		this.setDateLimits();
		this.fetchData();
		this.daysWeek = daysWeek;
	},


	methods: {
		setDateLimits: function setDateLimits() {
			document.getElementById("startdatefield").setAttribute("min", this.contract.start_date);
			document.getElementById("enddatefield").setAttribute("min", this.contract.start_date);

			if (this.contract.estimated_end_date != null) {
				document.getElementById("startdatefield").setAttribute("max", this.contract.estimated_end_date);
				document.getElementById("enddatefield").setAttribute("max", this.contract.estimated_end_date);
			}
		},
		initialize: function initialize() {
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
				sunday: 0
			};

			this.editIndex = -1;
		},
		fetchData: function fetchData() {
			var vm = this;
			vm.array = [];

			vm.initialize();

			axios.get(vm.url + '/api/teleworking', {
				params: {
					id: vm.contract.id
				}
			}).then(function (response) {
				vm.array = response.data;
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},
		delete: function _delete(index) {

			var vm = this;

			axios.delete(vm.url + '/api/teleworking/' + vm.array[index].id).then(function (response) {
				toastr.success(response.data);
			}).catch(function (error) {
				vm.showErrors(error.response.data);
			});
		},
		save: function save() {
			var vm = this;

			//CHANGE:ARRAY_MERGE
			if (vm.newTeleworking.id != -1) {
				axios.patch(vm.url + '/api/teleworking/' + vm.newTeleworking.id, {
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
					sunday: vm.newTeleworking.sunday
				}).then(function (response) {
					toastr.success(response.data);
					var properties = Object.keys(vm.newTeleworking);
					for (var i = properties.length - 1; i >= 0; i--) {
						vm.array[vm.editIndex][properties[i]] = vm.newTeleworking[properties[i]];
					}
					vm.initialize();
				}).catch(function (error) {
					vm.showErrors(error.response.data);
				});
				return;
			} else {
				axios.post(vm.url + '/api/teleworking', {
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
					sunday: vm.newTeleworking.sunday
				}).then(function (response) {
					toastr.success("Saved");
					vm.newTeleworking.id = response.data;
					vm.array.push(vm.newTeleworking);
					vm.initialize();
				}).catch(function (error) {
					vm.showErrors(error.response.data);
				});
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

/***/ }),

/***/ 152:
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(153)

var Component = __webpack_require__(5)(
  /* script */
  __webpack_require__(155),
  /* template */
  __webpack_require__(156),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/home/vagrant/Code/gestioninterna/resources/assets/js/components/TeleworkingPeriod.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] TeleworkingPeriod.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-8da1b404", Component.options)
  } else {
    hotAPI.reload("data-v-8da1b404", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 153:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(154);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(3)("c45ca21e", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-8da1b404!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./TeleworkingPeriod.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-8da1b404!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./TeleworkingPeriod.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 154:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(2)();
exports.push([module.i, "\n.item-panel {\n    position:relative;\n    border-bottom: 1px solid #ccc;\n    padding: .4em;\n    //margin-bottom: .5em;\n}\n.panel-right-corner {\n    position: absolute;\n    right: 2em;\n    top:1em;\n}\n.action {\n    cursor: pointer;\n    //display: block;\n    //margin: auto ;\n}\n.selected-item {\n    background-color: lightblue;\n}\n\n", ""]);

/***/ }),

/***/ 155:
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

/* harmony default export */ __webpack_exports__["default"] = ({
    template: '#teleworking-template',

    props: ['item', 'index', 'prop'],

    methods: {
        edititem: function edititem() {
            Event.$emit('Edit', this.index, this.item);
        },
        deleteitem: function deleteitem() {
            Event.$emit('Delete', this.index, this.item);
        }
    }
});

/***/ }),

/***/ 156:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-xs-12"
  }, [_c('div', {
    staticClass: "item-panel col-sm-12",
    class: {
      'selected-item': _vm.prop
    }
  }, [_c('h5', {
    staticClass: "action",
    on: {
      "click": _vm.edititem
    }
  }, [_c('b', [_vm._v("From " + _vm._s(_vm.item.start_date) + " "), (_vm.item.end_date) ? _c('span', [_vm._v("to " + _vm._s(_vm.item.end_date))]) : _vm._e(), _vm._v(" | ")]), _vm._v(" "), _c('label', [(_vm.item.monday) ? _c('span', [_vm._v("Monday |")]) : _vm._e(), _vm._v(" "), (_vm.item.tuesday) ? _c('span', [_vm._v("Tuesday |")]) : _vm._e(), _vm._v(" "), (_vm.item.wednesday) ? _c('span', [_vm._v("Wednesday |")]) : _vm._e(), _vm._v(" "), (_vm.item.thursday) ? _c('span', [_vm._v("Thursday |")]) : _vm._e(), _vm._v(" "), (_vm.item.friday) ? _c('span', [_vm._v("Friday |")]) : _vm._e(), _vm._v(" "), (_vm.item.saturday) ? _c('span', [_vm._v("Saturday |")]) : _vm._e(), _vm._v(" "), (_vm.item.sunday) ? _c('span', [_vm._v("Sunday |")]) : _vm._e()])]), _vm._v(" "), _c('div', {
    staticClass: "panel-right-corner"
  }, [_c('div', {
    staticClass: "action",
    on: {
      "click": _vm.deleteitem
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-trash",
    attrs: {
      "aria-hidden": "true"
    }
  })])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-8da1b404", module.exports)
  }
}

/***/ }),

/***/ 2:
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

/***/ 3:
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

var listToStyles = __webpack_require__(4)

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

/***/ 4:
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

/***/ 5:
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


/***/ })

},[150]);
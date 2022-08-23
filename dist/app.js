/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./includes/Js/Ajax.js":
/*!*****************************!*\
  !*** ./includes/Js/Ajax.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Ajax)
/* harmony export */ });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var AjaxObject = /*#__PURE__*/function () {
  function AjaxObject() {
    _classCallCheck(this, AjaxObject);

    this.options = {
      url: null,
      form: null,
      errors: null,
      done: null,
      fail: function (errors) {
        this.setErrorsText(errors.join("<br>"));
      }.bind(this)
    };
  }

  _createClass(AjaxObject, [{
    key: "url",
    value: function url(_url) {
      this.options.url = _url;
      return this;
    }
  }, {
    key: "errors",
    value: function errors(selector) {
      this.options.errors = selector;
      return this;
    }
  }, {
    key: "form",
    value: function form(selector) {
      this.options.form = selector;
      return this;
    }
  }, {
    key: "done",
    value: function done(callback) {
      this.options.done = callback;
      return this;
    }
  }, {
    key: "fail",
    value: function fail(callback) {
      this.options.fail = callback;
      return this;
    }
  }, {
    key: "setErrorsText",
    value: function setErrorsText(text) {
      if (this.options.errors) {
        if (text) {
          $(this.options.errors).html(text).show();
        } else {
          $(this.options.errors).html("").hide();
        }
      }
    }
  }, {
    key: "run",
    value: function run() {
      this.setErrorsText("");
      $.ajax({
        url: this.options.url,
        data: this.options.form ? new FormData($(this.options.form).get(0)) : {},
        processData: false,
        contentType: false,
        type: 'POST'
      }).done(function (json) {
        if (json.success) {
          if (this.options.done) {
            this.options.done(json.data);
          }
        } else {
          this.options.fail($.isPlainObject(json) ? json.errors : [json]);
        }
      }.bind(this));
      return this;
    }
  }]);

  return AjaxObject;
}();

var Ajax = /*#__PURE__*/function () {
  function Ajax() {
    _classCallCheck(this, Ajax);
  }

  _createClass(Ajax, null, [{
    key: "create",
    value: function create() {
      return new AjaxObject();
    }
  }]);

  return Ajax;
}();


;

/***/ }),

/***/ "./includes/Js/Documents.js":
/*!**********************************!*\
  !*** ./includes/Js/Documents.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Documents)
/* harmony export */ });
/* harmony import */ var _Ajax__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ajax */ "./includes/Js/Ajax.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }



var Documents = /*#__PURE__*/function () {
  function Documents() {
    _classCallCheck(this, Documents);
  }

  _createClass(Documents, null, [{
    key: "edit",
    value: function edit() {}
  }, {
    key: "new",
    value: function _new() {
      $('.js-add-document-modal').modal({
        closable: false
      }).modal('show');
      $("input[name=create_date]").closest(".ui.calendar").calendar({
        type: "date",
        dateFormat: "yyyy-mm-dd"
      });
    }
  }, {
    key: "reloadDocuments",
    value: function reloadDocuments() {}
  }, {
    key: "submit",
    value: function submit() {
      _Ajax__WEBPACK_IMPORTED_MODULE_0__["default"].create().url(Urls.AddDocument).form(".js-add-document-modal form").errors(".js-add-document-modal .js-errors").done(function (documentID) {
        this.close();
        this.reloadDocuments();
      }.bind(this)).run();
    }
  }, {
    key: "close",
    value: function close() {
      $('.js-add-document-modal').modal("hide");
      $('.js-add-document-modal form').get(0).reset();
      $("input[name=create_date]").closest(".ui.calendar").calendar("clear");
      $(".js-add-document-modal .js-errors").hide();
    }
  }, {
    key: "setup",
    value: function setup() {
      $(".js-add-document").click(this["new"]);
      $(".js-add-document-modal .js-submit").click(this.submit);
      $(".js-add-document-modal .js-close").click(this.close);
    }
  }]);

  return Documents;
}();


;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!****************************!*\
  !*** ./includes/Js/app.js ***!
  \****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Documents__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Documents */ "./includes/Js/Documents.js");

_Documents__WEBPACK_IMPORTED_MODULE_0__["default"].setup();
})();

/******/ })()
;
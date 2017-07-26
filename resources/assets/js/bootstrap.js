


/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap-sass');
} catch (e) {}


/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require('vue');
//Vue.use(require('vue-full-calendar'));

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Loading from './core/Loading';

// Add a request interceptor
window.axios.interceptors.request.use(function (config) {
		Loading.show();
		return config;
	}, function (error) {
		return Promise.reject(error);
	});

// Add a response interceptor
window.axios.interceptors.response.use(function (response) {
		Loading.hide();
		return response;
	}, function (error) {
		Loading.hide();
		return Promise.reject(error);
	});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
// 

/**
 * Moment
 */

window.moment = require('moment');
window.moment.locale('es');

/**
 * toastr is a Javascript library for non-blocking notifications. jQuery is required.
 */

window.toastr = require('toastr');
toastr.options.timeOut = 3000;
toastr.options.newestOnTop = true;
toastr.options.progressBar = false;
toastr.options.positionClass = 'toast-bottom-right';
toastr.options.preventDuplicates = true;

/**
 * Instancia de Vue para la gestion de eventos.
 */
window.Event = new Vue();
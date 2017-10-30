const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/views/projects.js', 'public/js')
   .js('resources/assets/js/views/workingreports.js', 'public/js')
   .js('resources/assets/js/views/user.js', 'public/js')
   .js('resources/assets/js/views/day_validation.js', 'public/js')
   .js('resources/assets/js/views/teleworking.js', 'public/js')
   .js('resources/assets/js/views/reductions.js', 'public/js')
   .js('resources/assets/js/views/groupsUser.js', 'public/js')
   //.js('resources/assets/js/views/categories.js', 'public/js')
   .js('resources/assets/js/views/week_validation.js', 'public/js')
   .js('resources/assets/js/views/calendar.js', 'public/js')
   .js('resources/assets/js/views/performance.js', 'public/js')
   .js('resources/assets/js/bootstrap.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .extract([
   		'jquery',
   		'vue',
   		'axios'
   	]);

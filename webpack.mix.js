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
   .js('resources/assets/js/views/reports.js', 'public/js')
   .js('resources/assets/js/views/validate.js', 'public/js')
   .js('resources/assets/js/views/teleworking.js', 'public/js')
   .js('resources/assets/js/views/reductions.js', 'public/js')
   .js('resources/assets/js/bootstrap.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .extract([
   		'jquery',
   		'vue',
   		'axios'
   	]);

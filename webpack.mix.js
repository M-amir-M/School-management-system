const {mix} = require('laravel-mix');

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

mix.js([
    'resources/assets/js/package/persian-date-0.1.8.min.js',
    'resources/assets/js/package/persian-datepicker-0.4.5.min.js',
    'resources/assets/js/app.js',
    'resources/assets/js/custom.js',
    ], 'public/js/app.js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.combine([
    'resources/assets/css/persian-datepicker-0.4.5.min.css',
    'resources/assets/css/admin-panel-style.css',
    'resources/assets/css/font-awesome.min.css',
    'resources/assets/css/simple-line-icons.min.css',
    'resources/assets/css/style.css',
], 'public/css/vendor.css');
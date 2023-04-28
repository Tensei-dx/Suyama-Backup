let mix = require('laravel-mix');

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

 /*
use if run watch isn't working
node_modules/.bin/webpack --watch --watch-poll --config=node_modules/laravel-mix/setup/webpack.config.js
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'resources/assets/css')
    .styles(['public/resources/assets/css/app.css',
            'resources/assets/css/common.css',
            'resources/assets/css/navigation.css',
            'resources/assets/css/hotel.css'
            ],'public/css/merge.css');

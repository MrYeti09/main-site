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

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/viaativa-blocks.js', 'public/js/viaativa-blocks.js')
    .js('resources/assets/js/viaativa-admin.js', 'public/js/viaativa-admin.js')
    .js('resources/assets/js/viaativa-main.js', 'public/js/viaativa-main.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/viaativa-main.scss', 'public/css')
    .sass('resources/assets/sass/viaativa-blocks/blocks.scss', 'public/css/viaativa-blocks.css')
    .sass('resources/assets/sass/viaativa-admin/admin.scss', 'public/css/viaativa-admin.css')
    .copyDirectory('resources/assets/images', 'public/images');

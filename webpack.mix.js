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
    .extract(['vue', 'element-ui', 'vue-router'])
    .sass('resources/assets/sass/admin.scss', 'public/css')
    mix.copy('resources/assets/img/', 'public/img/');
if (mix.config.inProduction) {
    mix.version();
    mix.disableNotifications();
}
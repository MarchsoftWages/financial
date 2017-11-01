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

mix.js('resources/assets/js/admin/app.js', 'public/js/admin.js')
    .js('resources/assets/js/wx/app.js', 'public/js/wx.js')
    .extract(['vue', 'element-ui', 'vue-router'])
    .sass('resources/assets/sass/admin.scss', 'public/css')
    .sass('resources/assets/sass/wx.scss', 'public/css')
    mix.copy('resources/assets/js/unit', 'public/js/unit')
    mix.copy('resources/assets/img/', 'public/img/');
if (mix.config.inProduction) {
    mix.version();
    mix.disableNotifications();
}
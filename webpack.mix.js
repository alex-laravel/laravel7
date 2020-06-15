const mix = require('laravel-mix');

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

mix
    .sass('resources/sass/frontend/frontend.scss', 'public/css/frontend.css')
    .sass('resources/sass/backend/backend.scss', 'public/css/backend.css')
    .js('resources/js/frontend/frontend.js', 'public/js/frontend.js')
    .js('resources/js/backend/backend.js', 'public/js/backend.js');

if (mix.inProduction()) {
    mix.version();
}

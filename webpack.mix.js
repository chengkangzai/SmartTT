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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/alpine.js', 'public/js')
    .js('node_modules/chart.js/dist/chart.js', 'public/js/chart.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/booking_css.scss', 'public/booking_css')
    .postCss("resources/css/app.css", "public/tailwind", [
        require("tailwindcss"),
    ])
    .sourceMaps();

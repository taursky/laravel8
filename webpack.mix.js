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
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/font-awesome/fonts', 'public/fonts')
    .copy('resources/fonts/codenamecoder/CodenameCoderFree4F-Bold.otf', 'public/fonts/codenamecoder')
    .copy('resources/fonts/codenamecoder/CodenameCoderFree4F-Bold.ttf', 'public/fonts/codenamecoder')
    .copy('node_modules/tablesaw/dist/tablesaw.jquery.js', 'resources/js')
    .copy('node_modules/tablesaw/dist/tablesaw-init.js', 'resources/js')
    .sass('node_modules/font-awesome/scss/font-awesome.scss', 'public/css')
    .postCss('resources/css/main.css', 'public/css')
    .postCss('resources/css/mobile.css', 'public/css');

mix.copyDirectory('resources/images', 'public/images');

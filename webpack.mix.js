let mix = require('laravel-mix');

mix
    .js('includes/Js/app.js', 'dist')
    .setPublicPath('dist')
    .version();
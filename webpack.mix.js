const mix = require('laravel-mix');
const path = require('path');

mix.js('resources/js/app.js', 'public/js')
   .vue({ version: 3 })
   .css('resources/css/app.css', 'public/css')
   .webpackConfig({
     resolve: {
       alias: {
         '@': path.resolve('resources/js'),
       },
     },
   });
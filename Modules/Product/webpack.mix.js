const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.scripts([
    __dirname + '/Resources/assets/js/jquery.simplePagination.js',
],'Assets/js/product.js')
mix.styles([
    __dirname + '/Resources/assets/css/normalize.css',
    __dirname + '/Resources/assets/css/simplePagination.css',
    __dirname + '/Resources/assets/css/skeleton.css',
],'Assets/css/product.css')

if (mix.inProduction()) {
    mix.version();
}

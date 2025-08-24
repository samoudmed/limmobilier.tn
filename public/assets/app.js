import './styles/app.css';

// loads the jquery package from node_modules
import $ from 'jquery';

// import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
import greet from './greet';

$(document).ready(function() {
     $('body').prepend('<h1>'+greet('jill')+'</h1>');
});

import $ from 'jquery';
import 'select2';

$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

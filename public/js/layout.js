'use strict';

const $ = require('jquery');
require('bootstrap');
require('bootstrap/dist/css/bootstrap.css');
require('@babel/polyfill');
require('../css/main.css');

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});


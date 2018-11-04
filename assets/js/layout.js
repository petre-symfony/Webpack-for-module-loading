'use strict';

const $ = require('jquery');
require('bootstrap');
require('bootstrap/dist/css/bootstrap.css');
require('@babel/polyfill');
require('../css/main.scss');
require('font-awesome/css/font-awesome.css');

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});


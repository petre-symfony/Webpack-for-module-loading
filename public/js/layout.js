'use strict';

const $ = require('jquery');
require('bootstrap');
require('@babel/polyfill');
require('../css/main.css');

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});


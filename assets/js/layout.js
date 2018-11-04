'use strict';

const $ = require('jquery');
require('bootstrap-sass');
require('bootstrap-sass/assets/stylesheets/_bootstrap.scss');
require('@babel/polyfill');
require('../css/main.scss');
require('font-awesome/css/font-awesome.css');

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});


'use strict';

import $ from 'jquery';
import 'bootstrap-sass';
import '@babel/polyfill';
import '../css/main.scss';

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});


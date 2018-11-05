const $ = require('jquery');
require('bootstrap-sass');
const RepLogApp = require('./Components/RepLogApp');

window.$ = $;

$(document).ready(function () {

	var $wrapper = $('.js-rep-log-table');
	var repLogApp = new RepLogApp($wrapper);
});
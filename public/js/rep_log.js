const $ = require('jquery');
const RepLogApp = require('babel-loader!./Components/RepLogApp');

$(document).ready(function () {

	var $wrapper = $('.js-rep-log-table');
	var repLogApp = new RepLogApp($wrapper);
});
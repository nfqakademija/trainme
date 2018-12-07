import $ from 'jquery';
import Pikaday from 'pikaday';

$(document).ready(() => {
    const picker = new Pikaday({
        field: $('#datepickerList')[0],
        firstDay: 1
    });
});
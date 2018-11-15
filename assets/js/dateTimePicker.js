import $ from 'jquery';
import Pikaday from 'pikaday';

const picker = new Pikaday({
    field: $('#datepicker')[0],
    firstDay: 1
});
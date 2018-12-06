import $ from 'jquery';
import Pikaday from 'pikaday';
import 'timepicker';

$(document).ready(() => {
    const picker = new Pikaday({
        field: $('#datepickerList')[0],
        firstDay: 1
    });

    $('#fromList, #toList').timepicker({
        'timeFormat': 'H:i',
        'minTime': '6',
        'maxTime': '23',
        'step': 10,
        'stopScrollPropagation': true
    });
});
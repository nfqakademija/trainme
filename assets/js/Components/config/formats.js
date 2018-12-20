import moment from 'moment';
import BigCalendar from "react-big-calendar";

moment.locale('lt', {
    week: {
        dow: 1,
        doy: 1,
    },
});
const localizer = BigCalendar.momentLocalizer(moment);

const formats = {
    timeGutterFormat: 'HH:mm',
    eventTimeRangeFormat: ({start, end}) => `${localizer.format(start, 'HH:mm')} - ${localizer.format(end, 'HH:mm')}`
};

export {formats, localizer};
import React from 'react';
import BigCalendar from 'react-big-calendar';
import moment from 'moment';

const localizer = BigCalendar.momentLocalizer(moment);

const Calendar = (props) => (
    <React.Fragment>
        <BigCalendar
            localizer={localizer}
            views={['month', 'week', 'day']}
            defaultView={'day'}
            events={props.events}
            min={new Date(new Date().setHours(6, 0))}
            max={new Date(new Date().setHours(23, 0))}
        />
    </React.Fragment>
);

export default Calendar;
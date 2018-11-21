import React from 'react';
import BigCalendar from 'react-big-calendar';
import moment from 'moment';

const localizer = BigCalendar.momentLocalizer(moment);

const Calendar = (props) => (
    <React.Fragment>
        <BigCalendar
            localizer={localizer}
            views={['month', 'week', 'day']}
            events={props.events}
        />
    </React.Fragment>
);

export default Calendar;
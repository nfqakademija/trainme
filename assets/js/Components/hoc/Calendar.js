import React from 'react';
import BigCalendar from 'react-big-calendar';

import {formats, localizer} from "../config/formats";

let views = ['week', 'day'];
if ($(window).width() < 600) {
    views = ['day'];
}

const Calendar = props => (
    <BigCalendar
        localizer={localizer}
        views={views}
        defaultView={'day'}
        startAccessor={'starts_at'}
        endAccessor={'ends_at'}
        events={props.events}
        min={new Date(new Date().setHours(7, 0))}
        max={new Date(new Date().setHours(23, 0))}
        selectable={props.selectable}
        onSelectEvent={props.onSelectEvent}
        formats={formats}
        onSelecting={() => false}
    />
);

export default Calendar;
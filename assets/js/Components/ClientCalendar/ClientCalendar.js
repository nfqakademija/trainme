import React from 'react';
import moment from 'moment';

import BigCalendar from 'react-big-calendar';

const localizer = BigCalendar.momentLocalizer(moment);

class ClientCalendar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: []
        }
    }

    componentDidMount() {
        this.createEvents();
    }

    createEvents() {
        this.setState({
            events: [
                {
                    starts_at: new Date(),
                    ends_at: new Date('2018-12-03 14:00:00')
                }
            ]
        })
    }

    render() {
        return (<BigCalendar
            localizer={localizer}
            views={['week', 'day']}
            defaultView={'day'}
            startAccessor={'starts_at'}
            endAccessor={'ends_at'}
            events={this.state.events}
            min={new Date(new Date().setHours(6, 0))}
            max={new Date(new Date().setHours(23, 0))}
        />);
    }
}

export default ClientCalendar;
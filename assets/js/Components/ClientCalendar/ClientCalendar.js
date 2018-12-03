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
                    ends_at: new Date('2018-12-03 16:00:00')
                },
                {
                    starts_at: new Date('2018-12-03 11:00:00'),
                    ends_at: new Date('2018-12-03 12:00:00')
                }
            ]
        })
    }

    render() {
        let calendar = <p>You don't have any scheduled workouts yet.</p>

        if (this.state.events.length !== 0) {
            calendar = (<BigCalendar
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

        return (
            <React.Fragment>
                {calendar}
            </React.Fragment>
        )
    }
}

export default ClientCalendar;
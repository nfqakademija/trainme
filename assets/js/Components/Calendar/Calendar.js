import React from 'react';

import BigCalendar from 'react-big-calendar';
import Spinner from '../Management/UI/Spinner';

import moment from 'moment';
import axios from 'axios';

const localizer = BigCalendar.momentLocalizer(moment);

class Calendar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: [],
            loading: true
        }
    }

    componentDidMount() {
        axios.get(`/api/trainers/${this.props.id}/available_times`)
            .then(response => {
                this.setState({
                    events: response.data.map(event => {
                        return {
                            ends_at: new Date(event.ends_at.date),
                            starts_at: new Date(event.starts_at.date)
                        }
                    }),
                    loading: false
                });
            })
            .catch(err => {
                this.setState({
                    loading: false
                });
                console.log(err);
            });
    }

    render() {
        let calendar = <Spinner/>;
        if (!this.state.loading) {
            calendar = <BigCalendar
                localizer={localizer}
                views={['week', 'day']}
                defaultView={'day'}
                startAccessor={'starts_at'}
                endAccessor={'ends_at'}
                events={this.state.events}
                min={new Date(new Date().setHours(6, 0))}
                max={new Date(new Date().setHours(23, 0))}
            />;
        }

        return (<React.Fragment>
            {calendar}
        </React.Fragment>)
    }
}

export default Calendar;
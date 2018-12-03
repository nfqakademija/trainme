import React from 'react';
import moment from 'moment';
import $ from 'jquery';

import BigCalendar from 'react-big-calendar';

const localizer = BigCalendar.momentLocalizer(moment);

class ClientCalendar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: [],
            modalVisible: false,
            currentEvent: ''
        }

    };

    componentDidMount() {
        this.createEvents();
    }

    createEvents() {
        this.setState({
            events: [
                {
                    starts_at: new Date('2018-12-04 14:00:00'),
                    ends_at: new Date('2018-12-04 16:00:00'),
                    id: 1
                },
                {
                    starts_at: new Date('2018-12-04 11:00:00'),
                    ends_at: new Date('2018-12-04 12:00:00'),
                    id: 2
                }
            ]
        })
    }

    closeModal() {
        this.setState({modalVisible: false});
        $('html, body').css({height: '100%', overflowY: 'auto'});
    }

    onEventClick(event) {
        this.setState({
            modalVisible: true,
            currentEvent: {
                starts_at: event.starts_at,
                ends_at: event.ends_at,
                id: event.id
            }
        });

        $('html, body').css({height: '100%', overflowY: 'hidden'});
    };

    bookWorkout(id) {
        console.log('Booked workout: ' + id);
    }

    render() {
        let calendar = <p>You don't have any scheduled workouts yet.</p>;

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
                selectable={true}
                onSelectEvent={event => this.onEventClick(event)}
            />);
        }

        let modalContent = null;

        if (this.state.currentEvent) {
            modalContent = (
                <div className="calModal__content">
                    <div className="calModal__head">
                        <h3 className="blackTitle blackTitle--fSmaller">Book a workout</h3>
                        <span onClick={() => this.closeModal()} className="calModal__close">&times;</span>
                    </div>
                    <hr className="calModal__bar"/>

                    <div className="calModal__inputs">
                        <div className="top__item">
                            <label htmlFor="bookDate">Date:</label>
                            <input id="bookDate" type="date" disabled
                                   defaultValue={this.state.currentEvent.starts_at.toLocaleString().split(' ')[0]}/>
                        </div>
                        <div className="top__item">
                            <label htmlFor="bookFrom">From:</label>
                            <input id="bookFrom" type="time"
                                   defaultValue={this.state.currentEvent.starts_at.toLocaleString().split(' ')[1]}/>
                        </div>
                        <div className="top__item">
                            <label htmlFor="bookTo">To:</label>
                            <input id="bookTo" type="time"
                                   defaultValue={this.state.currentEvent.ends_at.toLocaleString().split(' ')[1]}/>
                        </div>
                    </div>

                    <div className="calModal__foot">
                        <button onClick={() => this.bookWorkout(this.state.currentEvent.id)}
                                className="button button__content">Book
                        </button>
                    </div>
                </div>);
        }

        return (
            <React.Fragment>
                {calendar}
                {this.state.modalVisible ?
                    <div id="calModal" className="calModal">
                        {modalContent}
                    </div> : null}
            </React.Fragment>
        )
    }
}

export default ClientCalendar;
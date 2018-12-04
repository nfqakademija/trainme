import React from 'react';

import BigCalendar from 'react-big-calendar';
import Spinner from '../UI/Spinner';

import moment from 'moment';
import axios from 'axios';

const localizer = BigCalendar.momentLocalizer(moment);

class TrainerCalendar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: [],
            loading: true,
            modalVisible: false,
            currentEvent: '',
            booking: false,
            success: false
        }
    }

    componentDidMount() {
        this.fetchAvailableTimes();
    }

    fetchAvailableTimes() {
        this.setState({loading: true});
        axios.get(`/api/trainers/${this.props.id}/available_times`)
            .then(response => {
                this.setState({
                    events: response.data.map(event => {
                        return {
                            ends_at: new Date(event.ends_at),
                            starts_at: new Date(event.starts_at)
                        }
                    }),
                    loading: false,
                    booking: false,
                    success: false
                });

                this.closeModal();
            })
            .catch(err => {
                this.setState({
                    loading: false,
                    booking: false,
                    success: false
                });
                console.log(err);
            });
    }

    onEventClick(event) {
        this.setState({
            modalVisible: true,
            currentEvent: {
                starts_at: event.starts_at,
                ends_at: event.ends_at,
            }
        });
    };

    closeModal() {
        this.setState({modalVisible: false});
    }

    bookWorkout() {
        this.setState({booking: true});
        axios.post('/api/scheduled_workout', {
            'starts_at': this.state.currentEvent.starts_at.toLocaleString(),
            'ends_at': this.state.currentEvent.ends_at.toLocaleString(),
            'trainer_id': this.props.id
        }).then(response => {
            this.fetchAvailableTimes();
            this.setState({
                success: true,
                booking: false
            });
        }).catch(err => {
            console.log(err);
            this.setState({booking: false});
            this.closeModal();
        });
        console.log({
            'starts_at': this.state.currentEvent.starts_at.toLocaleString(),
            'ends_at': this.state.currentEvent.ends_at.toLocaleString(),
        });
    }

    handleFromChange(e) {
        const {currentEvent} = this.state;
        const date = `${currentEvent.starts_at.toLocaleString().split(' ')[0]} `;

        this.setState({
            currentEvent: {
                starts_at: new Date(date + e.target.value),
                ends_at: currentEvent.ends_at
            }
        })
    }

    handleToChange(e) {
        const {currentEvent} = this.state;
        const date = `${currentEvent.starts_at.toLocaleString().split(' ')[0]} `;

        this.setState({
            currentEvent: {
                starts_at: currentEvent.starts_at,
                ends_at: new Date(date + e.target.value)
            }
        })
    }

    render() {
        let calendar = <Spinner/>;

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
                            <input id="bookFrom" type="time" onChange={(e) => this.handleFromChange(e)}
                                   defaultValue={this.state.currentEvent.starts_at.toLocaleString().split(' ')[1]}/>
                        </div>
                        <div className="top__item">
                            <label htmlFor="bookTo">To:</label>
                            <input id="bookTo" type="time" onChange={(e) => this.handleToChange(e)}
                                   defaultValue={this.state.currentEvent.ends_at.toLocaleString().split(' ')[1]}/>
                        </div>
                    </div>

                    <div className="calModal__foot">
                        {this.state.booking ?
                            <button disabled
                                    className="button button__content">Booking...
                            </button> :
                            <button onClick={() => this.bookWorkout()}
                                    className="button button__content">Book
                            </button>}
                        {this.state.success ?
                            <div className="bookMsg bookMsg--success">
                                <p>Success!</p>
                            </div> :
                            null}
                    </div>
                </div>);
        }

        return (<React.Fragment>
            {calendar}
            {this.state.modalVisible ?
                <div id="calModal" className="calModal">
                    {modalContent}
                </div> : null}
        </React.Fragment>)
    }
}

export default TrainerCalendar;
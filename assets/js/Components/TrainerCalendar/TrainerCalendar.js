import React from 'react';

import BigCalendar from 'react-big-calendar';
import Spinner from '../UI/Spinner';
import Modal from "../UI/Modal";

import moment from 'moment';
import axios from 'axios';

import validateDateInput from "./validation";
import Message from "../UI/Message";

let views = ['week', 'day'];

if ($(window).width() < 600) {
    views = ['day'];
}

const localizer = BigCalendar.momentLocalizer(moment);

class TrainerCalendar extends React.Component {
    constructor(props) {
        super(props);

        const userRoles = JSON.parse(props.roles);

        this.state = {
            events: [],
            isCustomer: userRoles.includes('ROLE_CUSTOMER'),
            isTrainer: userRoles.includes('ROLE_TRAINER'),
            loading: true,
            modalVisible: false,
            booking: false,
            showSuccessMessage: false,
            starts_at: '',
            ends_at: '',
            bookFromValue: '',
            bookToValue: ''
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
        if (!this.state.isCustomer) {
            return;
        }

        this.setState({
            showSuccessMessage: false,
            modalVisible: true,
            starts_at: event.starts_at,
            ends_at: event.ends_at,
        }, () => {
            $('#bookFrom').timepicker({
                timeFormat: 'HH:mm',
                interval: 5,
                minTime: '6',
                maxTime: '23',
                defaultTime: event.starts_at,
                dynamic: false,
                dropdown: true,
                scrollbar: false,
                change: (time) => {
                    const date = moment(event.ends_at).format('YYYY-MM-DD');
                    const timeVal = moment(time).format('HH:mm:ss');

                    this.setState({bookFromValue: new Date(`${date} ${timeVal}`)})
                }
            });

            $('#bookTo').timepicker({
                timeFormat: 'HH:mm',
                interval: 5,
                minTime: '6',
                maxTime: '23',
                dynamic: false,
                defaultTime: event.ends_at,
                dropdown: true,
                scrollbar: false,
                change: (time) => {
                    const date = moment(event.ends_at).format('YYYY-MM-DD');
                    const timeVal = moment(time).format('HH:mm:ss');

                    this.setState({bookToValue: new Date(`${date} ${timeVal}`)})
                }
            });

        });
    };

    closeModal() {
        this.setState({modalVisible: false});
    }

    bookWorkout() {
        const {starts_at, ends_at, bookFromValue, bookToValue} = this.state;

        if (!validateDateInput(starts_at, ends_at, bookFromValue, bookToValue)) {
            const from = starts_at.toLocaleTimeString();
            const to = ends_at.toLocaleTimeString();
            alert(`Please choose time between ${from} and ${to}`);
            return;
        }

        this.setState({booking: true});
        axios.post('/api/scheduled_workout', {
            'starts_at': moment(this.state.bookFromValue).format('YYYY-MM-DD HH:mm:ss'),
            'ends_at': moment(this.state.bookToValue).format('YYYY-MM-DD HH:mm:ss'),
            'trainer_id': this.props.id
        }).then(response => {
            this.fetchAvailableTimes();
            this.setState({
                success: true,
                booking: false,
                showSuccessMessage: true
            });
        }).catch(err => {
            console.log(err);
            this.setState({booking: false});
            this.closeModal();
        });
    }

    render() {
        const {
            modalVisible, showSuccessMessage, loading, events, starts_at, ends_at, bookFromValue, bookToValue,
            booking, isCustomer
        } = this.state;

        if (modalVisible) {
            $('body').css({overflowY: 'hidden'});
        } else {
            $('body').css({overflowY: 'auto'});
        }

        let successMessage = null;

        if (showSuccessMessage) {
            successMessage =
                <Message type="success">You've successfully booked a workout! Trainer will contact you soon.</Message>;
        }

        let calendar = <p>This trainer has no available workouts.</p>;

        if (loading) {
            calendar = <div className={loading ? "mngList" : null}><Spinner/></div>;
        }

        if (events.length !== 0) {
            calendar = (<BigCalendar
                localizer={localizer}
                views={views}
                defaultView={'day'}
                startAccessor={'starts_at'}
                endAccessor={'ends_at'}
                events={events}
                min={new Date(new Date().setHours(7, 0))}
                max={new Date(new Date().setHours(23, 0))}
                selectable={true}
                onSelectEvent={event => this.onEventClick(event)}
            />);
        }

        let modalContent = null;

        if (starts_at && ends_at) {
            modalContent = (
                <React.Fragment>
                    <div className="calModal__head">
                        <h3 className="blackTitle blackTitle--fSmaller blackTitle--modal">Book a workout</h3>
                        <span onClick={() => this.closeModal()} className="calModal__close">&times;</span>
                    </div>
                    <hr className="calModal__bar"/>

                    <div className="calModal__middle calModal__middle--row">
                        <div className="modalInputGroup">
                            <label htmlFor="bookDate">Date:</label>
                            <input className="bookInput" id="bookDate" type="date" disabled
                                   defaultValue={moment(starts_at).format("YYYY-MM-DD")}/>
                        </div>
                        <div className="modalInputGroup">
                            <label htmlFor="bookFrom">From:</label>
                            <input className="bookInput" id="bookFrom" type="text"/>
                            <input type="hidden" value={bookFromValue}/>
                        </div>
                        <div className="modalInputGroup">
                            <label htmlFor="bookTo">To:</label>
                            <input className="bookInput" id="bookTo" type="text"/>
                            <input type="hidden" value={bookToValue}/>
                        </div>
                    </div>

                    <div className="calModal__foot">
                        {booking ?
                            <button disabled
                                    className="btnPrimary" style={{width: '100px'}}>Booking...
                            </button> :
                            <button onClick={() => this.bookWorkout()}
                                    className="btnPrimary u-w85">Book
                            </button>}
                    </div>
                </React.Fragment>);
        }

        let info = null;

        if (!loading && isCustomer && events.length !== 0) {
            info = <Message type="info">Click on the desired available time slot to book a workout.</Message>;
        } else if (!loading && events.length !== 0) {
            info = <Message type="danger">Only logged in customers can book workouts!</Message>;
        }

        return (<React.Fragment>
            {successMessage}
            {info}
            {calendar}
            {modalVisible ?
                <Modal>
                    {modalContent}
                </Modal> : null}
        </React.Fragment>)
    }
}

export default TrainerCalendar;
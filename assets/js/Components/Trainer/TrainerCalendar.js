import React from 'react';
import axios from 'axios';
import moment from 'moment';

import Spinner from '../UI/Spinner';
import Modal from "../UI/Modal";
import Message from "../UI/Message";
import ModalHead from "../UI/ModalHead";
import ModalFoot from "../UI/ModalFoot";
import ModalContent from "../UI/ModalContent";
import Calendar from '../hoc/Calendar';
import validateDateInput from "../utils/validateBooking";

class TrainerCalendar extends React.Component {
    constructor(props) {
        super(props);
        const userRoles = JSON.parse(props.roles);
        this.state = {
            events: [],
            isCustomer: userRoles.includes('ROLE_CUSTOMER'),
            isTrainer: userRoles.includes('ROLE_TRAINER'),
            isLoading: true,
            isModalVisible: false,
            isBooking: false,
            isSuccess: false,
            isError: false,
            starts_at: '',
            ends_at: '',
            bookFromValue: '',
            bookToValue: '',
            responseMessage: ''
        }
    }

    componentDidMount() {
        this.fetchAvailableTimes();
    }

    fetchAvailableTimes() {
        this.setState({isLoading: true});
        axios.get(`/api/trainers/${this.props.id}/available_times`)
            .then(response => {
                this.setState({
                    events: response.data.map(event => {
                        return {
                            ends_at: new Date(event.ends_at),
                            starts_at: new Date(event.starts_at)
                        }
                    }),
                    isLoading: false,
                    isBooking: false,
                });
                this.closeModal();
            })
            .catch(err => {
                this.setState({
                    isLoading: false,
                    isBooking: false,
                });
                console.log(err);
            });
    }

    onEventClick(event) {
        if (!this.state.isCustomer) {
            return;
        }

        this.setState({
            isSuccess: false,
            isError: false,
            isModalVisible: true,
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
                    this.setState({bookFromValue: new Date(`${date} ${timeVal}`)});
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
                    this.setState({bookToValue: new Date(`${date} ${timeVal}`)});
                }
            });
        });
    };

    closeModal() {
        this.setState({isModalVisible: false});
    }

    bookWorkout() {
        const {
            starts_at,
            ends_at,
            bookFromValue,
            bookToValue
        } = this.state;
        if (!validateDateInput(starts_at, ends_at, bookFromValue, bookToValue)) {
            const from = moment(starts_at).format('HH:mm');
            const to = moment(ends_at).format('HH:mm');
            alert(`Please choose time between ${from} and ${to}`);
            return;
        }

        if (bookToValue <= bookFromValue) {
            alert('Invalid time');
            return;
        }

        this.setState({isBooking: true});
        axios.post('/api/scheduled_workout', {
            'starts_at': moment(bookFromValue).format('YYYY-MM-DD HH:mm'),
            'ends_at': moment(bookToValue).format('YYYY-MM-DD HH:mm'),
            'trainer_id': this.props.id
        }).then(response => {
            this.setState({
                isBooking: false,
                isSuccess: true,
                isError: false,
                responseMessage: 'You\'ve successfully booked a workout! Trainer will contact you soon.'
            });
            this.fetchAvailableTimes();
        }).catch(err => {
            console.log(err);
            this.setState({
                isBooking: false,
                isError: true,
                isSuccess: false,
                responseMessage: err.response.data.validationError ? err.response.data.errors[0] : 'Oops, something went wrong.'
            });
            this.closeModal();
        });
    }

    render() {
        const {
            isModalVisible,
            isSuccess,
            isError,
            isLoading,
            events,
            starts_at,
            bookFromValue,
            bookToValue,
            isBooking,
            isCustomer,
            isTrainer,
            responseMessage
        } = this.state;
        const BODY = $('body');
        BODY.css({overflowY: 'auto'});

        let calendar = <p>This trainer has no available workouts.</p>;
        let modal = null;
        let info = null;
        let message = <Message type={isSuccess ? 'success' : 'danger'}>{responseMessage}</Message>;

        if (isLoading) {
            calendar = <Spinner/>;
        }

        if (events.length) {
            const selectedDate = moment(this.props.selectedDate, "YYYY-MM-DD");

            calendar = (<Calendar
                events={events}
                selectable
                onSelectEvent={event => this.onEventClick(event)}
                defaultDate={selectedDate.isValid() ? selectedDate.toDate() : new Date()}
            />);
        }

        if (isTrainer && !events.length && !isLoading) {
            calendar =
                <p>You have no available workout times. Add some <a className="messageLink" href="/manage">here</a>.
                </p>;
        }

        if (isModalVisible) {
            BODY.css({overflowY: 'hidden'});
            modal = (
                <Modal>
                    <ModalHead onCloseClick={() => this.closeModal()}>Book a workout</ModalHead>
                    <ModalContent
                        defaultDate={moment(starts_at).format("YYYY-MM-DD")}
                        bookFromValue={bookFromValue}
                        bookToValue={bookToValue}
                        booking/>
                    <ModalFoot>
                        {isBooking ?
                            <button disabled
                                    className="btnPrimary" style={{width: '100px'}}>Booking...
                            </button> :
                            <button onClick={() => this.bookWorkout()}
                                    className="btnPrimary u-w85">Book
                            </button>}
                    </ModalFoot>
                </Modal>);
        }

        if (isCustomer && events.length) {
            info = <Message type="info">Click on the desired available time slot to book a workout.</Message>;
        } else if (events.length && !isTrainer) {
            info = <Message type="danger">Only logged in
                <em style={{fontWeight: 'bolder', fontStyle: 'normal'}}> customers </em> can book workouts! Please <a
                    href="/login" className="messageLink">log in</a>.</Message>;
        } else if (events.length) {
            info =
                <Message type="info">You are logged in as a trainer. Your customers will book workouts here.</Message>;
        }

        return (
            <React.Fragment>
                {(isSuccess || isError) && message}
                {info}
                {calendar}
                {modal}
            </React.Fragment>)
    }
}

export default TrainerCalendar;
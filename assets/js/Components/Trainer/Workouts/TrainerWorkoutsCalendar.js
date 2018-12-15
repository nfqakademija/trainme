import React from 'react';
import axios from 'axios';
import $ from 'jquery';

import Spinner from '../../UI/Spinner';
import Modal from "../../UI/Modal";
import Message from '../../UI/Message';
import Calendar from "../../hoc/Calendar";
import ModalHead from "../../UI/ModalHead";
import ModalContent from "../../UI/ModalContent";

class TrainerWorkoutsCalendar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: [],
            isLoading: true,
            isModalVisible: false,
            currentEvent: '',
            starts_at: '',
            ends_at: '',
            customer: ''
        }
    }

    componentDidMount() {
        this.fetchWorkouts();
    }

    fetchWorkouts() {
        this.setState({isLoading: true});
        axios.get(`/api/trainers/${this.props.id}/scheduledWorkouts`)
            .then(response => {
                this.setState({
                    events: response.data.map(event => {
                        return {
                            ends_at: new Date(event.ends_at),
                            starts_at: new Date(event.starts_at),
                            customer: event.customer
                        }
                    }),
                    isLoading: false,
                });
            })
            .catch(err => {
                this.setState({
                    isLoading: false,
                });
                console.log(err);
            });
    }

    onEventClick(event) {
        this.setState({
            isModalVisible: true,
            currentEvent: {
                starts_at: event.starts_at,
                ends_at: event.ends_at,
                customer: event.customer
            },
        });
    };

    closeModal() {
        this.setState({isModalVisible: false});
    }

    render() {
        const {
            isLoading,
            events,
            isModalVisible,
            currentEvent
        } = this.state;
        let calendar = <p>You have no scheduled workouts.</p>;
        let modal = null;
        let info = null;
        const BODY = $('body').css({overflowY: 'auto'});

        if (isLoading) {
            calendar = <Spinner/>;
        }

        if (events.length) {
            calendar = (<Calendar
                events={events}
                selectable={true}
                onSelectEvent={event => this.onEventClick(event)}
            />);
        }

        if (currentEvent && isModalVisible) {
            BODY.css({overflowY: 'hidden'});
            modal = (
                <Modal>
                    <ModalHead onCloseClick={() => this.closeModal()}>Scheduled workouts</ModalHead>
                    <ModalContent col trainerWorkout customerName={currentEvent.customer.name}
                                  customerPhone={currentEvent.customer.phone}/>
                </Modal>);
        }

        if (!isLoading && events.length) {
            info = <Message type="info">Click on the workout to view info.</Message>;
        }

        return (<React.Fragment>
            {info}
            {calendar}
            {modal}
        </React.Fragment>)
    }
}

export default TrainerWorkoutsCalendar;
import React from 'react';
import axios from 'axios';
import $ from 'jquery'
import moment from 'moment';

import Spinner from "../UI/Spinner";
import Modal from "../UI/Modal";
import Message from "../UI/Message";
import Calendar from '../hoc/Calendar';
import ModalHead from "../UI/ModalHead";
import ModalContent from "../UI/ModalContent";
import ModalFoot from "../UI/ModalFoot";

class CustomerWorkouts extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: '',
            isLoading: false,
            isModalVisible: false,
            currentWorkout: '',
            isDeleting: false,
            isSuccess: false,
            isError: false
        }
    };

    componentDidMount() {
        this.fetchEvents();
    }

    fetchEvents() {
        this.setState({isLoading: true});
        axios.get('/api/scheduled_workout')
            .then(response => {
                this.setState({
                    isLoading: false,
                    events: response.data.map(workout => {
                        return {
                            starts_at: new Date(workout.starts_at),
                            ends_at: new Date(workout.ends_at),
                            id: workout.id,
                            trainer: workout.trainer
                        }
                    })
                });
            })
            .catch(err => {
                this.setState({isLoading: false});
                console.log(err);
            });
    }

    onWorkoutClick(event) {
        this.setState({
            isModalVisible: true,
            isSuccess: false,
            isError: false,
            currentWorkout: {
                starts_at: event.starts_at,
                ends_at: event.ends_at,
                id: event.id,
                trainer: event.trainer
            }
        });
    }

    closeModal() {
        this.setState({isModalVisible: false});
    }

    cancelWorkout() {
        const {starts_at, ends_at} = this.state.currentWorkout;
        const date = moment(starts_at).format('YYYY-MM-DD');
        const from = moment(starts_at).format('HH:mm');
        const to = moment(ends_at).format('HH:mm');
        const warning = confirm(`Are you sure you want to cancel workout on ${date} between ${from} and ${to}?`);
        if (!warning) {
            return;
        }

        this.setState({isDeleting: true});
        axios.delete(`/api/scheduled_workout/${this.state.currentWorkout.id}`)
            .then(response => {
                this.setState({
                    events: this.state.events.filter(event => event.id !== this.state.currentWorkout.id),
                    isDeleting: false,
                    isModalVisible: false,
                    isSuccess: true
                });
            })
            .catch(err => {
                this.setState({
                    isModalVisible: false,
                    isDeleting: false,
                    isError: true
                });
                console.log(err);
            });
    }

    render() {
        const {
            events,
            isError,
            isLoading,
            currentWorkout,
            isDeleting,
            isModalVisible,
            isSuccess
        } = this.state;
        let calendar = <p>You don't have any scheduled workouts yet.</p>;
        let successMessage = null;
        let info = null;
        let modal = null;
        const BODY = $('body');
        BODY.css({overflowY: 'auto'});

        if (isSuccess) {
            successMessage = <Message type="success">Workout canceled successfully.</Message>;
        } else if (isError) {
            successMessage = <Message type="danger">Oops, something went wrong!</Message>;
        }

        if (events.length) {
            calendar = (<Calendar
                events={events}
                selectable
                onSelectEvent={event => this.onWorkoutClick(event)}
            />);
        }

        if (isLoading) {
            calendar = <Spinner/>;
        }

        if (!isLoading && events.length) {
            info = <Message type="info">Click on the workout to view info or to cancel it.</Message>;
        }

        if (currentWorkout && isModalVisible) {
            BODY.css({overflowY: 'hidden'});
            modal = (
                <Modal>
                    <ModalHead onCloseClick={() => this.closeModal()}> Workout info</ModalHead>
                    <ModalContent
                        workoutDate={moment(currentWorkout.starts_at).format('YYYY-MM-DD')}
                        workoutStarts={moment(currentWorkout.starts_at).format('HH:mm')}
                        workoutEnds={moment(currentWorkout.ends_at).format('HH:mm')}
                        customer/>
                    <div className="calModal__head">
                        <h3 className="blackTitle blackTitle--fSmaller blackTitle--modal">Trainer's info</h3>
                    </div>
                    <hr className="calModal__bar"/>
                    <ModalContent
                        col
                        customerWorkout
                        trainerName={currentWorkout.trainer.name}
                        trainerPhone={currentWorkout.trainer.phone}/>
                    <ModalFoot>
                        <a target="_blank" className="btn" href={`/trainers/${currentWorkout.trainer.id}`}>View Page</a>
                        <span className="btn btn--cancel"
                              onClick={() => this.cancelWorkout()}>{isDeleting ? 'Canceling...' : 'Cancel workout'}</span>
                    </ModalFoot>
                </Modal>);
        }

        return (
            <React.Fragment>
                {successMessage}
                {info}
                {calendar}
                {modal}
            </React.Fragment>
        )
    }
}

export default CustomerWorkouts;
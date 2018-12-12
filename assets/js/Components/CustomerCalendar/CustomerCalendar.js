import React from 'react';
import moment from 'moment';
import axios from 'axios';
import $ from 'jquery'

import BigCalendar from 'react-big-calendar';
import Spinner from "../UI/Spinner";
import Modal from "../UI/Modal";

let views = ['week', 'day'];

if ($(window).width() < 600) {
    views = ['day'];
}

const localizer = BigCalendar.momentLocalizer(moment);

class CustomerCalendar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: '',
            loading: false,
            modalVisible: false,
            currentWorkout: '',
            deleting: false
        }
    };

    componentDidMount() {
        this.fetchEvents();
    }

    fetchEvents() {
        this.setState({loading: true});
        axios.get('/api/scheduled_workout')
            .then(response => {
                this.setState({
                    loading: false,
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
                this.setState({loading: false});
                console.log(err);
            });
    }

    onWorkoutClick(event) {
        this.setState({
            modalVisible: true,
            currentWorkout: {
                starts_at: event.starts_at,
                ends_at: event.ends_at,
                id: event.id,
                trainer: event.trainer
            }
        });
    }

    closeModal() {
        this.setState({modalVisible: false});
    }

    cancelWorkout() {
        const {starts_at, ends_at} = this.state.currentWorkout;
        const date = starts_at.toLocaleDateString();
        const from = starts_at.toLocaleTimeString().substr(0, 5);
        const to = ends_at.toLocaleTimeString().substr(0, 5);

        const warning = confirm(`Are you sure you want to cancel workout on ${date} between ${from} and ${to}?`);
        if (!warning) {
            return;
        }

        this.setState({deleting: true});
        axios.delete(`/api/scheduled_workout/${this.state.currentWorkout.id}`)
            .then(response => {
                this.setState({
                    events: this.state.events.filter(event => event.id !== this.state.currentWorkout.id),
                    deleting: false,
                    modalVisible: false
                });
            })
            .catch(err => {
                this.setState({
                    modalVisible: false,
                    deleting: false
                });
                console.log(err);
            });
    }

    render() {
        const {events, loading, currentWorkout, deleting, modalVisible} = this.state;

        let calendar = <p>You don't have any scheduled workouts yet.</p>;

        if (events.length !== 0) {
            calendar = (<BigCalendar
                localizer={localizer}
                views={views}
                defaultView={'day'}
                startAccessor={'starts_at'}
                endAccessor={'ends_at'}
                events={events}
                min={new Date(new Date().setHours(6, 0))}
                max={new Date(new Date().setHours(23, 0))}
                onSelectEvent={event => this.onWorkoutClick(event)}
            />);
        }

        if (loading) {
            calendar = <div className={loading ? "mngList" : null}><Spinner/></div>;
        }

        let info = null;

        if (!loading && events.length !== 0) {
            info = <section className="info info--customer">
                <p className="info__text">
                    <i className="fas fa-info-circle u-mgRt fa-info-circle--info"></i>
                    Click on the workout to view info or to cancel it.
                </p>
            </section>
        }

        let modalContent = null;

        if (currentWorkout) {
            modalContent = (
                <React.Fragment>
                    <div className="calModal__head">
                        <h3 className="blackTitle blackTitle--fSmaller">Workout info</h3>
                        <span onClick={() => this.closeModal()} className="calModal__close">&times;</span>
                    </div>
                    <hr className="calModal__bar"/>

                    <div className="calModal__middle calModal__middle--row">
                        <div className="modalInputGroup">
                            <p>Date:</p>
                            <p>{currentWorkout.starts_at.toLocaleDateString()}</p>
                        </div>
                        <div className="modalInputGroup">
                            <p>Starts:</p>
                            <p>{currentWorkout.starts_at.toLocaleTimeString().substr(0, 5)}</p>
                        </div>
                        <div className="modalInputGroup">
                            <p>Ends:</p>
                            <p>{currentWorkout.ends_at.toLocaleTimeString().substr(0, 5)}</p>
                        </div>
                    </div>

                    <div className="calModal__head">
                        <h3 className="blackTitle blackTitle--fSmaller">Trainer info</h3>
                    </div>
                    <hr className="calModal__bar"/>

                    <div className="calModal__middle calModal__middle--col">
                        <p className="calModal__info">Name: {currentWorkout.trainer.name}</p>
                        <p className="calModal__info">Phone: {currentWorkout.trainer.phone}</p>
                    </div>
                    <div className="calModal__foot">
                        <a target="_blank" className="btn" href={`/trainers/${currentWorkout.trainer.id}`}>View Page</a>
                        <span className="btn btn--cancel"
                              onClick={() => this.cancelWorkout()}>{deleting ? 'Canceling...' : 'Cancel workout'}</span>
                    </div>
                </React.Fragment>);
        }

        if (modalVisible) {
            $('body').css({overflowY: 'hidden'});
        } else {
            $('body').css({overflowY: 'auto'});
        }

        return (
            <React.Fragment>
                {info}
                {calendar}
                {modalVisible ? <Modal>{modalContent}</Modal> : null}
            </React.Fragment>
        )
    }
}

export default CustomerCalendar;
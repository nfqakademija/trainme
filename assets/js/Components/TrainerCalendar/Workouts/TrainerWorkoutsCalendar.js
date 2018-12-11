import React from 'react';

import BigCalendar from 'react-big-calendar';
import Spinner from '../../UI/Spinner';
import Modal from "../../UI/Modal";

import moment from 'moment';
import axios from 'axios';
import $ from 'jquery';

let views = ['week', 'day'];

if ($(window).width() < 600) {
    views = ['day'];
}

const localizer = BigCalendar.momentLocalizer(moment);

class TrainerWorkoutsCalendar extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            events: [],
            loading: true,
            modalVisible: false,
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
        this.setState({loading: true});
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
                    loading: false,
                });
            })
            .catch(err => {
                this.setState({
                    loading: false,
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
                customer: event.customer
            },
        });
    };

    closeModal() {
        this.setState({modalVisible: false});
    }

    render() {
        let calendar = <p>You have no scheduled workouts.</p>;

        if (this.state.loading) {
            calendar = <div className={this.state.loading ? "mngList" : null}><Spinner/></div>;
        }

        if (this.state.events.length !== 0) {
            calendar = (<BigCalendar
                localizer={localizer}
                views={views}
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
                <React.Fragment>
                    <div className="calModal__head">
                        <h3 className="blackTitle blackTitle--fSmaller">Workout info</h3>
                        <span onClick={() => this.closeModal()} className="calModal__close">&times;</span>
                    </div>
                    <hr className="calModal__bar"/>

                    <div className="calModal__middle calModal__middle--col">
                        <p className="info--customer">Name: {this.state.currentEvent.customer.name}</p>
                        <p className="info--customer">Phone: {this.state.currentEvent.customer.phone}</p>
                    </div>
                </React.Fragment>);
        }

        return (<React.Fragment>
            {calendar}
            {this.state.modalVisible ?
                <Modal>
                    {modalContent}
                </Modal> : null}
        </React.Fragment>)
    }
}

export default TrainerWorkoutsCalendar;
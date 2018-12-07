import React from 'react';

import BigCalendar from 'react-big-calendar';
import Spinner from '../UI/Spinner';
import Modal from "../UI/Modal";

import moment from 'moment';
import axios from 'axios';

import validateDateInput from "./validation";

const width = $(window).width();
let views = ['week', 'day'];

if (width < 600) {
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
            success: false,
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
                booking: false
            });

            this.setState({showSuccessMessage: true})
        }).catch(err => {
            console.log(err);
            this.setState({booking: false});
            this.closeModal();
        });
    }

    render() {

        let successMessage = null;

        if (this.state.showSuccessMessage) {
            successMessage = (<section className="info info--trainer">
                <p className="info__text">
                    <i className="fas fa-info-circle u-mgRt fa-info-circle--custom"></i>
                    You successfully booked a workout! Trainer will contact you soon.
                </p>
            </section>);
        }

        let calendar = <p>This trainer has no available workouts.</p>;

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
                min={new Date(new Date().setHours(7, 0))}
                max={new Date(new Date().setHours(23, 0))}
                selectable={true}
                onSelectEvent={event => this.onEventClick(event)}
            />);
        }

        let modalContent = null;

        if (this.state.starts_at && this.state.ends_at) {
            modalContent = (
                <React.Fragment>
                    <div className="calModal__head">
                        <h3 className="blackTitle blackTitle--fSmaller">Book a workout</h3>
                        <span onClick={() => this.closeModal()} className="calModal__close">&times;</span>
                    </div>
                    <hr className="calModal__bar"/>

                    <div className="calModal__middle calModal__middle--row">
                        <div className="modalInputGroup">
                            <label htmlFor="bookDate">Date:</label>
                            <input id="bookDate" type="date" disabled
                                   defaultValue={moment(this.state.starts_at).format("YYYY-MM-DD")}/>
                        </div>
                        <div className="modalInputGroup">
                            <label htmlFor="bookFrom">From:</label>
                            <input id="bookFrom" type="text"/>
                            <input type="hidden" value={this.state.bookFromValue}/>
                        </div>
                        <div className="modalInputGroup">
                            <label htmlFor="bookTo">To:</label>
                            <input id="bookTo" type="text"/>
                            <input type="hidden" value={this.state.bookToValue}/>
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
                </React.Fragment>);
        }

        return (<React.Fragment>
            {successMessage}
            {calendar}
            {this.state.modalVisible ?
                <Modal>
                    {modalContent}
                </Modal> : null}
        </React.Fragment>)
    }
}

export default TrainerCalendar;
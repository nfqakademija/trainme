import React from 'react';
import axios from 'axios';
import Pikaday from "pikaday";
import moment from 'moment';

import Slot from './Slot';
import Spinner from '../../UI/Spinner';
import Message from "../../UI/Message";
import AddSlot from "./AddSlot";

import validateSlot from '../../utils/validateSlot';

class Management extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            slots: [],
            isLoading: false,
            date: '',
            from: '',
            to: '',
            mngFromValue: '',
            mngToValue: '',
            isPosting: false,
        };
    }

    componentDidMount() {
        this.fetchSlots();

        const picker = new Pikaday({
            field: $('#mngDate')[0],
            firstDay: 1,
            onSelect: (date) => {
                this.setState({date})
            }
        });

        $('#mngFrom').timepicker({
            timeFormat: 'HH:mm',
            interval: 10,
            minTime: '6',
            maxTime: '23',
            dynamic: false,
            dropdown: true,
            scrollbar: false,
            change: (time) => {
                this.setState({mngFromValue: time})
            }
        });

        $('#mngTo').timepicker({
            timeFormat: 'HH:mm',
            interval: 10,
            minTime: '6',
            maxTime: '23',
            dynamic: false,
            dropdown: true,
            scrollbar: false,
            change: (time) => {
                this.setState({mngToValue: time})
            }
        });
    }

    fetchSlots() {
        this.setState({isLoading: true});
        axios.get('/api/availability_slot')
            .then(response => {
                this.setState({
                    slots: Object.keys(response.data).map(slot => response.data[slot]),
                    isLoading: false
                });
            })
            .catch(err => {
                console.log(err);
                this.setState({
                    isLoading: false
                })
            });
    };

    addNewSlot() {
        const {
            date,
            mngFromValue,
            mngToValue,
            slots
        } = this.state;

        if (date && mngFromValue && mngToValue) {
            const dateStr = moment(date).format("YYYY-MM-DD");
            const from = moment(mngFromValue).format("HH:mm");
            const to = moment(mngToValue).format("HH:mm");
            if (!validateSlot(slots, date, mngFromValue, mngToValue)) {
                alert('You are already available in this period of time!');
                return;
            }
            this.setState({isPosting: true});
            axios.post('/api/availability_slot', {
                starts_at: `${dateStr} ${from}`,
                ends_at: `${dateStr} ${to}`
            }).then(response => {
                this.setState({
                    slots: [{
                        id: response.data.id,
                        starts_at: response.data.starts_at,
                        ends_at: response.data.ends_at
                    }, ...this.state.slots],
                    isPosting: false
                });
            }).catch(err => {
                console.log(err);
                this.setState({isPosting: false});
            });
        } else {
            alert('Please fill in all inputs');
        }
    };

    deleteClicked(id) {
        let box = confirm('Are you sure you want to remove available schedule time?');
        if (box) {
            axios.delete(`/api/availability_slot/${id}`)
                .then(response => {
                    this.setState({
                        slots: [...this.state.slots.filter(slot => slot.id !== id)],
                    });
                }).catch(err => {
                console.log(err);
            });
        }
    }

    render() {
        const {
            isLoading,
            slots,
            isPosting,
            mngFromValue,
            mngToValue
        } = this.state;
        let list = <p>You don't have any available time ranges yet.</p>;
        let addNewButton = <button style={{marginTop: '25px'}} onClick={() => this.addNewSlot()}
                                   className="btnPrimary">Add new</button>;

        if (isLoading) {
            list = <Spinner/>;
        }

        if (slots.length) {
            list = slots.map(slot => (
                <Slot
                    key={slot.id}
                    id={slot.id}
                    date={moment(slot.starts_at).format('YYYY-MM-DD')}
                    from={moment(slot.starts_at).format('HH:mm')}
                    to={moment(slot.ends_at).format('HH:mm')}
                    onDelete={id => this.deleteClicked(id)}
                    slots={slots}
                />
            ));
        }

        if (isPosting) {
            addNewButton = <button style={{marginTop: '25px'}} className="btnPrimary btnPrimary--disabled">Adding</button>;
        }

        return (
            <React.Fragment>
                <Message type="info">You can add, edit or remove your availability periods here.</Message>
                <AddSlot from={mngFromValue} to={mngToValue}>{addNewButton}</AddSlot>
                <div className={isLoading ? "mngList" : null}>{slots && list}</div>
            </React.Fragment>
        )
    }
}

export default Management;
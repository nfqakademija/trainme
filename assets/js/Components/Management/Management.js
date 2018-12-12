import React from 'react';
import axios from 'axios';
import Pikaday from "pikaday";
import moment from 'moment';

import Slot from './Slot';
import Spinner from '../UI/Spinner';

import validateSlot from './validateSlot';

class Management extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            slots: [],
            loading: false,
            date: '',
            from: '',
            to: '',
            mngFromValue: '',
            mngToValue: '',
            posting: false,
            deleting: false
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
            timeFormat: 'HH:mm ',
            interval: 5,
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
            timeFormat: 'HH:mm ',
            interval: 5,
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
        this.setState({loading: true});

        axios.get('/api/availability_slot')
            .then(response => {
                this.setState({
                    slots: Object.keys(response.data).map(slot => response.data[slot]),
                    loading: false
                });
            })
            .catch(err => {
                console.log(err);
                this.setState({
                    loading: false
                })
            });
    };

    addNewSlot() {
        const {date, mngFromValue, mngToValue, slots} = this.state;

        if (date && mngFromValue && mngToValue) {
            const dateStr = moment(date).format("YYYY-MM-DD");
            const from = moment(mngFromValue).format("HH:mm");
            const to = moment(mngToValue).format("HH:mm");

            if (!validateSlot(slots, date, mngFromValue, mngToValue)) {
                alert(`You are already available on ${dateStr} between ${from} and ${to}`);
                return;
            }

            this.setState({posting: true});

            axios.post('/api/availability_slot', {
                starts_at: `${dateStr} ${from}`,
                ends_at: `${dateStr} ${to}`
            }).then(response => {
                this.setState({
                    slots: [...this.state.slots, {
                        id: response.data.id,
                        starts_at: response.data.starts_at,
                        ends_at: response.data.ends_at
                    }],
                    posting: false
                });
            }).catch(err => {
                console.log(err);
                this.setState({posting: false});
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
                        slots: [...this.state.slots.filter(slot => slot.id !== id)]
                    });
                }).catch(err => {
                console.log(err)
            });
        }
    }

    render() {
        let list = <p>You don't have any available time ranges yet.</p>;

        if (this.state.loading) {
            list = <Spinner/>;
        }

        if (this.state.slots.length) {
            list = this.state.slots.map(slot => (
                <Slot
                    key={slot.id}
                    id={slot.id}
                    date={moment(slot.starts_at).format('YYYY-MM-DD')}
                    from={moment(slot.starts_at).format('HH:mm')}
                    to={moment(slot.ends_at).format('HH:mm')}
                    onDelete={id => this.deleteClicked(id)}
                    deleting={this.state.deleting}
                />
            ));
        }

        let addNewButton = <button onClick={() => this.addNewSlot()} className="newSlot">Add new</button>;

        if (this.state.posting) {
            addNewButton = <button className="newSlot newSlot--disabled">Adding</button>;
        }

        return (
            <React.Fragment>
                <div className="newSlotContainer">
                    <div className="top">
                        <div className="top__item">
                            <label htmlFor="mngDate">Date:</label>
                            <input type="text" id="mngDate"/>
                        </div>

                        <div className="top__item">
                            <label htmlFor="mngFrom">From:</label>
                            <input type="text" id="mngFrom"/>
                            <input type="hidden" value={this.state.mngFromValue}/>

                        </div>

                        <div className="top__item">
                            <label htmlFor="mngTo">To:</label>
                            <input type="text" id="mngTo"/>
                            <input type="hidden" value={this.state.mngToValue}/>
                        </div>
                    </div>
                    {addNewButton}
                </div>

                <div className={this.state.loading ? "mngList" : null}>{this.state.slots ? list : null}</div>
            </React.Fragment>
        )
    }
}

export default Management;
import React from 'react';
import axios from 'axios';

import Slot from './Slot';
import Spinner from '../../UI/Spinner';

class AvailSlots extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            slots: [],
            loading: false,
            date: '',
            from: '',
            to: '',
            posting: false,
            deleting: false
        };
    }

    componentDidMount() {
        this.fetchSlots();
    }

    fetchSlots() {
        this.setState({loading: true});

        axios.get('/api/availability_slot')
            .then(response => {
                this.setState({
                    slots: response.data,
                    loading: false
                });

                console.log(response.data);
            })
            .catch(err => {
                console.log(err);
                this.setState({
                    loading: false
                })
            });
    };

    addNewSlot() {
        const {date, from, to} = this.state;

        if (date && from && to) {
            this.setState({posting: true});

            axios.post('/api/availability_slot', {
                starts_at: `${date} ${from}`,
                ends_at: `${date} ${to}`
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
        let list = <Spinner/>;

        if (!this.state.loading) {
            if (this.state.slots.length !== 0) {
                list = this.state.slots.map(slot => (
                    <Slot
                        key={slot.id}
                        id={slot.id}
                        date={slot.starts_at.split(' ')[0]}
                        from={slot.starts_at.split(' ')[1].substr(0, 5)}
                        to={slot.ends_at.split(' ')[1].substr(0, 5)}
                        onDelete={id => this.deleteClicked(id)}
                        deleting={this.state.deleting}
                    />
                ));
            } else {
                list = <p>You don't have any available time ranges yet.</p>;
            }
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
                            <input onChange={(e) => {
                                this.setState({date: e.target.value})
                            }} type="date" id="mngDate"/>
                        </div>

                        <div className="top__item">
                            <label htmlFor="mngFrom">From:</label>
                            <input onChange={(e) => {
                                this.setState({from: e.target.value})
                            }} type="time" id="mngFrom"/>
                        </div>

                        <div className="top__item">
                            <label htmlFor="mngTo">To:</label>
                            <input onChange={(e) => {
                                this.setState({to: e.target.value})
                            }} type="time" id="mngTo"/>
                        </div>
                    </div>
                    {addNewButton}
                </div>

                <div className={this.state.loading ? "mngList" : null}>{list}</div>
            </React.Fragment>
        )
    }
}

export default AvailSlots;
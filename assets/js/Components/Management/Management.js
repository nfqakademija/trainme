import React from 'react';
import axios from 'axios';

import Item from './Item';
import Spinner from './Spinner';

class Management extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            slots: [],
            loading: false,
            date: '',
            from: '',
            to: '',
            posting: false
        };
    }

    componentDidMount() {
        this.fetchSlots();
    }

    fetchSlots() {
        this.setState({loading: true});

        axios.get('/api/trainers/1/availabilitySlots')
            .then(response => {
                this.setState({
                    slots: response.data,
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
        const {date, from, to} = this.state;

        if (date && from && to) {
            this.setState({posting: true});
            // axios.post('someUrl', {
            //     start: `${date} ${from}`,
            //     end: `${date} ${to}`
            // }).then(response => {
            this.setState({
                slots: [...this.state.slots, {
                    start: `${date} ${from}`,
                    end: `${date} ${to}`
                }],
                posting: false
            });
            // }).catch(err => {
            //     console.log(err);
            //     this.setState({posting: false});
            // });
        } else {
            alert('Please fill in all inputs');
        }
    };

    deleteClicked(id) {
        let box = confirm('Are you sure you want to remove available schedule time?');
        if (box) {
            console.log('deleted');

            axios.delete('someUrl/' + id)
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
                    <Item
                        // key={slot.id}
                        key={Math.floor(Math.random() * 9999)}
                        // id={slot.id}
                        date={slot.start.split(' ')[0]}
                        from={slot.start.split(' ')[1].substr(0, 5)}
                        to={slot.end.split(' ')[1].substr(0, 5)}
                        onDelete={id => this.deleteClicked(id)}
                    />
                ));
            } else {
                list = <p>You don't have any available time ranges yet.</p>;
            }
        }

        let addNewButton = <button onClick={() => this.addNewSlot()} className="newSlot">Add new</button>;

        if (this.state.posting) {
            addNewButton = <button className="newSlot newSlot--disabled">Posting</button>;
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

export default Management;
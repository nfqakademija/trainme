import React from 'react';
import axios from 'axios';

import Item from './Item';
import Spinner from './Spinner';

class Management extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            slots: [],
            loading: false
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

    render() {
        let list = <Spinner/>;

        if (!this.state.loading) {
            if (this.state.slots.length !== 0) {
                list = this.state.slots.map(slot => (
                    <Item
                        key={Math.floor(Math.random() * 9999)}
                        date={slot.start.split(' ')[0]}
                        from={slot.start.split(' ')[1].substr(0, 5)}
                        to={slot.end.split(' ')[1].substr(0, 5)}
                    />
                ));
            } else {
                list = <p>You don't have any available time ranges yet.</p>;
            }
        }

        return (
            <React.Fragment>
                <div className="newSlotContainer">
                    <div className="top">
                        <div className="top__item">
                            <label htmlFor="mngDate">Date:</label>
                            <input type="date" id="mngDate"/>
                        </div>

                        <div className="top__item">
                            <label htmlFor="mngFrom">From:</label>
                            <input type="time" id="mngFrom"/>
                        </div>

                        <div className="top__item">
                            <label htmlFor="mngTo">To:</label>
                            <input type="time" id="mngTo"/>
                        </div>
                    </div>
                    <button className="newSlot">Add new</button>
                </div>

                <div className={this.state.loading ? "mngList" : null}>{list}</div>
            </React.Fragment>
        )
    }
}

export default Management;
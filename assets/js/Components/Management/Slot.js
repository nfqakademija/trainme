import React from 'react';
import axios from 'axios';
import Pikaday from "pikaday";
import moment from 'moment';

import validateSlot from "./validateSlot";

class Slot extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            edit: false,
            saving: false,
            date: this.props.date,
            from: this.props.from,
            to: this.props.to,
            dateInput: '',
            fromInput: '',
            toInput: ''
        }
    }

    editClicked() {
        this.setState({edit: true}, () => {
            const picker = new Pikaday({
                field: $('#mngEditDate')[0],
                firstDay: 1,
                defaultDate: this.state.date,
                onSelect: (date) => {
                    this.setState({dateInput: date});
                }
            });

            $('#mngEditFrom').timepicker({
                timeFormat: 'HH:mm',
                interval: 5,
                minTime: '6',
                maxTime: '23',
                dynamic: false,
                defaultTime: new Date(`${this.state.date} ${this.state.from}`),
                dropdown: true,
                scrollbar: false,
                change: (time) => {
                    const timeVal = moment(time).format('HH:mm');
                    this.setState({fromInput: timeVal});
                }
            });

            $('#mngEditTo').timepicker({
                timeFormat: 'HH:mm',
                interval: 5,
                minTime: '6',
                maxTime: '23',
                dynamic: false,
                defaultTime: new Date(`${this.state.date} ${this.state.to}`),
                dropdown: true,
                scrollbar: false,
                change: (time) => {
                    const timeVal = moment(time).format('HH:mm');
                    this.setState({toInput: timeVal});
                }
            });
        });
    };

    cancelClicked() {
        this.setState({edit: false});
    }

    saveClicked(id) {
        const {dateInput, fromInput, toInput} = this.state;
        let date = this.state.date;
        let from = this.state.from;
        let to = this.state.to;

        if (dateInput) {
            date = moment(dateInput).format('YYYY-MM-DD');
        }
        if (fromInput) {
            from = fromInput;
        }
        if (toInput) {
            to = toInput;
        }

        if (!validateSlot(this.props.slots, new Date(date), new Date(`${date} ${fromInput}`), new Date(`${date} ${toInput}`))) {
            alert(`You are already available in this period of time!`);
            return;
        }

        this.setState({saving: true});

        axios.put(`/api/availability_slot/${id}`,
            {
                'starts_at': `${date} ${from}`,
                'ends_at': `${date} ${to}`
            })
            .then(response => {
                this.setState({
                    edit: false,
                    saving: false,
                    date: date,
                    from: from,
                    to: to
                });
            }).catch(err => {
            this.setState({saving: false, edit: false});
            console.log(err);
        });
    }

    render() {
        const {date, from, to, dateInput, fromInput, toInput, edit, saving} = this.state;

        let dateField = <p>{date}</p>;
        let fromField = <p>{from}</p>;
        let toField = <p>{to}</p>;

        if (edit) {
            dateField = (<React.Fragment>
                    <input type='hidden' value={dateInput}/>
                    <input className="mngInput" type='text' defaultValue={this.state.date} id='mngEditDate'/>
                </React.Fragment>
            );

            fromField = (<React.Fragment>
                <input type='hidden' value={fromInput}/>
                <input className="mngInput" type='text' defaultValue={this.state.from} id='mngEditFrom'/>
            </React.Fragment>);

            toField = (<React.Fragment>
                <input type='hidden' value={toInput}/>
                <input className="mngInput" type='text' defaultValue={this.state.to} id='mngEditTo'/>
            </React.Fragment>);
        }

        return (
            <React.Fragment>
                <div className="managementContainer__item">
                    <div className="top">
                        <div className="top__item">
                            <p>Date:</p>
                            {dateField}
                        </div>

                        <div className="top__item">
                            <p>From:</p>
                            {fromField}
                        </div>

                        <div className="top__item">
                            <p>To:</p>
                            {toField}
                        </div>
                    </div>
                    <div className="functionsBlock">
                        <button onClick={() => this.editClicked()}
                                className="btn editButton">{saving ? 'Saving...' : 'Edit'}</button>
                        {edit ?
                            <React.Fragment>
                                <button style={{'opacity': 1}} onClick={() => this.saveClicked(this.props.id)}
                                        className="btn btnSave">{saving ? null : 'Save'}
                                </button>
                                <button style={{'opacity': 1}} onClick={() => this.cancelClicked()}
                                        className="btn btn--cancel btnDiscard">Cancel
                                </button>
                            </React.Fragment> :
                            <button style={{'opacity': 1}} onClick={() => this.props.onDelete(this.props.id)}
                                    className="btn btn--cancel btnDiscard">Remove</button>}
                    </div>
                </div>
            </React.Fragment>
        )
    }
};

export default Slot;
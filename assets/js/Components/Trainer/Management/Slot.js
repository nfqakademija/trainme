import React from 'react';
import axios from 'axios';
import Pikaday from "pikaday";
import moment from 'moment';

import validateSlot from "../../utils/validateSlot";

class Slot extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isEditing: false,
            isSaving: false,
            date: this.props.date,
            from: this.props.from,
            to: this.props.to,
            dateInput: '',
            fromInput: '',
            toInput: ''
        }
    }

    editClicked() {
        this.setState({isEditing: true}, () => {
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
                interval: 10,
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
                interval: 10,
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
        this.setState({isEditing: false});
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

        const slots = this.props.slots.filter(slot => slot.id !== id);
        if (!validateSlot(slots, new Date(date), new Date(`${date} ${from}`), new Date(`${date} ${to}`)) &&
            !(date === this.state.date && from === this.state.from && to === this.state.to)) {
            alert('You are already available in this period of time!');
            return;
        }
        this.setState({isSaving: true});
        axios.put(`/api/availability_slot/${id}`,
            {
                'starts_at': `${date} ${from}`,
                'ends_at': `${date} ${to}`
            })
            .then(response => {
                this.setState({
                    isEditing: false,
                    isSaving: false,
                    date: date,
                    from: from,
                    to: to
                });
            }).catch(err => {
            this.setState({isSaving: false, isEditing: false});
            console.log(err);
        });
    }

    render() {
        const {
            date,
            from,
            to,
            dateInput,
            fromInput,
            toInput,
            isEditing,
            isSaving
        } = this.state;
        let dateField = <p>{date}</p>;
        let fromField = <p>{from}</p>;
        let toField = <p>{to}</p>;

        if (isEditing) {
            dateField = (<React.Fragment>
                    <input type="hidden" value={dateInput}/>
                    <input className="mngInput" type="text" defaultValue={this.state.date} id="mngEditDate" autoComplete="off"/>
                </React.Fragment>
            );

            fromField = (<React.Fragment>
                <input type="hidden" value={fromInput}/>
                <input className="mngInput" type="text" defaultValue={this.state.from} id="mngEditFrom" autoComplete="off"/>
            </React.Fragment>);

            toField = (<React.Fragment>
                <input type="hidden" value={toInput}/>
                <input className="mngInput" type="text" defaultValue={this.state.to} id="mngEditTo" autoComplete="off"/>
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
                                className="btn editButton">{isSaving ? 'Saving...' : 'Edit'}</button>
                        {isEditing ?
                            <React.Fragment>
                                <button style={{'opacity': 1}} onClick={() => this.saveClicked(this.props.id)}
                                        className="btn btnSave">{isSaving ? null : 'Save'}
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
}

export default Slot;
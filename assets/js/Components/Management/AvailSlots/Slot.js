import React from 'react';
import axios from 'axios';

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
        this.setState({edit: true});
    };

    cancelClicked() {
        this.setState({edit: false});
    }

    saveClicked(id) {
        const {dateInput, fromInput, toInput} = this.state;
        let date = this.props.date;
        let from = this.props.from;
        let to = this.props.to;

        if (dateInput) {
            date = dateInput;
        }
        if (fromInput) {
            from = fromInput;
        }
        if (toInput) {
            to = toInput
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
            console.log(err)
        });
    }

    render() {
        let date = <p>{this.state.date}</p>;
        let from = <p>{this.state.from}</p>;
        let to = <p>{this.state.to}</p>;

        if (this.state.edit) {
            date = <input type='date' onChange={(e) => {
                this.setState({dateInput: e.target.value})
            }} defaultValue={this.props.date}/>;

            from = <input type='time' onChange={(e) => {
                this.setState({fromInput: e.target.value})
            }} defaultValue={this.props.from}/>;

            to = <input type='time' onChange={(e) => {
                this.setState({toInput: e.target.value})
            }} defaultValue={this.props.to}/>;
        }

        return (
            <React.Fragment>
                <div className="managementContainer__item">
                    <div className="top">
                        <div className="top__item">
                            <p>Date:</p>
                            {date}
                        </div>

                        <div className="top__item">
                            <p>From:</p>
                            {from}
                        </div>

                        <div className="top__item">
                            <p>To:</p>
                            {to}
                        </div>
                    </div>
                    <div className="functionsBlock">
                        <button onClick={() => this.editClicked()}
                                className="btn editButton">{this.state.saving ? 'Saving...' : 'Edit'}</button>
                        {this.state.edit ?
                            <React.Fragment>
                                <button style={{'opacity': 1}} onClick={() => this.saveClicked(this.props.id)}
                                        className="btn btnSave">Save
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
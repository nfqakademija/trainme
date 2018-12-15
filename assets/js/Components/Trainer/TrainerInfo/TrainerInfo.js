import React from 'react';
import axios from 'axios';

class TrainerInfo extends React.Component {
    constructor(props) {
        super(props);

        const trainer = JSON.parse(this.props.trainer);

        this.state = {
            trainer: trainer,
            count: JSON.parse(this.props.count),
            isTrainer: JSON.parse(this.props.user).includes('ROLE_TRAINER'),
            editing: false,
            primaryInfo: {
                statement: trainer.personalStatement,
                phone: trainer.phone,
                location: trainer.location
            },
            statement: trainer.personalStatement,
            phone: trainer.phone,
            location: trainer.location,
            saving: false
        };
    }

    editClicked() {
        this.setState({editing: true});
    }

    discardClicked() {
        const {primaryInfo} = this.state;

        this.setState({
            editing: false,
            statement: primaryInfo.statement,
            phone: primaryInfo.phone,
            location: primaryInfo.location
        });
    }

    saveClicked() {
        const {statement, location, phone} = this.state;
        if (!(statement && location && phone)) {
            alert('Fields cannot be empty');
            return;
        }

        this.setState({saving: true});
        axios.put('/api/trainer', {
            'personal_statement': statement,
            'phone': phone,
            'location': location
        }).then((response) => {
            this.setState({
                editing: false,
                saving: false
            });
        }).catch((error) => {
            console.log(error);
            this.setState({
                editing: false,
                saving: false
            });
        });
    }

    onStatementChange(e) {
        this.setState({statement: e.target.value});
    }

    onPhoneChange(e) {
        this.setState({phone: e.target.value});
    }

    onLocationChange(e) {
        this.setState({location: e.target.value});
    }

    render() {
        const {isTrainer, count, editing, statement, phone, location, saving} = this.state;
        let editButtons = null;

        if (isTrainer) {
            editButtons = <div className="buttonContainer">
                {!editing ? <button className="btn"
                                    onClick={() => this.editClicked()}>Edit
                </button> : <React.Fragment>
                    <button className="btn btnSave btn--editingInfo"
                            onClick={() => this.saveClicked()}>{saving ? 'Saving...' : 'Save'}</button>
                    <button className="btn btn--cancel btnDiscard" onClick={() => this.discardClicked()}>Discard
                    </button>
                </React.Fragment>}
            </div>
        }

        let statementField = <p className="trainerInfo__desc justify">{statement}</p>;
        let phoneField = <span className="infoText">{phone}</span>;
        let locationField = <span className="infoText">{location}</span>;
        let classNames = 'trainerInfo__desc';

        if (editing) {
            classNames = 'trainerInfo__desc u-between';
            statementField = <textarea className="trainerTextarea"
                                       onChange={(e) => this.onStatementChange(e)} defaultValue={statement}/>;
            phoneField = <input className="trainerInput" onChange={(e) => this.onPhoneChange(e)} type="phone"
                                defaultValue={phone}/>;
            locationField = <input className="trainerInput" onChange={(e) => this.onLocationChange(e)} type="text"
                                   defaultValue={location}/>
        }

        return <React.Fragment>
            {statementField}
            <p title="Phone" className={classNames}>
                <i className="fas fa-phone trainer__icon"></i>
                {phoneField}
            </p>
            <p title="Location" className={classNames}>
                <i className="fas fa-map-marker-alt trainer__icon"></i>
                {locationField}
            </p>
            <p title="Workouts on TrainMe" className="trainerInfo__desc">
                <i className="fas fa-dumbbell trainer__icon"></i>
                <span className="infoText">{count}</span>
            </p>
            {editButtons}
        </React.Fragment>
    }
}

export default TrainerInfo;
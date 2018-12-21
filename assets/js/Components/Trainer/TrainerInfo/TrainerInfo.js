import React from 'react';
import axios from 'axios';

import EditButtons from "../../UI/EditButtons";
import InputField from "./InputField";

class TrainerInfo extends React.Component {
    constructor(props) {
        super(props);
        const trainer = JSON.parse(this.props.trainer);
        this.state = {
            trainer: trainer,
            count: JSON.parse(this.props.count),
            isTrainer: JSON.parse(this.props.user).includes('ROLE_TRAINER'),
            isEditing: false,
            primaryInfo: {
                statement: trainer.personalStatement,
                phone: trainer.phone,
                location: trainer.location
            },
            statement: trainer.personalStatement,
            phone: trainer.phone,
            location: trainer.location,
            isSaving: false
        };
    }

    editClicked() {
        this.setState({isEditing: true});
    }

    discardClicked() {
        const {primaryInfo} = this.state;
        this.setState({
            isEditing: false,
            statement: primaryInfo.statement,
            phone: primaryInfo.phone,
            location: primaryInfo.location
        });
    }

    saveClicked() {
        const {
            statement,
            location,
            phone
        } = this.state;
        if (!(statement && location && phone)) {
            alert('Fields cannot be empty');
            return;
        }
        this.setState({isSaving: true});
        axios.put('/api/trainer', {
            'personal_statement': statement,
            'phone': phone,
            'location': location
        }).then((response) => {
            this.setState({
                isEditing: false,
                isSaving: false
            });
        }).catch((error) => {
            console.log(error);
            this.setState({
                isEditing: false,
                isSaving: false
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
        const {
            isTrainer,
            count,
            isEditing,
            statement,
            phone,
            location,
            isSaving
        } = this.state;
        let statementField = <p className="trainerInfo__desc justify">{statement}</p>;
        let phoneField = <span className="infoText">{phone}</span>;
        let locationField = <span className="infoText">{location}</span>;

        if (isEditing) {
            statementField = <textarea className="trainerTextarea"
                                       onChange={(e) => this.onStatementChange(e)}
                                       defaultValue={statement.replace(/(\r\n|\n|\r)/gm, '').replace(/ {1,}/g, ' ')}/>;
            phoneField = <input className="trainerInput" onChange={(e) => this.onPhoneChange(e)} type="phone"
                                defaultValue={phone}/>;
            locationField = <input className="trainerInput" onChange={(e) => this.onLocationChange(e)} type="text"
                                   defaultValue={location}/>
        }

        return (
            <React.Fragment>
                {statementField}
                <InputField isEditing={isEditing} title="Phone" phone>{phoneField}</InputField>
                <InputField isEditing={isEditing} title="Location" location>{locationField}</InputField>
                <InputField isEditing={isEditing} className="trainerInfo__desc" title="Workouts on TrainMe" workouts>
                    <span className="infoText">{count}</span>
                </InputField>
                {isTrainer &&
                <EditButtons
                    isEditing={isEditing}
                    isSaving={isSaving} onEdit={() => this.editClicked()}
                    onSave={() => this.saveClicked()}
                    onDiscard={() => this.discardClicked()}
                />}
            </React.Fragment>);
    }
}

export default TrainerInfo;
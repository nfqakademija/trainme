import React from 'react';

const InputField = props => {
    let icon = null;
    let classNames = 'trainerInfo__desc';

    if (props.phone) {
        icon = <i className="fas fa-phone trainer__icon"></i>;
    }
    if (props.workouts) {
        icon = <i className="fas fa-dumbbell trainer__icon"></i>;
    }
    if (props.location) {
        icon = <i className="fas fa-map-marker-alt trainer__icon"></i>;
    }
    if (props.isEditing) {
        classNames = 'trainerInfo__desc u-between';
    }
    return (
        <p title={props.title} className={props.className ? props.className : classNames}>
            {icon}
            {props.children}
        </p>);
};

export default InputField;
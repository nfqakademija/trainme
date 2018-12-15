import React from 'react';

const ModalContent = props => {
    let modal = props.children;
    if (props.booking) {
        modal = <React.Fragment>
            <div className="modalInputGroup">
                <label htmlFor="bookDate">Date:</label>
                <input className="bookInput" id="bookDate" type="date" disabled
                       defaultValue={props.defaultDate}/>
            </div>
            <div className="modalInputGroup">
                <label htmlFor="bookFrom">From:</label>
                <input className="bookInput" id="bookFrom" type="text"/>
                <input type="hidden" value={props.bookFromValue}/>
            </div>
            <div className="modalInputGroup">
                <label htmlFor="bookTo">To:</label>
                <input className="bookInput" id="bookTo" type="text"/>
                <input type="hidden" value={props.bookToValue}/>
            </div>
        </React.Fragment>
    }

    if (props.customer) {
        modal = <React.Fragment>
            <div className="modalInputGroup">
                <p>Date:</p>
                <p>{props.workoutDate}</p>
            </div>
            <div className="modalInputGroup">
                <p>Starts:</p>
                <p>{props.workoutStarts}</p>
            </div>
            <div className="modalInputGroup">
                <p>Ends:</p>
                <p>{props.workoutEnds}</p>
            </div>
        </React.Fragment>
    }

    if (props.customerWorkout) {
        modal = <React.Fragment>
            <p className="calModal__info">Name: {props.trainerName}</p>
            <p className="calModal__info">Phone: {props.trainerPhone}</p>
        </React.Fragment>
    }
    return (<div className={`calModal__middle ${props.col ? 'calModal__middle--col' : 'calModal__middle--row'}`}>
        {modal}
    </div>)
};

export default ModalContent;
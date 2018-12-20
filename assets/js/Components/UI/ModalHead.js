import React from 'react';

const ModalHead = props => (
    <React.Fragment>
        <div className="calModal__head">
            <h3 className="blackTitle blackTitle--fSmaller blackTitle--modal">{props.children}</h3>
            <span onClick={props.onCloseClick} className="calModal__close">&times;</span>
        </div>
        <hr className="calModal__bar"/>
    </React.Fragment>
);

export default ModalHead;
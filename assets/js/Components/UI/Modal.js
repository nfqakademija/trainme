import React from 'react';

const Modal = (props) => (
    <div className="calModal">
        <div className="calModal__content">
            {props.children}
        </div>
    </div>
);

export default Modal;
import React from 'react';

const AddSlot = props => (
    <div className="newSlotContainer">
        <div className="top">
            <div className="top__item">
                <label htmlFor="mngDate">Date:</label>
                <input className="mngInput" type="text" id="mngDate"/>
            </div>

            <div className="top__item">
                <label htmlFor="mngFrom">From:</label>
                <input className="mngInput" type="text" id="mngFrom"/>
                <input type="hidden" value={props.from}/>

            </div>

            <div className="top__item">
                <label htmlFor="mngTo">To:</label>
                <input className="mngInput" type="text" id="mngTo"/>
                <input type="hidden" value={props.to}/>
            </div>
        </div>
        {props.children}
    </div>
);

export default AddSlot;
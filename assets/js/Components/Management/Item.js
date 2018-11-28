import React from 'react';

const Item = (props) => {
    return (
        <React.Fragment>
            <div className="managementContainer__item">
                <div className="top">
                    <div className="top__item">
                        <p>Date:</p>
                        <p>{props.date}</p>
                    </div>

                    <div className="top__item">
                        <p>From:</p>
                        <p>{props.from}</p>
                    </div>

                    <div className="top__item">
                        <p>To:</p>
                        <p>{props.to}</p>
                    </div>
                </div>
                <div className="functionsBlock">
                    <button className="btn editButton">Edit</button>
                </div>
            </div>
        </React.Fragment>
    )
};

export default Item;
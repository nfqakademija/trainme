import React from 'react';

const Message = props => {
    let message;
    switch (props.type) {
        case 'info':
            message = <section className="info info--customer">
                <p className="info__text">
                    <i className="fas fa-info-circle u-mgRt fa-info-circle--info"></i>
                    {props.children}
                </p>
            </section>;
            break;
        case 'danger':
            message = <section className="info info--customer info--danger">
                <p className="info__text">
                    <i className="fas fa-info-circle u-mgRt fa-info-circle--danger"></i>
                    {props.children}
                </p>
            </section>;
            break;
        case 'success':
            message = <section className="info info--customer info--success">
                <p className="info__text">
                    <i className="fas fa-info-circle u-mgRt fa-info-circle--success"></i>
                    {props.children}
                </p>
            </section>;
            break;
    }
    return (
        <React.Fragment>{message}</React.Fragment>
    )
};

export default Message;
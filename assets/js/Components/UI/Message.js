import React from 'react';

class Message extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        let message;
        switch (this.props.type) {
            case 'info':
                message = <section className="info info--customer">
                    <p className="info__text">
                        <i className="fas fa-info-circle u-mgRt fa-info-circle--info"></i>
                        {this.props.children}
                    </p>
                </section>;
                break;
            case 'danger':
                message = <section className="info info--customer info--danger">
                    <p className="info__text">
                        <i className="fas fa-info-circle u-mgRt fa-info-circle--danger"></i>
                        {this.props.children}
                    </p>
                </section>;
                break;
            case 'success':
                message = <section className="info info--customer info--success">
                    <p className="info__text">
                        <i className="fas fa-info-circle u-mgRt fa-info-circle--success"></i>
                        {this.props.children}
                    </p>
                </section>;
                break;
        }
        return (
            <React.Fragment>{message}</React.Fragment>
        )
    }
}

export default Message;
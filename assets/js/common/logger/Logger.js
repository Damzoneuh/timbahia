import React, {Component} from 'react';

export default class Logger extends Component{
    constructor(props) {
        super(props);
    }


    render() {
        const {message, type} = this.props;
        return (
            <div className={"alert alert-" + type + " alert-dismissible fade show position-fixed position-logger m-5"} role="alert">
                {message}
                <button type="button" className="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        );
    }

}
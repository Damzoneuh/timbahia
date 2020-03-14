import React, {Component} from 'react';

export default class Loader extends Component{
    constructor(props) {
        super(props);

    }

    render() {
        return(
            <div className="vh-100 d-flex align-items-center justify-content-center">
                <div className="spinner-border text-success" role="status">

                </div>
            </div>
        )
    }

}
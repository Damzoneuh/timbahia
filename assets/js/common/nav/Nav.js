import React, {Component} from 'react';
import reactDOM from 'react-dom';

export default class Nav extends Component{
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="text-danger">Hello world</div>
        )
    }
}

reactDOM.render(<Nav />, document.getElementById('nav'));
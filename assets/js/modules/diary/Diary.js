import React, {Component} from 'react';
import ReactDOM from 'react-dom';

export default class Diary extends Component{
    constructor(props) {
        super(props);
    }


    render() {
        return (
            <div>
                hello
            </div>
        );
    }
}

ReactDOM.render(<Diary/>, document.getElementById('diary'));
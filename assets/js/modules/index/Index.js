import React, {Component} from 'react';
import reactDOM from 'react-dom';
import Loader from "../../common/loader/Loader";
import axios from 'axios';
import AssocSwitch from "./AssocSwitch";

export default class Index extends Component{
    constructor(props) {
        super(props);
        this.state = {
            assocs: [],
            isLoaded: false,
            header: null
        }
    }

    componentDidMount() {
        axios.get('/api/header')
            .then(res => {
                this.setState({
                    header: res.data
                });
                axios.get('/api/assoc')
                    .then(res => {
                        this.setState({
                            assocs: res.data,
                            isLoaded: true
                        })
                    })
            })
    }

    render() {
        const {isLoaded, assocs, header} = this.state;
        if (isLoaded){
            return(
                <div>
                    <div className="banner mt-5 pt-5">
                    </div>
                    <div className="container-fluid">
                        <div className="row">
                            <div className="col">
                                <div className="p-5">
                                    <h1 className="text-center text-success mt-5 mb-5">{header}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="luciano mt-5 mb-5">
                    </div>
                    <div className="container-fluid">
                        <AssocSwitch assocs={assocs}/>
                    </div>
                </div>
            )
        }
        else{
            return (
                <Loader />
            )
        }
    }
}

reactDOM.render(<Index />, document.getElementById('index'));
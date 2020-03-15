import React, {Component} from 'react';
import axios from 'axios';
import Loader from "../../common/loader/Loader";
import Logger from "../../common/logger/Logger";

export default class DiarySelected extends Component{
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            diary: null,
            message: null,
            type: null
        };
        this.handleBack = this.handleBack.bind(this);
    }

    componentDidMount(){
        axios.get('/api/diary/' + this.props.id)
            .then(res => {
                this.setState({
                    isLoaded: true,
                    diary: res.data
                })
            })
            .catch(e => {
                this.setState({
                    message: 'La publication n\'existe plus',
                    type: 'danger'
                })
            })
    }

    handleBack(){
        this.props.handleBack();
        this.setState({
            isLoaded: false,
            diary: null,
            message: null,
            type: null
        })
    }


    render() {
        const {isLoaded, type, diary, message} = this.state;
        if (!isLoaded){
            return (
                <Loader/>
            )
        }
        else{
            return (
                <div className="container-fluid mt-5">
                    <div className="row mt-5 mb-5">
                        <div className="col-12 mt-5">
                            {message && type ? <Logger message={message} type={type}/> : ''}
                        </div>
                        <div className="col-12">
                            {diary ?
                                <div className="p-5 bg-footer rounded shadow">
                                    <div className="text-center"><button className="btn btn-group btn-outline-light" onClick={this.handleBack}>Retour</button></div>
                                    {diary.img ?
                                        <div className="row text-white">
                                            <div className="col-md-6 col-12">
                                                <img src={'/img/render/' + diary.img} alt={diary.title} className="w-thumb"/>
                                            </div>
                                            <div className="col-md-6 col-12">
                                                <h4 className="text-center">
                                                    {diary.title}
                                                </h4>
                                            </div>
                                            <div className="col-12">
                                                <div className="text-center">
                                                    {diary.date}
                                                </div>
                                            </div>
                                            <div className="mt-5">
                                                {diary.text}
                                            </div>
                                        </div>
                                        :
                                        <div className="row text-white">
                                            <div className="col-md-6 col-12">
                                                <h4 className="text-center">
                                                    {diary.title}
                                                </h4>
                                            </div>
                                            <div className="col-md-6 col-12">
                                                <div className="text-center">
                                                    {diary.date}
                                                </div>
                                            </div>
                                            <div className="mt-5">
                                                {diary.text}
                                            </div>
                                        </div>
                                    }
                                </div>
                                : ''}
                        </div>
                    </div>
                </div>
            );
        }
    }
}
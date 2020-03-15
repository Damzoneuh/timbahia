import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Loader from "../../common/loader/Loader";
import Logger from "../../common/logger/Logger";
import DiarySelected from "./DiarySelected";

export default class Diary extends Component{
    constructor(props) {
        super(props);
        this.state = {
            message: null,
            type: null,
            isLoaded: false,
            diaries: null,
            selected: null
        };
        this.handleBack = this.handleBack.bind(this);
        this.handleViewMore = this.handleViewMore.bind(this);
    }

    componentDidMount(){
        axios.get('/api/diary')
            .then(res => {
                if (res.data.length > 0){
                    this.setState({
                        isLoaded: true,
                        diaries: res.data
                    })
                }
                else {
                    this.setState({
                        type: 'danger',
                        message: 'Auncune actualité pour le moment',
                        isLoaded: true
                    })
                }
            })
            .catch(e => {
                this.setState({
                    type: 'danger',
                    message: 'Auncune actualité pour le moment',
                    isLoaded: true
                })
            })
    }

    handleViewMore(id){
        this.setState({
            selected: id
        })
    }

    handleBack(){
        this.setState({
            selected: null
        })
    }

    render() {
        const {message, type, isLoaded, diaries, selected} = this.state;
        if (!isLoaded){
            return (
                <Loader/>
            )
        }
        if (selected){
            return (
                <DiarySelected id={selected} handleBack={this.handleBack}/>
            )
        }
        else {
            return(
                <div className="container-fluid mt-5">
                    <div className="row align-items-strech">
                        <div className="col-12">
                            {message && type ? <Logger message={message} type={type}/> : ''}
                        </div>
                        {diaries && diaries.length > 0 ? diaries.map(d => {
                            return(
                                <div className="col-md-6 col-12" key={d.id}>
                                    <div className="p-5 mt-5 mb-5 bg-footer rounded shadow">
                                        {d.img ?
                                            <div className="row align-items center">
                                                <div className="col-md-6 col-12">
                                                    <img src={'/img/render/' + d.img} alt={d.title} className="w-thumb"/>
                                                </div>
                                                <div className="col-md-6 col-12">
                                                    <h3 className="text-white text-center">{d.title}</h3>
                                                </div>
                                                <div className="col-12">
                                                    <div className="text-center text-white">{d.date}</div>
                                                </div>
                                            </div>
                                            :
                                            <div className="row align-items center">
                                                <div className="col-md-6 col-12">
                                                    <h3 className="text-white text-center">{d.title}</h3>
                                                </div>
                                                <div className="col-md-6 col-12 d-flex align-items-center justify-content-center">
                                                    <div className="text-center text-white">{d.date}</div>
                                                </div>
                                            </div>
                                        }
                                        <div className="text-center">
                                            <button className="btn btn-group btn-outline-light" onClick={() => this.handleViewMore(d.id)}>Voir plus</button>
                                        </div>
                                    </div>
                                </div>
                            )
                        }) : ''}

                    </div>
                </div>
            )
        }
    }
}

ReactDOM.render(<Diary/>, document.getElementById('diary'));
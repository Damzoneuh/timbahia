import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Loader from "../../common/loader/Loader";
import Logger from "../../common/logger/Logger";

export default class News extends Component{
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            news: null,
            message: null,
            type: null,
            selected: null
        };
        this.handleViewMore = this.handleViewMore.bind(this);
        this.handleBack = this.handleBack.bind(this);
    }

    componentDidMount(){
        axios.get('/api/news')
            .then(res => {
                if (res.data.length > 0){
                    this.setState({
                        isLoaded: true,
                        news: res.data
                    })
                }
                else {
                    this.setState({
                        isLoaded: true,
                        message: 'Aucune actualité n\'est disponible',
                        type: 'danger'
                    })
                }

            })
            .catch(e => {
                this.setState({
                    isLoaded: true,
                    message: 'Aucune actualité n\'est disponible',
                    type: 'danger'
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
        const {isLoaded, message, news, type} = this.state;
        if (!isLoaded){
            return <Loader/>
        }
        else {
            return (
                <div className="container-fluid mt-5">
                    <div className="row">
                        <div className="col-12">
                            {message && type ? <Logger message={message} type={type}/> : ''}
                        </div>
                        {news && news.length > 0 ? news.map(n => {
                            return (
                                <div className="col-md-6 col-sm-12 mt-5 mb-5" key={n.id}>
                                    <div className="bg-footer p-5 rounded text-white">
                                        <div className="d-flex justify-content-between align-items-center p-5">
                                            {n.img ? <img src={'/img/render/' + n.id} alt={n.title} /> : '' }
                                            <h3 className="font-weight-bold text-center">{n.title.toUpperCase()}</h3>
                                        </div>
                                        <div className="text-center mt-5">
                                            <button className="btn btn-group btn-outline-light" >Voir plus</button>
                                        </div>
                                    </div>
                                </div>
                            )
                        }) : ''}
                    </div>
                </div>
            );
        }
    }
}

ReactDOM.render(<News/>, document.getElementById('news'));
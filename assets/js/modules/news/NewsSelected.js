import React, {Component} from 'react';
import axios from 'axios';
import Loader from "../../common/loader/Loader";
import Logger from "../../common/logger/Logger";

export default class NewsSelected extends Component{
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            news: null,
            message: null,
            type: null
        };
        this.handleBack = this.handleBack.bind(this);
    }

    componentDidMount(){
        axios.get('/api/news/' + this.props.id)
            .then(res => {
                this.setState({
                    news: res.data,
                    isLoaded: true
                })
            })
            .catch(e => {
                this.setState({
                    message: 'La publication n\'est plus disponible',
                    type: 'danger'
                })
            })
    }

    handleBack(){
        this.props.handleBack();
        this.setState({
            isLoaded: false,
            news: null,
            message: null,
            type: null
        })
    }


    render() {
        const {message, type, isLoaded, news} = this.state;
        if (!isLoaded){
            return(
                <div className="container-fluid">
                    <div className="row">
                        <div className="col">
                            <Loader/>
                        </div>
                    </div>
                </div>
            )
        }
       else{
           return (
               <div className="container-fluid mt-5">
                   <div className="row">
                       <div className="col-12">
                           {message && type ? <Logger message={message} type={type}/> : ''}
                       </div>
                       <div className="col">
                           <div className="bg-footer mt-5 mb-5 p-5">
                               <div className="row">
                                   <div className="col-12 text-center">
                                       <button className="btn btn-group btn-outline-light mb-5" onClick={() => this.handleBack()}>Retour</button>
                                   </div>
                                   {news.img ?
                                       <div className="col-12">
                                           <div className="d-flex justify-content-between align-items-center">
                                               <img src={'/img/render/' + news.img} alt={news.title} className="w-thumb"/>
                                               <h3 className="text-white">{news.title.toUpperCase()}</h3>
                                           </div>
                                       </div>
                                           :
                                       <div className="col-12">
                                           <h3 className="text-white text-center">{news.title.toUpperCase()}</h3>
                                       </div>
                                           }
                                       <div className="col-12 mt-5 text-white text-center">
                                           {news.text}
                                       </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           )
        }
    }


}
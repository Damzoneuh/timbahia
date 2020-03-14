import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Logger from "../logger/Logger";
import fb from '../../../img/fb.png';
export default class Footer extends Component{
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            links: null,
            forms: null,
            tab: 0,
            senderEmail: null,
            message: null,
            log: null,
            logType: null
        };
        this.handleChangeMessage = this.handleChangeMessage.bind(this);
        this.handleMessageSend = this.handleMessageSend.bind(this);
        this.handleChangeTab = this.handleChangeTab.bind(this);
    }

    componentDidMount(){
        axios.get('api/nav/link')
            .then(res => {
                this.setState({
                    links: res.data
                });
                axios.get('/api/footer/form')
                    .then(res => {
                        this.setState({
                            forms: res.data,
                            tab: res.data[0].id,
                            isLoaded: true
                        })
                    })
            })
    }

    handleChangeMessage(e){
        this.setState({
            [e.target.name]: e.target.value
        })
    }

    handleMessageSend(e){
        e.preventDefault();
        axios.post('/api/contact/send', {
            target: this.state.tab === 1 ? this.state.forms[1].email : this.state.forms[0].email,
            text: this.state.message,
            sender: this.state.senderEmail
        })
            .then(res => {
                console.log(this.state.forms[this.state.tab]);
                if (res.data){
                    this.setState({
                        message: null,
                        senderEmail: null,
                        log: 'Votre message à bien été envoyé',
                        logType: 'success'
                    })
                }
                else {
                    this.setState({
                        log: 'Une erreur est survenue lors de l\'envoi de votre message',
                        logType: 'danger'
                    })
                }
            })
    }

    handleChangeTab(tab){
        this.setState({
            tab: tab
        })
    }

    render() {
        const {isLoaded, links, forms, tab, logType, log} = this.state;
        if (isLoaded && links){
            return(
                <footer className="bg-footer">
                    {log && logType ? <Logger message={log} type={logType}/> : ''}
                    <div className="container-fluid">
                        <div className="row">
                            <div className="col-sm-12 col-md-6">
                                <div className="p-5">
                                    <ul className="nav flex-column shadow mb-5">
                                        {links.left.map(l => {
                                            return(
                                                <li key={l.id} className="nav-item"><a href={l.path} className="text-white nav-link" title={l.name}>{l.name}</a> </li>
                                            )
                                        })}
                                    </ul>
                                    <div>
                                        <a href="https://www.facebook.com/100pour100BAHIA/"
                                           title="facebook 100% bahia" className="d-flex justify-content-between align-items-center text-white shadow p-2" target="_blank">
                                            <img src={fb} alt="facebook" className="w-logo"/> Lyon
                                        </a>
                                    </div>
                                    <div>
                                        <a href="https://www.facebook.com/CapoeiraAnnecyVamosVadiar/"
                                           title="facebook 100% bahia" className="d-flex justify-content-between align-items-center text-white shadow p-2" target="_blank">
                                            <img src={fb} alt="facebook" className="w-logo"/> Annecy
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div className="col-sm-12 col-md-6">
                                <div className="p-5">
                                    <ul className="nav nav-tabs">
                                        <li className="nav-item mb-footer">
                                            <p className={tab === 1 ? 'nav-link link active' : 'nav-link link'} onClick={() => this.handleChangeTab(1)}>Annecy</p>
                                        </li>
                                        <li className="nav-item mb-footer">
                                            <p className={tab === 2 ? 'nav-link link active' : 'nav-link link'} onClick={() => this.handleChangeTab(2)}>Lyon</p>
                                        </li>
                                    </ul>
                                    <h4 className="text-center text-white mt-5">Nous contacter</h4>
                                    {forms.map(form => {
                                        return(
                                            <form className={tab === form.id ? 'form' : 'd-none'} key={form.id} onChange={this.handleChangeMessage} onSubmit={this.handleMessageSend}>
                                                <input name="email" type="hidden" value={form.email} />
                                                <div className="form-group mt-2">
                                                    <label htmlFor={form.id + "-senderEmail"} className="text-white">Votre email</label>
                                                    <input name="senderEmail" type="text" className="form-control" id={form.id + "-senderEmail"} required={true}/>
                                                </div>
                                                <div className="form-group">
                                                    <textarea placeholder="Votre message" className="form-control" rows={3} name="message" required={true}></textarea>
                                                </div>
                                                <div className="text-center">
                                                    <button className="btn btn-group btn-outline-light">Envoyer</button>
                                                </div>
                                            </form>
                                        )
                                    })}
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            )
        }
        else {
            return (<div> </div>)
        }
    }
}

ReactDOM.render(<Footer />, document.getElementById('footer'));

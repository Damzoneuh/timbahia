import React, {Component} from 'react';
import reactDOM from 'react-dom';
import logo from '../../../img/logo.jpg';
import axios from 'axios';
const el = document.getElementById('nav');

export default class Nav extends Component{
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            links : []
        }
    }

    componentDidMount() {
        axios.get('api/nav/link')
            .then(res => {
                this.setState({
                    isLoaded: true,
                    links: res.data
                })
            })
    }

    render() {
        const {isLoaded, links} = this.state;
        if (isLoaded){
            return (
                <nav className="navbar navbar-expand-lg fixed-top bg-light navbar-light justify-content-between border-bottom border-success shadow" role="navigation">
                    <button className="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                    <a href="/" className="navbar-brand p-0 m-0"><img src={logo} alt="logo" className="w-logo"/></a>
                    <div className="collapse navbar-collapse" id="navbarToggler">
                        <ul className="navbar-nav mr-auto mt-2 mt-lg-0">
                            {links.left.map(l => {
                                return (
                                    <li className="nav-item" key={l.id}>
                                        <a className="nav-link text-success" href={l.path}> {l.name}</a>
                                    </li>
                                )
                            })}
                        </ul>
                    </div>
                    <div className="collapse navbar-collapse justify-content-end" id="navbarToggler">
                        <ul className="navbar-nav mt-2 mt-lg-0 ">
                            {links.right.map(l => {
                                if (el.dataset.connected && l.path === '/logout'){
                                    return (
                                        <li className="nav-item" key={l.id}>
                                            <a className="nav-link text-success" href={l.path}> {l.name}</a>
                                        </li>
                                    )
                                }
                                if (!el.dataset.connected && l.path === '/login'){
                                    return (
                                        <li className="nav-item" key={l.id}>
                                            <a className="nav-link text-success" href={l.path}> {l.name}</a>
                                        </li>
                                    )
                                }
                            })}
                        </ul>
                    </div>
                </nav>
            )
        }
        else {
            return (
                <div> </div>
            )
        }
    }
}

reactDOM.render(<Nav />, document.getElementById('nav'));
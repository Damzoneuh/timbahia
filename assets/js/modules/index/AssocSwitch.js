import React, {Component} from 'react';
import logoLyon from '../../../img/logo-lyon.png';
import logoAnnecy from '../../../img/vamosvadiar.jpg';

export default class AssocSwitch extends Component{
    constructor(props) {
        super(props);

    }

    render() {
        const {assocs} = this.props;
        if (assocs && assocs.length > 0){
            return (
                <div className="row align-items-stretch">
                    {assocs.map(a => {
                        return (
                            <div className="col mt-5 mb-5" key={a.id}>
                                <div className="text-white text-center bg-footer p-5 rounded shadow h-100 ">
                                    <div className="d-flex justify-content-between align-items-center mt-2 mb-2">
                                        <div><img src={a.city === 'Lyon' ? logoLyon : logoAnnecy } alt="logo" className="w-logo-details mt-2"/></div>
                                        <div>
                                            <h2>{a.city}</h2>
                                            <h3>{a.name}</h3>
                                        </div>
                                    </div>
                                    <table className="table text-white mt-5">
                                        <thead className="bg-dark">
                                            <tr>
                                                <th scope="col">
                                                    Jour
                                                </th>
                                                <th scope="col">
                                                    Début
                                                </th >
                                                <th scope="col">
                                                    Fin
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {a.hours.map((h,key) => {
                                            return (
                                                <tr key={key}>
                                                    <td>{h.day}</td>
                                                    <td>{h.start}</td>
                                                    <td>{h.end}</td>
                                                </tr>
                                            )
                                        })}
                                        </tbody>
                                    </table>
                                    <div className="text-center">
                                        <a className="btn btn-group btn-outline-light" href="#contact-us" >Nous rejoindre</a>
                                    </div>
                                </div>
                            </div>
                        )
                    })}
                </div>
            )
        }
        else {
            return(
                <div> </div>
            )
        }
    }
}
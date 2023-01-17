import React, { Component } from 'react';
import { toCamelCase, toSnakeCase, toPascalCase, toTituloCase, toSnakePluralCase, removerAcentos } from '../Util';
import '../../css/app.css';
import data from '../data.json';
import plural from 'pluralize-ptbr';
import Atributos from './Atributos';


export default class Crud extends Component {
    constructor(props) {
        super(props);
        this.state = {
            modelo: localStorage.getItem('modelo')? JSON.parse(localStorage.getItem("modelo")):"",
            modelo_logico: localStorage.getItem('modelo')? JSON.parse(localStorage.getItem("modelo")):"",
            nivel_aplicacao: localStorage.getItem('nivel_aplicacao')? JSON.parse(localStorage.getItem("nivel_aplicacao")):"",
            fornecedor: localStorage.getItem('fornecedor')? JSON.parse(localStorage.getItem("fornecedor")):"",
            pacote: localStorage.getItem('pacote')? JSON.parse(localStorage.getItem("pacote")):"",
            tabela: localStorage.getItem('tabela')? JSON.parse(localStorage.getItem("tabela")):"",
            id_automatico:1,

            atributos: localStorage.getItem('atributos')? JSON.parse(localStorage.getItem("atributos")): data.atributos
        };
    }

    handleText = e => {
        const { name, value } = e.target;

        this.setState({
            [name]: value
        });
        localStorage.setItem(name, value);
    }

    handleRadioChange = e => {
        const { name, value } = e.target;

        this.setState({
            [name]: value
        });
        localStorage.setItem(name, value);
    }


    handleModelo = e => {
        const { name, value } = e.target;

        this.setState({
            modelo: value,
            modelo_logico: toPascalCase(value),
            tabela: toSnakePluralCase(value)
        });
        localStorage.setItem("modelo",value);
        localStorage.setItem("modelo_logico",toPascalCase(value));
        localStorage.setItem("tabela",toSnakePluralCase(value));
    }

    handleTextProperChange = e => {
        const { name, value } = e.target;

        this.setState({
            [name]: toTituloCase(value)


        });
        localStorage.setItem(name, value);
    }

    adicionarAtributo(atributo){

        localStorage.setItem("atributos", JSON.stringify(atributos));
    }
    adicionarAtributo(atributo){
        atributo = "";
        localStorage.setItem("atributos", JSON.stringify(atributos));
    }
    render() {
        return (
            <div className="container">
                <div className="row">
                    <div className='col-12'>
                        <h2>Crud de Aplicação</h2>
                    </div>
                </div>
                <div className="row">
                    <div className="col-6 ">
                        <div className="custom-control custom-radio custom-control-inline">
                            <input
                                id="aplicacao"
                                value="aplicacao"
                                name="nivel_aplicacao"
                                className='custom-control-input'
                                type="radio"
                                checked={this.state.nivel_aplicacao === 'aplicacao' ? 'checked' : ''}
                                onChange={this.handleRadioChange}
                            />
                            <label className="custom-control-label" htmlFor="aplicacao">Aplicação</label>
                        </div>
                        <div className="custom-control custom-radio custom-control-inline">
                            <input
                                id="pacote"
                                value="pacote"
                                name="nivel_aplicacao"
                                type="radio"
                                className='custom-control-input'
                                checked={this.state.nivel_aplicacao === 'pacote' ? 'checked' : ''}
                                onChange={this.handleRadioChange}
                            />
                            <label className="custom-control-label" htmlFor="pacote">Pacote</label>
                        </div>
                    </div>
                </div>
                <div className={this.state.nivel_aplicacao === 'pacote' ? 'row visible' : 'row invisible'}>

                    <div className="col-6 form-group">

                        <input
                            id="fornecedor"
                            className='form-control'
                            placeholder="fornecedor"
                            name="fornecedor"
                            type="text"
                            onChange={this.handleTextProperChange}
                            value={this.state.fornecedor}
                        />
                    </div>
                    <div className="col-6 pl-5 form-group">
                        <input
                            id="pacote"
                            className='form-control'
                            placeholder="pacote"
                            name="pacote"
                            type="text"
                            onChange={this.handleTextProperChange}
                            value={this.state.pacote}
                        />
                    </div>
                </div>
                <div className='row'>
                    <div className="col-6 form-group">
                        <input
                            id="modelo"
                            className='form-control'
                            placeholder="modelo"
                            name="modelo"
                            type="text"
                            onChange={this.handleModelo}
                            value={this.state.modelo}
                        />
                    </div>
                    <div className="col-6 pl-5 form-group">
                        <input
                            id="tabela"
                            className='form-control'
                            placeholder="tabela"
                            name="tabela"
                            type="text"
                            onChange={this.handleText}
                            value={this.state.tabela}
                        />
                    </div>
                </div>
                <div className='row'>
                    <div className="col-6 form-group">
                        <input
                            id="modelo_logico"
                            className='form-control'
                            placeholder="modelo_logico"
                            name="modelo_logico"
                            type="text"
                            onChange={this.handleText}
                            value={this.state.modelo_logico}
                        />
                    </div>

                </div>
                <div className='row'>
                    <div className='col-12'>
                        <Atributos atributosItems={this.state.atributos} />
                    </div>
                </div>
            </div>
        );
    }
}

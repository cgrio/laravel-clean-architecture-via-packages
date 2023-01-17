import React, { Component } from "react";
import Modal from 'react-modal';

export default class Atributos extends Component {
    construct() {

    }
    adicionarAtributo = (e) => {
        novo_atributo = {
            "_id": 0,
            "nome": "",
            "tipo": "string",
            "tamanho": 255,
            "casas_decimais": 0,
            "obrigatorio": 0,
            "aceita_nulo": 1,
            "indexavel": 0,
            "visivel_no_grid": 1,
            "pesquisavel": 1,
            "referencia_modelo": "",
            "editavel": 1
        };
        novo_atributo._id = this.props.atributosItems.length + 1;
        this.props.atributosItems.concat(novo_atributo);
    }
    editarAtributo = (valor) => {
        console.log(valor);
    }
    removerAtributo = (valor) => {

    }
    render() {
        const { atributosItems } = this.props;
        const atributo = null;
        return (
            <div>
                {atributosItems.length === 0 ? (
                    <div className="atributo atributo-header">Atributos está vazio</div>
                ) : (
                        <div className="atributo atributo-header">
                            O modelo tem {atributosItems.length} de atributos{" "}
                        </div>
                    )}
                <div>
                    <div className="atributo">
                        <ul className="atributo-items">
                            {atributosItems.map((item) => (
                                <li key={item._id}>
                                    <div>


                                        <p>{item.referencia_modelo}</p>

                                    </div>
                                    <div>
                                        <div>{item.nome}</div>
                                        <div className="right">

                                            <button
                                                className="button"
                                                onClick={() => this.props.removeFromAtributos(item)}
                                            >
                                                Remove
                      </button>
                                        </div>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>
                <Modal>
                    <div>
                        <label htmlFor="nome">nome</label>
                        <input type='text' onChange={this.handleField} value={this.state.nome} name="nome" id="nome" />
                        <label htmlFor="tipo">tipo</label>
                        <select value={this.state.tipo} onChange={this.handleField} name="tipo" id="tipo">
                            <option value=''>selecione</option>
                            <option value='bigIncrements'>bigIncrements</option>
                            <option value='bigInteger'>bigInteger</option>
                            <option value='binary'>binary</option>
                            <option value='boolean'>boolean</option>
                            <option value='char'>char</option>
                            <option value='date'>date</option>
                            <option value='dateTime'>dateTime</option>
                            <option value='dateTimeTz'>dateTimeTz</option>
                            <option value='decimal'>decimal</option>
                            <option value='double'>double</option>
                            <option value='enum'>enum</option>
                            <option value='float'>float</option>
                            <option value='geometry'>geometry</option>
                            <option value='geometryCollection'>geometryCollection</option>
                            <option value='increments'>increments</option>
                            <option value='integer'>integer</option>
                            <option value='ipAddress'>ipAddress</option>
                            <option value='json'>json</option>
                            <option value='jsonb'>jsonb</option>
                            <option value='lineString'>lineString</option>
                            <option value='longText'>longText</option>
                            <option value='macAddress'>macAddress</option>
                            <option value='mediumIncrements'>mediumIncrements</option>
                            <option value='mediumInteger'>mediumInteger</option>
                            <option value='mediumText'>mediumText</option>
                            <option value='morphs'>morphs</option>
                            <option value='multiLineString'>multiLineString</option>
                            <option value='multiPoint'>multiPoint</option>
                            <option value='multiPolygon'>multiPolygon</option>
                            <option value='nullableMorphs'>nullableMorphs</option>
                            <option value='nullableTimestamps'>nullableTimestamps</option>
                            <option value='point'>point</option>
                            <option value='polygon'>polygon</option>
                            <option value='rememberToken'>rememberToken</option>
                            <option value='smallIncrements'>smallIncrements</option>
                            <option value='smallInteger'>smallInteger</option>
                            <option value='softDeletes'>softDeletes</option>
                            <option value='softDeletesTz'>softDeletesTz</option>
                            <option value='string'>string</option>
                            <option value='text'>text</option>
                            <option value='time'>time</option>
                            <option value='timeTz'>timeTz</option>
                            <option value='timestamp'>timestamp</option>
                            <option value='timestampTz'>timestampTz</option>
                            <option value='timestamps'>timestamps</option>
                            <option value='timestampsTz'>timestampsTz</option>
                            <option value='tinyIncrements'>tinyIncrements</option>
                            <option value='tinyInteger'>tinyInteger</option>
                            <option value='unsignedBigInteger'>unsignedBigInteger</option>
                            <option value='unsignedDecimal'>unsignedDecimal</option>
                            <option value='unsignedInteger'>unsignedInteger</option>
                            <option value='unsignedMediumInteger'>unsignedMediumInteger</option>
                            <option value='unsignedSmallInteger'>unsignedSmallInteger</option>
                            <option value='unsignedTinyInteger'>unsignedTinyInteger</option>
                            <option value='uuid'>uuid</option>
                            <option value='year'>year</option>
                        </select>
                        <label htmlFor="tamanho">tamanho</label><input type='text' value={atributo.tamanho} onChange={this.editarAtributo(atributo._id)} name="tamanho" id="tamanho" />
                        <label htmlFor="casas_decimais">casas_decimais</label><input type='text' value={atributo.casas_decimais} onChange={this.editarAtributo(atributo._id)} name="casas_decimais" />
                        <input type='checkbox' value={atributo.obrigatorio} onChange={this.editarAtributo(atributo._id)} name="obrigatorio" /><label htmlFor="obrigatorio">obrigatorio</label>
                        <input type='checkbox' value={atributo.aceita_nulo} onChange={this.editarAtributo(atributo._id)} name="aceita_nulo" /><label htmlFor="aceita_nulo">aceita_nulo</label>
                        <input type='checkbox' value={atributo.indexavel} onChange={this.editarAtributo(atributo._id)} name="indexavel" /><label htmlFor="indexavel">indexavel</label>
                        <input type='checkbox' value={atributo.visivel_no_grid} onChange={this.editarAtributo(atributo._id)} name="visivel_no_grid" /><label htmlFor="visivel_no_grid">visivel_no_grid</label>
                        <input type='checkbox' value={atributo.pesquisavel} onChange={this.editarAtributo(atributo._id)} name="pesquisavel" /><label htmlFor="pesquisavel">pesquisavel</label>
                        <input type='checkbox' value={atributo.editavel} onChange={this.editarAtributo(atributo._id)} name="editavel" /><label htmlFor="editavel">editavel</label>
                    </div>
                </Modal>
            </div>
        );
    }
}

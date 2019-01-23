import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from 'prop-types'
import classNames from 'classnames'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'

import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

class InnerPadding extends Component  {

    constructor(props){
        super(props)
        
        this._handleChangePadding = this._handleChangePadding.bind(this)
        this._handleUpdatePadding = this._handleUpdatePadding.bind(this)
        this._selectInput         = this._selectInput.bind(this)
    }

    componentWillMount() {
        
        const { item } = this.props

        if(_.isUndefined(item.styles)){
            this.state = {
                "inner-padding-top-bottom" : 15,
                "inner-padding-left-right" : 10,
             }
        }
        else{
            this.state = _.extend({}, item.styles)
        }

        this.setState({"link" : false })

        this.mouseInterval = null

    }


    _handleChangePadding(event){

        const value = Math.abs(Number(event.target.value))

        const name  = event.target.name

        this._handleUpdatePadding(name, value);

    }

    _handleUpdatePadding(name, value){

        if(!this.state.link){
            this.setState({
                [name]: value
            })
        }
        else{
            this.setState({
                "inner-padding-top-bottom": value,
                "inner-padding-left-right": value,
            })
        }

        this.props.onChangePadding({
             "inner-padding-top-bottom" : this.state["inner-padding-top-bottom"],
             "inner-padding-left-right" : this.state["inner-padding-left-right"]
        })
    }


    _selectInput(event){
        const input = event.target;
        input.select();
    }


    render() {
        const { item } = this.props

        const _classLink = classNames({
            "dashicons-admin-links" : !this.state.link,
            "dashicons-editor-unlink": this.state.link,
        }, "delipress__marginputs__locker delipress__marginputs__locker--locked dashicons-before")

        let inputEls = []
        let inputLoop = [
            {
                "key" : "top",
                "label" : translationDelipressReact.Builder.component_settings.style.attributes_default.paddingTop
            },
            {
                "key" : "bottom",
                "label": translationDelipressReact.Builder.component_settings.style.attributes_default.paddingBottom
            },
            {
                "key" : "left",
                "label": translationDelipressReact.Builder.component_settings.style.attributes_default.paddingLeft
            },
            {
                "key" : "right",
                "label": translationDelipressReact.Builder.component_settings.style.attributes_default.paddingRight
            }
        ]


        _.each(inputLoop, (el, i) => {
            inputEls.push(
                <div className="delipress__marginputs__cell" key={i}>
                    <input
                        type="number"
                        min={0}
                        name={(i == 0 || i == 1) ? 'inner-padding-top-bottom' : 'inner-padding-left-right'}
                        value={(i == 0 || i == 1) ? this.state['inner-padding-top-bottom'] : this.state['inner-padding-left-right']}
                        onChange={this._handleChangePadding}
                        onKeyUp={this._handleChangePadding}
                        onFocus={this._selectInput}
                        readOnly={( (this.state.link && i != 0) || i == 1 || i == 3 )}
                    />
                    <label>{el.label}</label>
                </div>
            )
        }, this)

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.style.attributes_default.innerPadding}
                </span>
                <SettingsItem>
                    <div className="delipress__marginputs">
                        {inputEls}
                        <a className={_classLink} onClick={(e) => {this.setState({"link" : !this.state.link})}}/>
                    </div>
                </SettingsItem>
            </div>
        )
    }
}

InnerPadding.propTypes = {
    onChangePadding : PropTypes.func.isRequired,
    item: PropTypes.object.isRequired
}

export default InnerPadding

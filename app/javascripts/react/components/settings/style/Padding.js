import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from 'prop-types'
import classNames from 'classnames'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

class Padding extends Component  {

    constructor(props){
        super(props)

        const { item } = this.props
        
        this._handleChangePadding = this._handleChangePadding.bind(this)
        this._handleIncrement     = this._handleIncrement.bind(this)
        this._clearIncrement      = this._clearIncrement.bind(this)
        this._selectInput         = this._selectInput.bind(this)
        this.state                = _.extend({}, {
            "padding-top" : item.styles["padding-top"],
            "padding-bottom" : item.styles["padding-bottom"],
            "padding-left" : item.styles["padding-left"],
            "padding-right" : item.styles["padding-right"],
            link : false
        })

        this.mouseInterval = null
    }

    componentWillReceiveProps(nextProps){

        const { item } = nextProps

        this.setState({
            "padding-top" : item.styles["padding-top"],
            "padding-bottom" : item.styles["padding-bottom"],
            "padding-left" : item.styles["padding-left"],
            "padding-right" : item.styles["padding-right"]
        })

    }


    _handleChangePadding(event){
    
        const value = Math.abs(Number(event.target.value))

        const name  = event.target.name

        this._handleUpdatePadding(name, value);

    }

    _handleIncrement(event){
        
        const padding = 'padding-' + event.target.getAttribute('data-padding');
        const increment = Number(event.target.getAttribute('data-increment'));

        let paddingState = this.state[padding];
        paddingState = (increment == 1 ? paddingState + 1 : paddingState - 1);
        this._handleUpdatePadding(padding, paddingState);

        // Long press event
        // this.mouseInterval = setInterval(() => {
        //     let paddingState = this.state[padding];
        //     paddingState = (increment == 1 ? paddingState + 1 : paddingState - 1);
        //     this._handleUpdatePadding(padding, paddingState);
        // }, 500)
    }

    _clearIncrement(){
        // clearInterval(this.mouseInterval);
    }

    _handleUpdatePadding(name, value){
        
        if(!this.state.link){
            this.setState({
                [name]: value
            })
            
            this.props.onChangePadding({
                [name]: value
            })
        }
        else{
            this.setState({
                "padding-top": value,
                "padding-bottom": value,
                "padding-left": value,
                "padding-right": value
            })

            this.props.onChangePadding({
                "padding-top": value,
                "padding-bottom": value,
                "padding-left": value,
                "padding-right": value
            })
        }

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
                "key" : "right",
                "label": translationDelipressReact.Builder.component_settings.style.attributes_default.paddingRight
            },
            {
                "key" : "bottom",
                "label": translationDelipressReact.Builder.component_settings.style.attributes_default.paddingBottom
            },
            {
                "key" : "left",
                "label": translationDelipressReact.Builder.component_settings.style.attributes_default.paddingLeft
            }
        ]


        _.each(inputLoop, (el, i) => {
            inputEls.push(
                <div className="delipress__marginputs__cell" key={i}>
                    <input
                        type="number"
                        min={0}
                        id={'padding-' + el.key}
                        name={'padding-' + el.key}
                        value={this.state["padding-"+ el.key]}
                        onChange={this._handleChangePadding}
                        onKeyUp={this._handleChangePadding}
                        readOnly={(this.state.link && i != 0)}
                    />

                    {/* <span
                        onMouseDown={this._handleIncrement}
                        onMouseUp={this._clearIncrement}
                        data-increment='1'
                        data-padding={el.key}
                        className="delipress__marginputs__increment dashicons dashicons-arrow-up-alt2"
                    />
                    <span
                        onMouseDown={this._handleIncrement}
                        onMouseUp={this._clearIncrement}
                        data-increment='0'
                        data-padding={el.key}
                        className="delipress__marginputs__increment dashicons dashicons-arrow-down-alt2"
                    /> */}
                    <label>{el.label}</label>
                </div>
            )
        }, this)

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.style.attributes_default.padding}
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

Padding.propTypes = {
    onChangePadding : PropTypes.func.isRequired,
    item: PropTypes.object.isRequired
}

export default Padding

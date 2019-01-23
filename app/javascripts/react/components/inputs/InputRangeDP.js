import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import InputRange from "react-input-range"
import { connect } from "react-redux"
import { compose, bindActionCreators } from "redux"
import classNames from 'classnames'

import InputNumber from "javascripts/react/components/inputs/InputNumber"
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'


class InputRangeDP extends Component {
    constructor(props) {
        super(props)

        this._handleOnChangeWidth      = this._handleOnChangeWidth.bind(this)
        this._handleOnChangeWidthInput = this._handleOnChangeWidthInput.bind(this)
        this._handleInputFocus         = this._handleInputFocus.bind(this)
        this._handleOnChangeCompleteWidth = this._handleOnChangeCompleteWidth.bind(this)

        this.state = {
            rangeValue : this.props.rangeValue,
            maxValue : this.props.maxValue
        }
    }

    componentWillReceiveProps(nextProps){
        this.setState({
            rangeValue : nextProps.rangeValue,
            maxValue : nextProps.maxValue
        })
    }

    _handleOnChangeWidth(rangeValue) {

        const {
            elementSelector,
            typeSelector,
            suffixSelector,
            attr
        } = this.props

        if (rangeValue > this.state.maxValue) {
            rangeValue = this.state.maxValue
        }else if (rangeValue < 0){
            rangeValue = 0
        }

        this.setState({
            rangeValue: rangeValue
        })

        if(!_.isUndefined(elementSelector)){
            if(attr){
                jQuery(elementSelector).attr(typeSelector, rangeValue)
            }
            else{
                jQuery(elementSelector).css({
                    [typeSelector]: `${rangeValue}${suffixSelector}`
                })
            }
        }

        if(!_.isUndefined(this.props.handleOnChangeWidth)){
            this.props.handleOnChangeWidth(rangeValue)
        }
    }

    _handleOnChangeCompleteWidth(rangeValue){

        if (rangeValue > this.state.maxValue) {
            rangeValue = this.state.maxValue
        }else if (rangeValue < 0){
            rangeValue = 0
        }

        this.setState({
            rangeValue: rangeValue
        })

        this.props.handleOnChangeCompleteWidth(rangeValue)
    
    }

    _handleOnChangeWidthInput(e) {
        let inputValue = Number(e.target.value)
        if (_.isNaN(inputValue)) {
            inputValue = 0
        }
        this._handleOnChangeWidth(inputValue)
    }

    _handleInputFocus(e){
        e.target.select()
    }

    render() {

        const _classNames = classNames({
            "delipress__builder__side__setting__input" : true,
            "delipress__builder__side__setting__input--range" : true,
            "delipress__builder__side__setting__input--range--px" : (_.isUndefined(this.props.type) || this.props.type === "px"),
            "delipress__builder__side__setting__input--range--pourcent" : this.props.type === "pourcent"
        })

        return (
            <SettingsItem label={translationDelipressReact.Builder.component_settings.image.size} classModifier={_classNames}>
                <InputRange
                    minValue={this.props.minValue || 0}
                    maxValue={this.props.maxValue}
                    value={this.state.rangeValue}
                    onChange={this._handleOnChangeWidth}
                    onChangeComplete={this._handleOnChangeCompleteWidth}
                />
                <input
                    type="text"
                    value={this.state.rangeValue}
                    onKeyUp={this._handleOnChangeWidthInput}
                    onChange={this._handleOnChangeWidthInput}
                    onFocus={this._handleInputFocus}
                    className="delipress__input"
                />
            </SettingsItem>
        )
    }
}

InputRangeDP.propTypes = {
    rangeValue: PropTypes.number.isRequired,
    maxValue: PropTypes.number.isRequired,
    minValue: PropTypes.number,
    handleOnChangeWidth: PropTypes.func,
    handleOnChangeCompleteWidth: PropTypes.func.isRequired,
    type: PropTypes.string,
    elementSelector: PropTypes.string,
    typeSelector: PropTypes.string,
    suffixSelector: PropTypes.string,
}

export default InputRangeDP

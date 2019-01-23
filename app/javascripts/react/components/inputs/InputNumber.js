import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from "prop-types"
import {
    shallowEqual
} from 'javascripts/react/helpers/shallowEqual'
import {
    ceil10,
    round10
} from 'javascripts/react/helpers/decimalAdjust'

class InputNumber extends Component  {


    constructor(props, ctx) {
        super(props, ctx)

        this._changeInputValue       = this._changeInputValue.bind(this)
        this._handleChangeInput      = this._handleChangeInput.bind(this)

        this.state = _.extend({}, {
            [this.props.name] : this.props.nameValue
        })

        this.min = (_.isUndefined(this.props.min)) ? 0 : this.props.min
        this.max = (_.isUndefined(this.props.max)) ? null : this.props.max
    }

    componentWillReceiveProps(nextProps){
        this.state = _.extend({}, {
            [nextProps.name] : nextProps.nameValue
        })
    }

    _handleChangeInput(e){
        this._changeInputValue(e.target.value)
    }


    _changeInputValue(value){
        const {
            name
        } = this.props

        if(!_.isNumber(value)){
            value = Number(value)
        }

        this.setState({
            [name] : value
        })
        
        this.props.saveRefValue(name, value)
    }

    render() {
        const {
            name,
            placeholder,
            placeholderText,
            step,
            max
        } = this.props

        let _step = 1
        if(!_.isUndefined(step)){
            _step = step
        }

        let _max = ""
        if(!_.isUndefined(max)){
            _max = max
        }

        return (
            <div className="delipress__numberinput">
                <input
                    className="delipress__input"
                    placeholder={placeholderText}
                    name={name}
                    min={(_.isUndefined(this.props.min) ? 0 : this.props.min)}
                    max={_max}
                    onChange={this._handleChangeInput}
                    onKeyUp={this._handleChangeInput}
                    type="number"
                    step={_step}
                    value={this.state[name]}
                />
                {
                    (!_.isEmpty(placeholder)) ?
                    <div className="delipress__numberinput__suffix">{placeholder}</div> :
                    false
                }
            </div>
        )
    }
}

InputNumber.propType = {
    saveRefValue : PropTypes.func.isRequired,
    name : PropTypes.string.isRequired,
    nameValue: PropTypes.string,
    placeholder: PropTypes.string,
    min: PropTypes.number,
    max: PropTypes.number
}


export default InputNumber

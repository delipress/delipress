import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"


class BaseNewSettings extends Component {
    
    constructor(props){
        super(props)

        this.saveOptionValue                   = this.saveOptionValue.bind(this)
        this.saveOptionValueOnPreset           = this.saveOptionValueOnPreset.bind(this)
        this._changeInputValueText             = this._changeInputValueText.bind(this)
        this._changeInputValueTextOnPreset     = this._changeInputValueTextOnPreset.bind(this)
        this._changeInputValueCheckbox         = this._changeInputValueCheckbox.bind(this)
        this._changeInputValueCheckboxOnPreset = this._changeInputValueCheckboxOnPreset.bind(this)
        this._changeInputValue                 = this._changeInputValue.bind(this)
        this._changeInputValueOnPreset         = this._changeInputValueOnPreset.bind(this)
        this._changeInputValueNumber           = this._changeInputValueNumber.bind(this)
        this._changeInputValueNumberOnPreset   = this._changeInputValueNumberOnPreset.bind(this)
        this._changeInputValueInputText        = this._changeInputValueInputText.bind(this)
        this.timeoutSave                       = 450
        this.handleApply                       = this.handleApply.bind(this)

        const { item } = this.props
        
        if(!_.isNull(item) && !_.isUndefined(item)){
            this.styles = _.clone(item.styles)
        }
    }

    handleApply(e) {
        e.preventDefault()

        localStorage.setItem('dp_default_component_' + this.props.item.type, JSON.stringify(this.styles) );

        this.props.updateAllStyles(_.clone(this.styles))
    }



    componentWillReceiveProps(nextProps){
        const { item } = nextProps

        if(!_.isNull(item) && !_.isUndefined(item)){
            this.styles = _.clone(item.styles)
            this.setState({
                value: this.styles.value,
                href: this.styles.href
            })
        }
    }

    componentWillUpdate(){
        this.timeoutSave = 450
    }

    _changeInputValueInputText(event){
        this.setState({
            [event.target.name] : event.target.value
        })

        this.saveOptionValue(event.target.name, event.target.value)
    }

    _changeInputValueText(event){
        this.saveOptionValue(event.target.name, event.target.value)          
    }

    _changeInputValueTextOnPreset(event){
        this._changeInputValueOnPreset(event, event.target.value)          
    }

    _changeInputValueCheckbox(event){
        this.timeoutSave = 0
        this._changeInputValue(event, event.target.checked)        
    }

    _changeInputValueCheckboxOnPreset(event){
        this.timeoutSave = 0
        this._changeInputValueOnPreset(event, event.target.checked)        
    }

    _changeInputValueNumber(event){
        this._changeInputValue(event, Number(event.target.value))
    }

    _changeInputValueNumberOnPreset(event){
        this._changeInputValueOnPreset(event, Number(event.target.value))
    }

    _changeInputValueOnPreset(event, value){
        const name  = event.target.name

        this.saveOptionValueOnPreset(name, value)
    }
    
    _changeInputValue(event, value){
        const name  = event.target.name

        this.saveOptionValue(name, value)
    }

     saveOptionValueOnPreset(key, value){
        
        let _newPresets = _.clone(this.styles.presets)

        _newPresets = _.map(_newPresets, (val) => {
            if(val.type === this.styles.presetChoice){
                return _.extend({}, val, {
                   [key] : value
                })
            }
            return val
        })

        this.styles.presets = _newPresets

        this.saveEditor()

    }

    saveOptionValue(name, value){
        this.styles[name] = value

        this.saveEditor()
       
    }

    saveEditor(){
        clearTimeout(this.saves)

        this.saves = setTimeout(() => {
            this.props.saveEditor(this.styles)
        }, this.timeoutSave)
    }


}

export default BaseNewSettings

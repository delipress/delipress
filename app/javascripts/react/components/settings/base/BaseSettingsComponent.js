import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import {
    shallowEqual
} from 'javascripts/react/helpers/shallowEqual'

class BaseSettingsComponent extends Component  {
    
    constructor(props, ctx) {
        super(props, ctx)

        const { item } = this.props
            
        this.state = _.clone(item.styles)

        this._saveRefValue                     = this._saveRefValue.bind(this)
        this._saveRefValueOnPreset             = this._saveRefValueOnPreset.bind(this)
        this._saveEditor                       = this._saveEditor.bind(this)
        this._changeInputValueText             = this._changeInputValueText.bind(this)
        this._changeInputValueTextOnPreset     = this._changeInputValueTextOnPreset.bind(this)
        this._changeInputValueCheckbox         = this._changeInputValueCheckbox.bind(this)
        this._changeInputValueCheckboxOnPreset = this._changeInputValueCheckboxOnPreset.bind(this)
        this._changeInputValue                 = this._changeInputValue.bind(this)
        this._changeInputValueOnPreset         = this._changeInputValueOnPreset.bind(this)
        this._changeInputValueNumber           = this._changeInputValueNumber.bind(this)
        this._changeInputValueNumberOnPreset   = this._changeInputValueNumberOnPreset.bind(this)
    }

    componentWillReceiveProps(nextProps){
        const index     = `${this.props.item.keyRow}_${this.props.item.keyColumn}_${this.props.item._id}`
        const indexNext = `${nextProps.item.keyRow}_${nextProps.item.keyColumn}_${nextProps.item._id}`
        
        if(index != indexNext){
            this.setState(nextProps.item.styles)
        }

    }

    // componentWillUpdate(nextProps, nextState){

    //     const index     = `${this.props.item.keyRow}_${this.props.item.keyColumn}_${this.props.item._id}`
    //     const indexNext = `${nextProps.item.keyRow}_${nextProps.item.keyColumn}_${nextProps.item._id}`

    //     if(
    //         !shallowEqual(nextProps.item.styles, this.state) && shallowEqual(nextState, this.state) ||
    //         index != indexNext
    //     ){
    //         this.setState(_.clone(nextProps.item.styles))
    //     }
    // }


    _saveEditor(){
        const { saveEditor } = this.props

        setTimeout(() => {
            saveEditor(this.state)
        })
    }

    _changeInputValueText(event){
        this._changeInputValue(event, event.target.value)          
    }

    _changeInputValueTextOnPreset(event){
        this._changeInputValueOnPreset(event, event.target.value)          
    }

    _changeInputValueCheckbox(event){
        this._changeInputValue(event, event.target.checked)        
    }

    _changeInputValueCheckboxOnPreset(event){
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

        this._saveRefValueOnPreset(name, value)
    }
    
    _changeInputValue(event, value){
        const name  = event.target.name

        this._saveRefValue(name, value)
    }

    _saveRefValue(key, value){
        
        this.setState({
            [key]: value
        })

        this._saveEditor()

    }
    _saveRefValueOnPreset(key, value){
        
        let _newPresets = _.clone(this.state.presets)

        _newPresets = _.map(_newPresets, (val) => {
            if(val.type === this.state.presetChoice){
                return _.extend({}, val, {
                   [key] : value
                })
            }
            return val
        })

        this.setState({
            presets: _newPresets
        })

        this._saveEditor()

    }
}

export default BaseSettingsComponent



    

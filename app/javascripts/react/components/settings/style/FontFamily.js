import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from "prop-types"

import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

class FontFamily extends Component  {

    constructor(props, ctx){
        super(props, ctx)

        this.fontsAvailable    = configDelipressReact.fonts
        this._changeFontFamily = this._changeFontFamily.bind(this)

        const { styles } = this.props

        let fontFamily = "Arial"
        if(!_.isUndefined(styles.presetChoice)){
            const choice = _.clone(
                _.find(styles.presets, {"type" : styles.presetChoice})
            )
            fontFamily = choice["font-family"]
        }
        else{
            fontFamily = styles["font-family"]
        }

        this.state = {
            fontFamily : fontFamily
        }
    }

    componentWillReceiveProps(nextProps){

        const { styles } = nextProps

        let fontFamily = "Arial"
        if(!_.isUndefined(styles.presetChoice)){
            const choice = _.clone(
                _.find(styles.presets, {"type" : styles.presetChoice})
            )
            fontFamily = choice["font-family"]
        }
        else{
            fontFamily = styles["font-family"]
        }
        
        this.setState({
            fontFamily : fontFamily
        })
    }
    
    _changeFontFamily(event){
        this.props.onChangeFontFamily(event.target.value)
    }

    render() {
        const { fontFamily } = this.state

        return (
            <SettingsItem label={translationDelipressReact.font_family}>
                <select
                    name="font-family"
                    onChange={this._changeFontFamily}
                    value={fontFamily}
                >
                    {this.fontsAvailable.map((option, key) => {
                        return (
                            <option
                                key={`font_family_${key}`}
                                value={option}
                            >
                                {option}
                            </option>
                        )
                    })}
                </select>
            </SettingsItem>
        )
    }
}

FontFamily.propTypes = {
    styles : PropTypes.object.isRequired,
    onChangeFontFamily : PropTypes.func.isRequired
}

export default FontFamily

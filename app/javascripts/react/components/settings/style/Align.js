import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from 'prop-types'

import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

class Align extends Component  {

    constructor(props, ctx){
        super(props, ctx)

        this._onChangeSettingsAlign = this._onChangeSettingsAlign.bind(this)
    }

    _onChangeSettingsAlign(event){
        const value = event.target.value
        const name  = event.target.name

        this.props.onChangeSettingsAlign(value)
    }


    render() {
        const { styles } = this.props

        let align = "center"
        if(!_.isUndefined(styles.presetChoice)){
            const choice = _.clone(
                _.find(styles.presets, {"type" : styles.presetChoice})
            )
            align = choice.align
        }
        else{
            align = styles.align
        }
        
        let alignEls = []
        const alignLoop = ['left', 'center', 'right']

        _.each(alignLoop, (el, i) => {
            alignEls.push(
                <div className="delipress__buttonsgroup__cell" key={i}>
                    <input
                        type="radio"
                        name="settings__align"
                        id={'settings__align_' + el}
                        name="align"
                        value={el}
                        checked={align === el}
                        onChange={this._onChangeSettingsAlign}
                    />

                    <label htmlFor={'settings__align_' + el} className="delipress__buttonsgroup__cell">
                        <span className={'dashicons dashicons-align-' + el} />
                    </label>
                </div>
            )
        })

        return (
            <SettingsItem label={translationDelipressReact.align} classModifier="delipress__builder__side__setting__input--align">
                <div className="delipress__buttonsgroup">
                    {alignEls}
                </div>
            </SettingsItem>
        )
    }
}

Align.propTypes = {
    onChangeSettingsAlign : PropTypes.func.isRequired,
    styles: PropTypes.object.isRequired
}

export default Align

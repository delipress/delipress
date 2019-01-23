import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from 'prop-types'

import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

class VerticalAlignColumns extends Component  {

    constructor(props, ctx){
        super(props, ctx)

        this._onChangeSettingsAlign = this._onChangeSettingsAlign.bind(this)

    }

    _onChangeSettingsAlign(event){
        const value = event.target.value
        this.setState({valign: value})
        this.props.onChangeSettingsVerticalAlign(value)
    }


    render() {
    
        let   alignEls  = []
        const alignLoop = ['top', 'middle', 'bottom']

        const { styles } = this.props

        let valign = "center"
        if(!_.isUndefined(styles.presetChoice)){
            const choice = _.clone(
                _.find(styles.presets, {"type" : styles.presetChoice})
            )
            valign = choice["vertical-align"]
        }
        else{
            valign = styles["vertical-align"]
        }

        this.state = {
            valign : valign
        }

        _.each(alignLoop, (el, i) => {
            alignEls.push(
                <div className="delipress__buttonsgroup__cell" key={i}>
                    <input
                        type="radio"
                        name={"settings__valign_columns"}
                        id={'settings__valign_' + el + i}
                        value={el}
                        checked={this.state.valign === el}
                        onChange={this._onChangeSettingsAlign}
                    />

                    <label htmlFor={'settings__valign_' + el + i} className="delipress__buttonsgroup__cell">
                        {el}
                    </label>
                </div>
            )
        })

        return (
            <SettingsItem label={translationDelipressReact.vertical_align} classModifier="delipress__builder__side__setting__input--align">
                <div className="delipress__buttonsgroup">
                    {alignEls}
                </div>
            </SettingsItem>
        )
    }
}

VerticalAlignColumns.propTypes = {
    onChangeSettingsVerticalAlign : PropTypes.func.isRequired,
    styles: PropTypes.object.isRequired
}

export default VerticalAlignColumns

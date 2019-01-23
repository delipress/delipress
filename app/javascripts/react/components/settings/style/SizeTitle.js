import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'

import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

class SizeTitle extends Component  {

    constructor(props, ctx){
        super(props, ctx)
        this._onChangeSettingsSizeTitle = this._onChangeSettingsSizeTitle.bind(this)

        const { item } = this.props

        this.state = {
            presetChoice : item.styles.presetChoice
        }

    }

    componentWillReceiveProps(nextProps){
        const { item } = nextProps

        this.setState({
            presetChoice: item.styles.presetChoice
        })
    }

    _onChangeSettingsSizeTitle(event){
        const value = event.target.value
        const name  = event.target.name

        this.setState({
            [name] : value
        })

        this.props.onChangeSettingsSizeTitle(value)
    }


    render() {
        const { item } = this.props

        let   sizes     = []
        const alignLoop = ['H1', 'H2', 'H3']

        _.each(alignLoop, (el, i) => {
            sizes.push(
                <div className="delipress__buttonsgroup__cell" key={i}>
                    <input
                        type="radio"
                        name="settings__size"
                        id={'settings__size_' + el}
                        name="presetChoice"
                        value={el}
                        checked={this.state.presetChoice === el}
                        onChange={this._onChangeSettingsSizeTitle}
                    />

                    <label htmlFor={'settings__size_' + el} className="delipress__buttonsgroup__cell">
                        {el}
                    </label>
                </div>
            )
        }, this)

        return (
            <SettingsItem label={translationDelipressReact.Builder.component_settings.size_title.title} classModifier="delipress__builder__side__setting__input--align">
                <div className="delipress__buttonsgroup">
                    {sizes}
                </div>
            </SettingsItem>
        )
    }
}

SizeTitle.propTypes = {
    onChangeSettingsSizeTitle : PropTypes.func.isRequired,
    item: PropTypes.object.isRequired
}

export default SizeTitle

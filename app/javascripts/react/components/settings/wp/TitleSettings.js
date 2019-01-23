import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"

class TitleSettings extends Component {

    constructor(props){
        super(props)
        
        this._toggleOption = this._toggleOption.bind(this)
    }

    _toggleOption(event) {
        this.props.toggleOption(event.target.checked)
    }

    render() {
        const {
            valueOption
        } = this.props

        return (
            <SettingsItem
                label={translationDelipressReact.Builder.component_settings.wp_archive_post.settings_title}
            >
                <input
                    type="checkbox"
                    id="settings_title"
                    className="delipress__switch__input"
                    checked={valueOption}
                    onChange={this._toggleOption}
                />
                <label htmlFor="settings_title" className="delipress__switch">
                    <div className="delipress__switch__slider" />
                    <div className="delipress__switch__on">I</div>
                    <div className="delipress__switch__off">0</div>
                </label>
            </SettingsItem>
        )
    }

}

TitleSettings.propTypes = {
    toggleOption : PropTypes.func.isRequired,
    valueOption: PropTypes.bool.isRequired
}

export default TitleSettings

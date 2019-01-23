import React, { Component, cloneElement } from "react"
import { connect } from "react-redux"
import { bindActionCreators } from "redux"

import SettingsFactory from "javascripts/react/services/SettingsFactory"
import { SETTINGS_LIST_CONTENTS } from "javascripts/react/constants/EditorConstants"

class ComponentSettings extends Component {
    constructor(props) {
        super(props)

        this._saveSettings = this._saveSettings.bind(this)
    }

    _saveSettings() {
        const { actionsEditor } = this.props

        actionsEditor
            .changeSettingsComponent(SETTINGS_LIST_CONTENTS)
            .then(() => {
                actionsEditor.activeItem(null)
            })
    }

    render() {
        const {
            actionsEditor,
            component,
            item,
            paramsSettingsComponent
        } = this.props

        return (
            <div className="delipress__builder__side__panel">
                <div className="delipress__builder__side__panel__scroll">
                    {SettingsFactory.getSettingsComponent(
                        component,
                        item,
                        paramsSettingsComponent
                    )}
                </div>
            </div>
        )
    }
}

export default ComponentSettings

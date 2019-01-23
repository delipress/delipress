import React, { Component } from "react"

import ColorSelector from "javascripts/react/components/ColorSelector"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import Border from "javascripts/react/components/settings/style/Border"
import FormSize from "./FormSize"
import FormSpace from "./FormSpace"
import FormColor from "./FormColor"

class FormDesign extends Component {
    constructor(props) {
        super(props)
    }

    render() {
        const { config } = this.props

        return (
            <div className="container__settings__attributes settings__default">
                <FormSize {...this.props} />
                <FormSpace {...this.props} />
                <FormColor {...this.props} />
            </div>
        )
    }
}

export default FormDesign

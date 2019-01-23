import * as _ from "underscore"
import React, { Component } from "react"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import Checkbox from "javascripts/react/components/inputs/Checkbox"
import { shallowEqual } from "javascripts/react/helpers/shallowEqual"

class CustomCssSettings extends Component {
    constructor(props) {
        super(props)

        this.state = {
            value: this.props.config.custom_css
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleChangeNaked = this.handleChangeNaked.bind(this)
    }

    handleChange(event) {
        const val = event.target.value
        this.setState({ value: val })

        clearTimeout(this.saveChangeContent)

        this.saveChangeContent = setTimeout(() => {
            let { config } = this.props

            config = _.extend({}, config, {
                custom_css: val
            })
            this.props.updateOptin(config)
        }, 350)
    }

    handleChangeNaked(event) {
        const val = event.target.checked
        let { config } = this.props

        config.default_settings.form_wrapper.attrs = _.extend(
            {},
            this.props.config.default_settings.form_wrapper.attrs,
            {
                naked: val
            }
        )

        this.props.updateOptin(config)
    }

    render() {
        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .optin.custom_css.title
                    }
                </span>
                <SettingsItem classModifier="delipress__builder__custom-css">
                    <textarea
                        value={this.state.value}
                        onChange={this.handleChange}
                    />
                </SettingsItem>
                <SettingsItem
                    label={translationDelipressReact.Optin.naked}
                    id="optin_naked"
                >
                    <Checkbox
                        id="optin_naked"
                        defaultChecked={
                            this.props.config.default_settings.form_wrapper
                                .attrs.naked || false
                        }
                        handleChange={this.handleChangeNaked}
                    />
                </SettingsItem>
            </div>
        )
    }
}

export default CustomCssSettings

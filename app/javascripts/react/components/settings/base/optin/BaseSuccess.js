import React, { Component } from "react"

import BaseSettingsOptin from "javascripts/react/components/settings/base/BaseSettingsOptin"
import Checkbox from "javascripts/react/components/inputs/Checkbox"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"

class BaseSuccess extends BaseSettingsOptin {
    render() {
        
        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Optin
                            .shortcode_settings
                            .success_settings.title_email_form
                    }
                </span>
                <SettingsItem 
                    label={translationDelipressReact.Optin.shortcode_settings.success_settings.disable_email_input_form}
                    id="email_input_form-attrs-disable_email_input_form"
                >
                    <Checkbox
                        id="email_input_form-attrs-disable_email_input_form"
                        defaultChecked={this.config.email_input_form.attrs.disable_email_input_form}
                        handleChange={(e) => {
                            this._saveValue("email_input_form-attrs-disable_email_input_form", e.target.checked)
                        }}
                    />
                </SettingsItem>
            </div>
        )
    }
}

export default BaseSuccess

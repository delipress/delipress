import React, { Component } from "react"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"

const FormSize = props => {
    const formSizes = [
        {
            key: "default",
            name:
                translationDelipressReact.Builder.component_settings.optin.form
                    .form_size_default
        },
        {
            key: "large",
            name:
                translationDelipressReact.Builder.component_settings.optin.form
                    .form_size_large
        },
        {
            key: "full",
            name:
                translationDelipressReact.Builder.component_settings.optin.form
                    .form_size_full
        },
        {
            key: "inline",
            name:
                translationDelipressReact.Builder.component_settings.optin.form
                    .form_size_inline
        }
    ]

    return (
        <SettingsItem
            label={
                translationDelipressReact.Builder.component_settings.optin.form
                    .form_size
            }
        >
            <select
                name="form_size"
                onChange={event => {
                    props.saveValues({
                        form_wrapper: {
                            attrs: {
                                form_size: event.target.value
                            }
                        }
                    })
                }}
                value={props.config.form_wrapper.attrs.form_size}
            >
                {formSizes.map((option, key) => {
                    return (
                        <option key={`form_size_${key}`} value={option.key}>
                            {option.name}
                        </option>
                    )
                })}
            </select>
        </SettingsItem>
    )
}

export default FormSize

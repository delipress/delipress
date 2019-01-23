import React, { Component } from "react"
import * as _ from "underscore"

import ColorSelector from "javascripts/react/components/ColorSelector"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import Border from "javascripts/react/components/settings/style/Border"
import Checkbox from "javascripts/react/components/inputs/Checkbox"

class FormWrapper extends Component {
    constructor(props) {
        super(props)
    }

    singleName() {
        if (this.props.config.form_wrapper.attrs.metas != "single_field") {
            return null
        }

        return (
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.optin
                        .form.name_text
                }
            >
                <input
                    className="delipress__input"
                    name="name_placeholder"
                    type="text"
                    onChange={event => {
                        this.props.saveValues({
                            form_wrapper: {
                                attrs: {
                                    name_placeholder: event.target.value
                                }
                            }
                        })
                    }}
                    value={
                        this.props.config.form_wrapper.attrs.name_placeholder
                    }
                />
            </SettingsItem>
        )
    }

    fullName() {
        if (this.props.config.form_wrapper.attrs.metas != "first_last_name") {
            return null
        }
        return (
            <div>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .optin.form.firstname_text
                    }
                >
                    <input
                        className="delipress__input"
                        name="firstname_placeholder"
                        type="text"
                        onChange={event => {
                            this.props.saveValues({
                                form_wrapper: {
                                    attrs: {
                                        firstname_placeholder:
                                            event.target.value
                                    }
                                }
                            })
                        }}
                        value={
                            this.props.config.form_wrapper.attrs
                                .firstname_placeholder
                        }
                    />
                </SettingsItem>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .optin.form.lastname_text
                    }
                >
                    <input
                        className="delipress__input"
                        type="text"
                        name="lastname_placeholder"
                        onChange={event => {
                            this.props.saveValues({
                                form_wrapper: {
                                    attrs: {
                                        lastname_placeholder: event.target.value
                                    }
                                }
                            })
                        }}
                        value={
                            this.props.config.form_wrapper.attrs
                                .lastname_placeholder
                        }
                    />
                </SettingsItem>
            </div>
        )
    }

    render() {
        const { config } = this.props

        const nameFields = [
            {
                key: "empty",
                name:
                    translationDelipressReact.Builder.component_settings.optin
                        .form.name_fields_default
            },
            {
                key: "single_field",
                name:
                    translationDelipressReact.Builder.component_settings.optin
                        .form.name_fields_single
            },
            {
                key: "first_last_name",
                name:
                    translationDelipressReact.Builder.component_settings.optin
                        .form.name_fields_double
            }
        ]

        let _config = this.props.config

        if (_.isUndefined(this.props.config.form_wrapper)) {
            _config = _.extend({}, _config, {
                form_wrapper: {
                    attrs: {
                        naked: false,
                        metas: "empty"
                    }
                }
            })
        }

        const warningRedirect = !_config.form_wrapper.attrs.fields_enable && _config.form_wrapper.attrs.redirect_url == ""

        return (
            <div className="container__settings__attributes settings__default">
                <SettingsItem
                    label={translationDelipressReact.Builder.component_settings
                                .optin.form.enable_fields}
                >
                    <Checkbox
                        id="form-attrs-fields_enable"
                        defaultChecked={_config.form_wrapper.attrs.fields_enable}
                        handleChange={e => {
                            this.props.saveValues({
                                form_wrapper: {
                                    attrs: {
                                        fields_enable: e.target.checked
                                    }
                                }
                            })
                        }}
                    />
                </SettingsItem>
                {config.form_wrapper.attrs.fields_enable && <div>
                    <SettingsItem
                        label={
                            translationDelipressReact.Builder.component_settings
                                .optin.form.email_placeholder
                        }
                    >
                        <input
                            className="delipress__input"
                            name="email_placeholder"
                            type="text"
                            onChange={event => {
                                this.props.saveValues({
                                    form_wrapper: {
                                        attrs: {
                                            email_placeholder: event.target.value
                                        }
                                    }
                                })
                            }}
                            value={
                                this.props.config.form_wrapper.attrs
                                    .email_placeholder
                            }
                        />
                    </SettingsItem>
                    <SettingsItem
                        label={
                            translationDelipressReact.Builder.component_settings
                                .optin.form.name_fields
                        }
                    >
                        <select
                            name="name_fields"
                            onChange={event => {
                                this.props.saveValues({
                                    form_wrapper: {
                                        attrs: {
                                            metas: event.target.value
                                        }
                                    }
                                })
                            }}
                            value={_config.form_wrapper.attrs.metas}
                        >
                            {nameFields.map((option, key) => {
                                return (
                                    <option
                                        key={`name_field_${key}`}
                                        value={option.key}
                                    >
                                        {option.name}
                                    </option>
                                )
                            })}
                        </select>
                    </SettingsItem>
                    {this.singleName()}
                    {this.fullName()}
                </div>}
                
                <SettingsItem
                    label={translationDelipressReact.Builder.component_settings
                                .optin.form.redirect_url}
                >
                    <input
                        className="delipress__input"
                        name="redirect_url"
                        type="url"
                        data-not-valid={ translationDelipressReact.Builder.component_settings
                                .optin.form.redirect_url_not_valid}
                        placeholder="https://redirect-url.com"
                        onChange={event => {
                            this.props.saveValues({
                                form_wrapper: {
                                    attrs: {
                                        redirect_url: event.target.value
                                    }
                                }
                            })
                        }}
                        value={
                            _config.form_wrapper.attrs.redirect_url
                        }
                    />
                </SettingsItem>

                {warningRedirect && 
                    <div className="delipress__input__help" id="warning-present">
                        <p>{translationDelipressReact.Builder.component_settings
                                .optin.form.redirect_url_warning}</p>
                    </div>
                }
            </div>
        )
    }
}

export default FormWrapper

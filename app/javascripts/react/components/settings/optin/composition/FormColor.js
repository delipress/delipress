import React, { Component } from "react"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import ColorSelector from "javascripts/react/components/ColorSelector"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import Border from "javascripts/react/components/settings/style/Border"

const FormColor = props => {
    let _config = props.config

    if (_.isUndefined(props.config.fields)) {
        _config = _.extend({}, _config, {
            fields: {
                attrs: {
                    borderRadius: 0,
                    borderStyle: "solid",
                    borderWidth: 0,
                    borderColor: {
                        hex: "#ffffff",
                        rgb: {
                            r: 255,
                            g: 255,
                            b: 255,
                            a: 1
                        }
                    }
                },
                styling: {
                    backgroundColor: {
                        hex: "#ffffff",
                        rgb: {
                            r: 255,
                            g: 255,
                            b: 255,
                            a: 1
                        }
                    },
                    color: {
                        hex: "#ffffff",
                        rgb: {
                            r: 255,
                            g: 255,
                            b: 255,
                            a: 1
                        }
                    }
                }
            }
        })
    }

    return (
        <div>
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.optin
                        .form.background_form
                }
            >
                <ColorSelector
                    handleChangeComplete={color => {
                        props.saveValue(
                            "form_wrapper-styling-backgroundColor",
                            color
                        )
                    }}
                    disabledAlpha={false}
                    picker="sketch"
                    idSelector=".DELI-formBloc"
                    typeColor="backgroundColor"
                    color={
                        !_.isUndefined(_config.form_wrapper.styling)
                            ? _config.form_wrapper.styling.backgroundColor
                            : {
                                  hex: "#ffffff",
                                  rgb: {
                                      r: 255,
                                      g: 255,
                                      b: 255,
                                      a: 1
                                  }
                              }
                    }
                />
            </SettingsItem>
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.optin
                        .form.background_input
                }
            >
                <ColorSelector
                    handleChangeComplete={color => {
                        props.saveValue("fields-styling-backgroundColor", color)
                    }}
                    disabledAlpha={false}
                    picker="sketch"
                    idSelector=".DELI-inputField"
                    typeColor="backgroundColor"
                    color={
                        !_.isUndefined(_config.fields.styling.backgroundColor)
                            ? _config.fields.styling.backgroundColor
                            : {
                                  hex: "#ffffff",
                                  rgb: {
                                      r: 255,
                                      g: 255,
                                      b: 255,
                                      a: 1
                                  }
                              }
                    }
                />
            </SettingsItem>
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.optin
                        .form.color_input
                }
            >
                <ColorSelector
                    handleChangeComplete={color => {
                        props.saveValue("fields-styling-color", color)
                    }}
                    disabledAlpha={false}
                    picker="sketch"
                    idSelector=".DELI-inputField"
                    typeColor="color"
                    color={
                        !_.isUndefined(_config.fields.styling.color)
                            ? _config.fields.styling.color
                            : {
                                  hex: "#000000",
                                  rgb: {
                                      r: 0,
                                      g: 0,
                                      b: 0,
                                      a: 1
                                  }
                              }
                    }
                />
            </SettingsItem>
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.optin
                        .form.border_radius_input
                }
            >
                <InputNumber
                    name="fields-attrs-borderRadius"
                    nameValue={_config.fields.attrs.borderRadius}
                    placeholder="px"
                    saveRefValue={(key, value) => {
                        props.saveValues({
                            fields: {
                                attrs: {
                                    borderRadius: value
                                },
                                styling: {
                                    borderRadius: value + "px"
                                }
                            }
                        })
                    }}
                />
            </SettingsItem>
            <Border
                borderStyle={_config.fields.attrs.borderStyle}
                borderColor={
                    !_.isUndefined(_config.fields.attrs.borderColor)
                        ? _config.fields.attrs.borderColor
                        : {
                              hex: "#ffffff",
                              rgb: {
                                  r: 255,
                                  g: 255,
                                  b: 255,
                                  a: 1
                              }
                          }
                }
                borderWidth={_config.fields.attrs.borderWidth}
                borderTitle={false}
                saveRefValue={(key, value) => {
                    let obj = {
                        fields: {
                            attrs: {},
                            styling: {}
                        }
                    }

                    obj.fields.attrs[key] = value

                    if (key == "borderWidth") {
                        value += "px"
                    } else if (key == "borderColor") {
                        value = value.hex
                    }
                    obj.fields.styling[key] = value
                    props.saveValues(obj)
                }}
                item={{ selector: ".DELI-inputField" }}
            />
        </div>
    )
}

export default FormColor

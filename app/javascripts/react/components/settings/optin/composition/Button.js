import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import * as _ from "underscore"

import ColorSelector from "javascripts/react/components/ColorSelector"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import Border from "javascripts/react/components/settings/style/Border"

class Button extends Component {
    render() {
        const { config } = this.props

        return (
            <div className="container__settings__attributes settings__default">
                <SettingsItem label={translationDelipressReact.text}>
                    <input
                        className="delipress__input"
                        name="button-attrs-content"
                        onChange={this.props.changeInputValueInputText}
                        type="text"
                        value={config.button.attrs.content}
                    />
                </SettingsItem>
                {!this.props.naked &&
                    <div>
                        <SettingsItem
                            label={translationDelipressReact.background_color}
                        >
                            <ColorSelector
                                handleChangeComplete={color => {
                                    this.props.saveValue(
                                        "button-styling-backgroundColor",
                                        color
                                    )
                                }}
                                disabledAlpha={false}
                                picker="sketch"
                                idSelector=".DELI-formBloc .DELI-button"
                                typeColor="backgroundColor"
                                color={config.button.styling.backgroundColor}
                            />
                        </SettingsItem>
                        <SettingsItem
                            label={translationDelipressReact.text_color}
                        >
                            <ColorSelector
                                handleChangeComplete={color => {
                                    this.props.saveValue(
                                        "button-styling-color",
                                        color
                                    )
                                }}
                                disabledAlpha={false}
                                picker="sketch"
                                idSelector=".DELI-formBloc .DELI-button"
                                typeColor="color"
                                color={config.button.styling.color}
                            />
                        </SettingsItem>

                        <SettingsItem label={translationDelipressReact.margin}>
                            <InputNumber
                                name="fields_margin"
                                nameValue={config.button.attrs.margin}
                                placeholder="px"
                                saveRefValue={(key, value) => {
                                    this.props.saveValue(
                                        "button-styling-margin",
                                        value + "px"
                                    )
                                }}
                            />
                        </SettingsItem>
                        <SettingsItem
                            label={translationDelipressReact.border_radius}
                        >
                            <InputNumber
                                name="button-attrs-borderRadius"
                                nameValue={config.button.attrs.borderRadius}
                                placeholder="px"
                                saveRefValue={(key, value) => {
                                    this.props.saveValues({
                                        button: {
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
                            borderStyle={
                                !_.isUndefined(config.button.attrs.borderStyle)
                                    ? config.button.attrs.borderStyle
                                    : "solid"
                            }
                            borderColor={
                                !_.isUndefined(config.button.attrs.borderColor)
                                    ? config.button.attrs.borderColor
                                    : {
                                          rgb: {
                                              r: 255,
                                              g: 255,
                                              b: 255,
                                              a: 1
                                          },
                                          hex : "#ffffff"
                                      }
                            }
                            borderWidth={
                                !_.isUndefined(config.button.attrs.borderWidth)
                                    ? config.button.attrs.borderWidth
                                    : 0
                            }
                            borderTitle={false}
                            saveRefValue={(key, value) => {
                                let obj = {
                                    button: {
                                        attrs: {},
                                        styling: {}
                                    }
                                }

                                obj.button.attrs[key] = value

                                if (key == "borderWidth") {
                                    value += "px"
                                } else if (key == "borderColor") {
                                    value = value.hex
                                }
                                obj.button.styling[key] = value
                                this.props.saveValues(obj)
                            }}
                            item={{ selector: ".DELI-button" }}
                        />
                    </div>}
            </div>
        )
    }
}

Button.propTypes = {
    saveValue: PropTypes.func.isRequired,
    changeInputValueInputText: PropTypes.func.isRequired,
    config: PropTypes.object.isRequired
}

export default Button

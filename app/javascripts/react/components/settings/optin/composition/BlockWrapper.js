import React from "react"
import ColorSelector from "javascripts/react/components/ColorSelector"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import Border from "javascripts/react/components/settings/style/Border"
import Animation from "./Animation"
import OrientationFlyIn from "./OrientationFlyIn"

import {
    CONTENT_OPTIN,
    LOCKED_OPTIN,
    SCROLL_OPTIN,
    POPUP_OPTIN,
    FLY_IN_OPTIN,
    SHORTCODE_OPTIN,
    SMARTBAR_OPTIN,
    WIDGET_OPTIN
} from "javascripts/react/constants/OptinConstants"

import Fonts from "./Fonts"

const BlockWrapper = props => {
    const { config } = props

    return (
        <div className="container__settings__attributes settings__default">
            {props.type == FLY_IN_OPTIN && <OrientationFlyIn {...props} />}

            <Animation {...props} />

            <SettingsItem label={translationDelipressReact.background_color}>
                <ColorSelector
                    handleChangeComplete={color => {
                        props.saveValue(
                            "wrapper-styling-backgroundColor",
                            color
                        )
                    }}
                    idSelector=".DELI-wrapper"
                    typeColor="backgroundColor"
                    disabledAlpha={false}
                    picker="sketch"
                    color={props.config.wrapper.styling.backgroundColor}
                />
            </SettingsItem>
            <SettingsItem label={translationDelipressReact.text_color}>
                <ColorSelector
                    handleChangeComplete={color => {
                        props.saveValue("wrapper-styling-color", color)
                    }}
                    idSelector=".DELI-wrapper"
                    typeColor="color"
                    disabledAlpha={false}
                    picker="sketch"
                    color={props.config.wrapper.styling.color}
                />
            </SettingsItem>
            <Fonts {...props} />
            {/* <SettingsItem label={translationDelipressReact.max_width}>
                <InputNumber
                    name="wrapper-attrs-maxWidth"
                    nameValue={props.config.wrapper.attrs.maxWidth}
                    placeholder="px"
                    saveRefValue={(key, value) => {
                         props.saveValues({
                            wrapper : {
                                attrs : {
                                    maxWidth : value
                                },
                                styling : {
                                    maxWidth : value + "px"
                                }
                            }
                        })
                    }}
                />
            </SettingsItem> */}
            <SettingsItem label={translationDelipressReact.border_radius}>
                <InputNumber
                    name="wrapper-attrs-borderRadius"
                    nameValue={props.config.wrapper.attrs.borderRadius}
                    placeholder="px"
                    saveRefValue={(key, value) => {
                        props.saveValues({
                            wrapper: {
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
                borderStyle={props.config.wrapper.attrs.borderStyle}
                borderColor={props.config.wrapper.attrs.borderColor}
                borderWidth={props.config.wrapper.attrs.borderWidth}
                borderTitle={false}
                saveRefValue={(key, value) => {
                    let obj = {
                        wrapper: {
                            attrs: {},
                            styling: {}
                        }
                    }

                    obj.wrapper.attrs[key] = value

                    if (key == "borderWidth") {
                        value += "px"
                    } else if (key == "borderColor") {
                        value = value.hex
                    }
                    obj.wrapper.styling[key] = value
                    props.saveValues(obj)
                }}
                item={{ selector: ".DELI-wrapper" }}
            />
        </div>
    )
}

export default BlockWrapper

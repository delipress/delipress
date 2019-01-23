import React from "react"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"
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

const Animation = props => {
    const { config } = props

    let animations = {
        none: translationDelipressReact.Optin.animations.none,
        fadeIn: translationDelipressReact.Optin.animations.fadeIn,
        bounceIn: translationDelipressReact.Optin.animations.bounceIn,
        zoomIn: translationDelipressReact.Optin.animations.zoomIn,
        lightSpeedIn: translationDelipressReact.Optin.animations.lightSpeedIn,
        slideInUp: translationDelipressReact.Optin.animations.slideInUp,
        slideInLeft: translationDelipressReact.Optin.animations.slideInLeft,
        slideInRight: translationDelipressReact.Optin.animations.slideInRight,
        flipInX: translationDelipressReact.Optin.animations.flipInX,
        shake: translationDelipressReact.Optin.animations.shake,
        swing: translationDelipressReact.Optin.animations.swing,
        tada: translationDelipressReact.Optin.animations.tada
    }

    if (props.type == FLY_IN_OPTIN) {
        animations = _.omit(animations, [
            "bounceIn",
            "zoomIn",
            "flipInX",
            "shake",
            "swing",
            "tada"
        ])
    }

    if (props.type == SHORTCODE_OPTIN || props.type == WIDGET_OPTIN) {
        return null
    }

    return (
        <SettingsItem label={translationDelipressReact.animation}>
            <select
                name="animation"
                onChange={event => {
                    props.saveValues({
                        wrapper: {
                            attrs: {
                                animation: event.target.value
                            }
                        }
                    })
                }}
                value={props.config.wrapper.attrs.animation}
            >
                {_.map(animations, (option, key) => {
                    return (
                        <option key={`animated_${key}`} value={key}>
                            {option}
                        </option>
                    )
                })}
            </select>
        </SettingsItem>
    )
}

export default Animation

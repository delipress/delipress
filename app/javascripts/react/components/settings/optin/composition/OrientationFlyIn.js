import React from "react"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"

const OrientationFlyIn = props => {
    const { config } = props

    let orientations = [
        {
            value: "right",
            name: translationDelipressReact.orientations.right
        },
        {
            value: "left",
            name: translationDelipressReact.orientations.left
        },
        {
            value: "center",
            name: translationDelipressReact.orientations.center
        }
    ]

    return (
        <SettingsItem
            label={
                translationDelipressReact.Builder.component_settings.optin
                    .generals.orientation_fly_in
            }
        >
            <select
                name="orientation_fly_in"
                onChange={event => {
                    props.saveValues({
                        wrapper: {
                            attrs: {
                                orientation_fly_in: event.target.value
                            }
                        }
                    })
                }}
                value={props.config.wrapper.attrs.orientation_fly_in}
            >
                {_.map(orientations, (option, key) => {
                    return (
                        <option key={`animated_${key}`} value={option.value}>
                            {option.name}
                        </option>
                    )
                })}
            </select>
        </SettingsItem>
    )
}

export default OrientationFlyIn

import React, { Component } from "react"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"

const Fonts = props => {
    const { config } = props

    const font_list = [
        {
            value:
                "-apple-system, BlinkMacSystemFont, “Segoe UI”, Roboto, Helvetica, Arial, sans-serif",
            name: translationDelipressReact.Optin.fonts.system_font
        },
        {
            value: "",
            name: translationDelipressReact.Optin.fonts.website_font
        }
    ]

    const google_font_list = [
        {
            value: "Roboto",
            name: "Roboto"
        },
        {
            value: "Open Sans",
            name: "Open Sans"
        },
        {
            value: "Lato",
            name: "Lato"
        },
        {
            value: "Slabo 27px",
            name: "Slabo 27px"
        },
        {
            value: "Roboto Condensed",
            name: "Roboto Condensed"
        },
        {
            value: "Oswald",
            name: "Oswald"
        },
        {
            value: "Montserrat",
            name: "Montserrat"
        },
        {
            value: "Source Sans Pro",
            name: "Source Sans Pro"
        },
        {
            value: "Raleway",
            name: "Raleway"
        },
        {
            value: "PT Sans",
            name: "PT Sans"
        },
        {
            value: "Roboto Slab",
            name: "Roboto Slab"
        },
        {
            value: "Merriweather",
            name: "Merriweather"
        },
        {
            value: "Open Sans Condensed",
            name: "Open Sans Condensed"
        },
        {
            value: "Lora",
            name: "Lora"
        },
        {
            value: "Ubuntu",
            name: "Ubuntu"
        },
        {
            value: "Droid Sans",
            name: "Droid Sans"
        },
        {
            value: "Droid Serif",
            name: "Droid Serif"
        },
        {
            value: "Playfair Display",
            name: "Playfair Display"
        },
        {
            value: "PT Serif",
            name: "PT Serif"
        },
        {
            value: "Noto Sans",
            name: "Noto Sans"
        },
        {
            value: "Arimo",
            name: "Arimo"
        },
        {
            value: "Dosis",
            name: "Dosis"
        },
        {
            value: "Anton",
            name: "Anton"
        },
        {
            value: "Inconsolata",
            name: "Inconsolata"
        },
        {
            value: "Josefin Sans",
            name: "Josefin Sans"
        },
        {
            value: "Fira Sans",
            name: "Fira Sans"
        }
    ]

    return (
        <div>
            <SettingsItem label={translationDelipressReact.font_family}>
                <select
                    name="fonts"
                    onChange={event => {
                        const select = event.target
                        const fontValue = select.options[
                            select.selectedIndex
                        ].getAttribute("data-value")
                        props.saveValues({
                            wrapper: {
                                attrs: {
                                    fontFamily: select.value
                                },
                                styling: {
                                    fontFamily: fontValue
                                }
                            }
                        })
                    }}
                    value={config.wrapper.attrs.fontFamily}
                >
                    <optgroup label={translationDelipressReact.Optin.fonts.standard_fonts}>
                        {font_list.map((option, key) => {
                            return (
                                <option
                                    data-value={option.value}
                                    key={`font_${key}`}
                                    value={option.name}
                                >
                                    {option.name}
                                </option>
                            )
                        })}
                    </optgroup>

                    <optgroup label={translationDelipressReact.Optin.fonts.google_fonts}>
                        {google_font_list.map((option, key) => {
                            return (
                                <option
                                    data-value={option.value}
                                    key={`font_${key}`}
                                    value={option.name}
                                >
                                    {option.name}
                                </option>
                            )
                        })}
                    </optgroup>
                </select>
            </SettingsItem>
        </div>
    )
}

export default Fonts

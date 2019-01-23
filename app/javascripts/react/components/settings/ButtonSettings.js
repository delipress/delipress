import * as _ from "underscore"
import React, { Component } from "react"
import { connect } from "react-redux"

import ColorSelector from "../ColorSelector"
import BaseNewSettings from "javascripts/react/components/settings/base/BaseNewSettings"
import InnerPadding from "javascripts/react/components/settings/style/InnerPadding"
import Align from "javascripts/react/components/settings/style/Align"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import FontFamily from "javascripts/react/components/settings/style/FontFamily"
import Border from "javascripts/react/components/settings/style/Border"
import ApplyAll from "javascripts/react/components/settings/ApplyAll"

class ButtonSettings extends BaseNewSettings {
    constructor(props) {
        super(props)

        this.fontWeightOptions = [
            {
                value: "bold",
                text: "Bold"
            },
            {
                value: "lighter",
                text: "Light"
            },
            {
                value: "normal",
                text: "Normal"
            }
        ]

        this._handleChangePadding = this._handleChangePadding.bind(this)

        const { item } = this.props

        if (!_.isNull(item)) {
            this.state = {
                href: item.styles.href,
                value: item.styles.value
            }
        }

    }


    _handleChangePadding(newStyles) {
        this.styles = _.extend(this.styles, newStyles)

        this.saveEditor()
    }

    getColorBorder() {
        return {
            hex: "#000000",
            rgb: {
                r: 0,
                g: 0,
                b: 0,
                a: 1
            }
        }
    }

    render() {
        if (_.isNull(this.props.item) || _.isUndefined(this.props.item)) {
            return false
        }

        const { item } = this.props
        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .button.title_settings
                    }
                </span>
                <SettingsItem label={translationDelipressReact.text}>
                    <input
                        className="delipress__input"
                        name="value"
                        onChange={this._changeInputValueInputText}
                        type="text"
                        value={this.state.value}
                    />
                </SettingsItem>
                <SettingsItem label={translationDelipressReact.link}>
                    <input
                        className="delipress__input"
                        placeholder="https://delipress.io"
                        name="href"
                        onChange={this._changeInputValueInputText}
                        type="text"
                        value={this.state.href}
                    />
                </SettingsItem>
                <SettingsItem label={translationDelipressReact.font_size}>
                    <InputNumber
                        name="font-size"
                        nameValue={this.styles["font-size"]}
                        placeholder="px"
                        saveRefValue={this.saveOptionValue}
                    />
                </SettingsItem>
                <SettingsItem label={translationDelipressReact.font_weight}>
                    <select
                        name="font-weight"
                        onChange={this._changeInputValueText}
                        value={this.styles["font-weight"]}
                    >
                        {this.fontWeightOptions.map((option, key) => {
                            return (
                                <option
                                    key={`font_${key}`}
                                    value={option.value}
                                >
                                    {option.text}
                                </option>
                            )
                        })}
                    </select>
                </SettingsItem>
                <FontFamily
                    styles={this.styles}
                    onChangeFontFamily={font => {
                        this.saveOptionValue("font-family", font)
                    }}
                />
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .button.color
                    }
                >
                    <ColorSelector
                        handleChange={color => {
                            let _finalSelector = "p"
                            if (!_.isEmpty(this.styles["href"])) {
                                _finalSelector = "a"
                            }

                            const selectorP = jQuery(
                                `.id_selector_${index} ${_finalSelector}`
                            )

                            selectorP.css({
                                color: `rgba(${color.rgb.r}, ${color.rgb
                                    .g}, ${color.rgb.b}, ${color.rgb.a})`
                            })
                        }}
                        handleChangeComplete={color => {
                            this.saveOptionValue("color", color)
                        }}
                        picker="sketch"
                        idSelector={`.id_selector_${index} p`}
                        typeColor="color"
                        color={this.styles.color}
                    />
                </SettingsItem>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .button.background_color
                    }
                >
                    <ColorSelector
                        handleChange={color => {
                            let _finalSelector = "p"
                            if (!_.isEmpty(this.styles["href"])) {
                                _finalSelector = "a"
                            }
                            const selectorP = jQuery(
                                `.id_selector_${index} ${_finalSelector}`
                            )
                            const parentP = selectorP.parent()

                            selectorP.css({
                                backgroundColor: `rgba(${color.rgb.r}, ${color
                                    .rgb.g}, ${color.rgb.b}, ${color.rgb.a})`
                            })
                            parentP.css({
                                backgroundColor: `rgba(${color.rgb.r}, ${color
                                    .rgb.g}, ${color.rgb.b}, ${color.rgb.a})`
                            })
                        }}
                        handleChangeComplete={color => {
                            this.saveOptionValue("background-color", color)
                        }}
                        picker="sketch"
                        color={this.styles["background-color"]}
                    />
                </SettingsItem>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .button.width
                    }
                >
                    <InputNumber
                        name="width"
                        nameValue={this.styles.width}
                        placeholder="px"
                        saveRefValue={this.saveOptionValue}
                        min="0"
                        max="600"
                    />
                </SettingsItem>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .button.height
                    }
                >
                    <InputNumber
                        name="height"
                        nameValue={this.styles.height}
                        placeholder="px"
                        saveRefValue={this.saveOptionValue}
                    />
                </SettingsItem>
                <Align
                    onChangeSettingsAlign={value => {
                        this.saveOptionValue("align", value)
                    }}
                    styles={this.styles}
                />
                <Border
                    borderStyle={this.styles.borderStyle}
                    borderColor={this.styles.borderColor}
                    borderWidth={this.styles.borderWidth}
                    saveRefValue={this.saveOptionValue}
                    item={item}
                />

                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .button.border_radius
                    }
                >
                    <InputNumber
                        name="border-radius"
                        nameValue={this.styles["border-radius"]}
                        placeholder="px"
                        saveRefValue={this.saveOptionValue}
                    />
                </SettingsItem>
                <InnerPadding
                    onChangePadding={this._handleChangePadding}
                    item={this.props.item}
                />

                <ApplyAll
                    text={translationDelipressReact.Builder.component_settings.apply_all.replace("%{s}", translationDelipressReact.Builder.component.button)}
                    handleApply={this.handleApply}
                />
            </div>
        )
    }
}

function mapStateToProps(state) {
    if (_.isNull(state.EditorReducer.activeItem)) {
        return {
            item: null
        }
    }

    const arr = state.EditorReducer.activeItem.split("_")

    if (_.isUndefined(state.TemplateReducer.config.items[arr[0]])) {
        return {
            item: null
        }
    }

    return {
        item:
            state.TemplateReducer.config.items[arr[0]].columns[arr[1]].items[
                arr[2]
            ]
    }
}

export default connect(mapStateToProps)(ButtonSettings)

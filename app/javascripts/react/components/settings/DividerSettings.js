import * as _ from "underscore"
import React, { Component } from "react"
import { connect } from "react-redux"

import ColorSelector from "../ColorSelector"
import BaseNewSettings from "javascripts/react/components/settings/base/BaseNewSettings"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import ApplyAll from "javascripts/react/components/settings/ApplyAll"

class DividerSettings extends BaseNewSettings {
    constructor(props, ctx) {
        super(props, ctx)

        this.borderStyleOptions = [
            {
                value: "dashed",
                text: "Dashed"
            },
            {
                value: "dotted",
                text: "Dotted"
            },
            {
                value: "solid",
                text: "Solid"
            }
        ]
    }

    render() {

        if(_.isNull(this.props.item) || _.isUndefined(this.props.item)){
            return false
        }

        const { item } = this.props

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .divider.title_settings
                    }
                </span>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .divider.borderColor
                    }
                >
                    <ColorSelector
                        handleChange={color => {
                            jQuery(`.id_selector_${index} p`).css({
                                borderColor: `rgba(${ color.rgb.r }, ${ color.rgb.g }, ${ color.rgb.b }, ${ color.rgb.a })`
                            })
                        }}
                        handleChangeComplete={color => {
                            this.timeoutSave = 0
                            this.saveOptionValue("border-color", color)
                        }}
                        disabledAlpha={false}
                        picker="sketch"
                        color={this.styles["border-color"]}
                    />
                </SettingsItem>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .divider.borderStyle
                    }
                >
                    <select
                        placeholder="Select your border settings"
                        name="border-style"
                        onChange={(event) => {
                            this.timeoutSave = 0
                            this._changeInputValueText(event)
                        }}
                        value={this.styles["border-style"]}
                    >
                        {this.borderStyleOptions.map((option, key) => {
                            return (
                                <option
                                    key={`border_${key}`}
                                    value={option.value}
                                >
                                    {option.text}
                                </option>
                            )
                        })}
                    </select>
                </SettingsItem>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings.divider.borderPx
                    }
                >
                    <InputNumber
                        name="border-width"
                        nameValue={this.styles["border-width"]}
                        placeholder="px"
                        saveRefValue={this.saveOptionValue}
                    />
                </SettingsItem>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .divider.borderWidth
                    }
                >
                    <InputNumber
                        name="width"
                        nameValue={this.styles["width"]}
                        placeholder="%"
                        saveRefValue={this.saveOptionValue}
                        max={100}
                        min={1}
                    />
                </SettingsItem>
                <ApplyAll
                    text={translationDelipressReact.Builder.component_settings.apply_all.replace("%{s}", translationDelipressReact.Builder.component.divider)}
                    handleApply={this.handleApply}
                />
            </div>
        )
    }
}


function mapStateToProps(state){
    if(_.isNull(state.EditorReducer.activeItem)){
        return {
            item : null
        }
    }

    const arr = state.EditorReducer.activeItem.split("_")

    return {
        item : state.TemplateReducer.config.items[arr[0]].columns[arr[1]].items[arr[2]]
    }
}

export default connect(mapStateToProps)(DividerSettings)

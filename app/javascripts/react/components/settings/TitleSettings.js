import * as _ from "underscore"
import React, { Component } from "react"
import { connect } from "react-redux"

import ColorSelector from "../ColorSelector"

import BaseNewSettings from "javascripts/react/components/settings/base/BaseNewSettings"
import Align from "javascripts/react/components/settings/style/Align"
import FontFamily from "javascripts/react/components/settings/style/FontFamily"
import SizeTitle from "javascripts/react/components/settings/style/SizeTitle"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import ApplyAll from "javascripts/react/components/settings/ApplyAll"

class TitleSettings extends BaseNewSettings {
    constructor(props) {
        super(props)

        this.onChangeSettingsSizeTitle = this.onChangeSettingsSizeTitle.bind(
            this
        )
        this.handleApply = this.handleApply.bind(this)
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
    }

    handleApply(e) {
        e.preventDefault()

        localStorage.setItem('dp_default_component_' + this.props.item.type, JSON.stringify(this.styles) );
        
        const selectedPreset = this.styles.presetChoice
        const selectedPresetValues = _.findWhere(this.styles.presets, {
            type: selectedPreset
        })

        this.props.updateAllStyles(selectedPresetValues, selectedPreset)
    }

    onChangeSettingsSizeTitle(value) {
        this.styles.presetChoice = value

        this.saveEditor()
    }

    render() {
        if (_.isNull(this.props.item) || _.isUndefined(this.props.item)) {
            return false
        }

        const { item } = this.props

        let applyText =
            translationDelipressReact.Builder.component_settings.apply_all

        const presetChoice = _.find(this.styles.presets, {
            type: this.styles.presetChoice
        })

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`
        const idSelector = `.id_selector_${index} *`

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .title.title_settings
                    }
                </span>
                <SizeTitle
                    onChangeSettingsSizeTitle={this.onChangeSettingsSizeTitle}
                    item={item}
                />
                <SettingsItem label={translationDelipressReact.font_size}>
                    <InputNumber
                        name="font-size"
                        nameValue={presetChoice["font-size"]}
                        placeholder="px"
                        saveRefValue={this.saveOptionValueOnPreset}
                    />
                </SettingsItem>

                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .text.line_height
                    }
                >
                    <InputNumber
                        name="line-height"
                        nameValue={presetChoice["line-height"]}
                        step={0.1}
                        min={1}
                        max={2}
                        saveRefValue={this.saveOptionValueOnPreset}
                    />
                </SettingsItem>

                <SettingsItem label={translationDelipressReact.font_weight}>
                    <select
                        name="font-weight"
                        onChange={this._changeInputValueTextOnPreset}
                        value={presetChoice["font-weight"]}
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
                        this.timeoutSave = 0
                        this.saveOptionValueOnPreset("font-family", font)
                    }}
                />

                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .button.color
                    }
                >
                    <ColorSelector
                        handleChangeComplete={color => {
                            this.timeoutSave = 0
                            this.saveOptionValueOnPreset("color", color)
                        }}
                        disabledAlpha={false}
                        picker="sketch"
                        idSelector={idSelector}
                        typeColor="color"
                        color={presetChoice.color}
                    />
                </SettingsItem>

                <Align
                    onChangeSettingsAlign={value => {
                        this.timeoutSave = 0
                        this.saveOptionValueOnPreset("align", value)
                    }}
                    styles={this.styles}
                />
                <ApplyAll 
                    text={applyText.replace("%{s}", this.styles.presetChoice)}
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

    return {
        item:
            state.TemplateReducer.config.items[arr[0]].columns[arr[1]].items[
                arr[2]
            ]
    }
}

export default connect(mapStateToProps)(TitleSettings)

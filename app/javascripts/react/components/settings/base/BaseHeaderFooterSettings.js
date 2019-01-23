import React, { Component, cloneElement } from "react"

import ColorSelector from "javascripts/react/components/ColorSelector"
import BaseNewSettings from "javascripts/react/components/settings/base/BaseNewSettings"
import Align from "javascripts/react/components/settings/style/Align"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import FontFamily from "javascripts/react/components/settings/style/FontFamily"

class BaseHeaderFooterSettings extends BaseNewSettings {
    render() {
        if (_.isNull(this.props.item) || _.isUndefined(this.props.item)) {
            return false
        }

        const { item } = this.props

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        const sideTitle =
            item.keyRow == "unsubscribe"
                ? translationDelipressReact.Builder.component.footer
                : translationDelipressReact.Builder.component.header

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {sideTitle}
                </span>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .text.line_height
                    }
                >
                    <InputNumber
                        name="line-height"
                        min={1}
                        max={2}
                        step={0.1}
                        nameValue={this.styles["line-height"]}
                        saveRefValue={this.saveOptionValue}
                    />
                </SettingsItem>
                <Align
                    onChangeSettingsAlign={value => {
                        this.timeoutSave = 0
                        this.saveOptionValue("align", value)
                    }}
                    styles={this.styles}
                />

                <SettingsItem label={translationDelipressReact.color}>
                    <ColorSelector
                        handleChangeComplete={color => {
                            this.timeoutSave = 0
                            this.saveOptionValue("color", color)
                        }}
                        disabledAlpha={false}
                        picker="sketch"
                        idSelector={".id_selector_" + index + " p"}
                        typeColor="color"
                        color={this.styles["color"]}
                    />
                </SettingsItem>

                <SettingsItem label={translationDelipressReact.font_size}>
                    <InputNumber
                        name="font-size"
                        nameValue={this.styles["font-size"]}
                        min={0}
                        step={1}
                        placeholder="px"
                        saveRefValue={this.saveOptionValue}
                    />
                </SettingsItem>
                <FontFamily
                    styles={this.styles}
                    onChangeFontFamily={font => {
                        this.timeoutSave = 0
                        this.saveOptionValue("font-family", font)
                    }}
                />
            </div>
        )
    }
}

export default BaseHeaderFooterSettings

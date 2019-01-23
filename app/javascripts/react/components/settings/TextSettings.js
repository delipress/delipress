import * as _ from "underscore"
import React, { Component } from "react"

import { connect } from "react-redux"
import { compose, bindActionCreators } from "redux"

import BaseNewSettings from "javascripts/react/components/settings/base/BaseNewSettings"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import FontFamily from "javascripts/react/components/settings/style/FontFamily"
import ApplyAll from "javascripts/react/components/settings/ApplyAll"

class TextSettings extends BaseNewSettings {

    render() {
        if (_.isNull(this.props.item) || _.isUndefined(this.props.item)) {
            return false
        }

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .text.title_settings
                    }
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
                        this.saveOptionValue("font-family", font)
                    }}
                />

                <ApplyAll
                    text={translationDelipressReact.Builder.component_settings.apply_all.replace("%{s}", translationDelipressReact.Builder.component.text)}
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

export default connect(mapStateToProps)(TextSettings)

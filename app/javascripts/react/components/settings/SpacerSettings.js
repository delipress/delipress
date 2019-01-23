import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import classNames from 'classnames'
import { connect } from "react-redux"

import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'
import BaseNewSettings from 'javascripts/react/components/settings/base/BaseNewSettings'
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import ApplyAll from "javascripts/react/components/settings/ApplyAll"

class SpacerSettings extends BaseNewSettings  {

    render() {

        if(_.isNull(this.props.item) || _.isUndefined(this.props.item)){
            return false
        }

        const {
            height
        } = this.styles

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.spacer.title}
                </span>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .spacer.height
                    }
                >
                    <InputNumber
                        name="height"
                        nameValue={height}
                        placeholder="px"
                        min={5}
                        saveRefValue={this.saveOptionValue}
                    />
                </SettingsItem>
                <ApplyAll
                    text={translationDelipressReact.Builder.component_settings.apply_all.replace("%{s}", translationDelipressReact.Builder.component.spacer)}
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

export default connect(mapStateToProps)(SpacerSettings)

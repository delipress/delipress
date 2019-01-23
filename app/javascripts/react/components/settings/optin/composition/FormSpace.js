import * as _ from "underscore"
import React, { Component } from "react"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import InputNumber from "javascripts/react/components/inputs/InputNumber"

const FormSpace = props => {

    let _config = props.config
    if(_.isUndefined(_config.fields)){
        _config = _.extend({}, _config, {
            fields : {
                attrs:  {
                    margin : 0
                }
            }
        })
    }

    return (
        <SettingsItem
            label={
                translationDelipressReact.Builder.component_settings.optin.form
                    .form_space
            }
        >
            <InputNumber
                name="fields_margin"
                nameValue={_config.fields.attrs.margin}
                placeholder="px"
                saveRefValue={(key, value) => {
                    props.saveValues({
                        fields: {
                            attrs: {
                                margin: value
                            },
                            styling: {
                                margin: value + "px"
                            }
                        }
                    })
                }}
            />
        </SettingsItem>
    )
}

export default FormSpace

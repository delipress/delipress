import * as _ from "underscore"
import React, { Component } from "react"

import BaseSettingsOptin from "javascripts/react/components/settings/base/BaseSettingsOptin"

import Button from "javascripts/react/components/settings/optin/composition/Button"
import Image from "javascripts/react/components/settings/optin/composition/Image"
import BlockWrapper from "javascripts/react/components/settings/optin/composition/BlockWrapper"
import FormWrapper from "javascripts/react/components/settings/optin/composition/FormWrapper"
import FormRgpd from "javascripts/react/components/settings/optin/composition/FormRgpd"
import FormDesign from "javascripts/react/components/settings/optin/composition/FormDesign"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import Checkbox from "javascripts/react/components/inputs/Checkbox"

class BaseDefault extends BaseSettingsOptin {
    isNaked() {
        return this.allConfig.default_settings.form_wrapper.attrs.naked
    }

    render() {
        if (_.isUndefined(this.allConfig.default_settings.form_wrapper)) {
            this.allConfig.default_settings = _.extend(
                {},
                this.allConfig.default_settings,
                {
                    form_wrapper: {
                        attrs: {
                            naked: false,
                            metas: "empty"
                        }
                    }
                }
            )
        }

        const formWrapperAttrs = this.props.config.default_settings.form_wrapper.attrs
        const FormDesignEnable = !this.isNaked() && !formWrapperAttrs.fields_enable ? false : true


        let ButtonTitle = ""

        if (formWrapperAttrs.fields_enable) {
            ButtonTitle = translationDelipressReact.Builder.component_settings.optin.generals.title_button
        } else {
            ButtonTitle = translationDelipressReact.Builder.component_settings.optin.generals.title_button_redirect
        }

        return <div className="container__settings__attributes settings__default">
                {!this.isNaked() && <span className="delipress__builder__side__title">
                        {
                            translationDelipressReact.Builder
                                .component_settings.optin.generals
                                .title_bloc
                        }
                    </span>}

                {!this.isNaked() && <BlockWrapper saveValue={this._saveValue} saveValues={this._saveValues} config={this.config} type={this.props.type} />}

                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder
                            .component_settings.optin.generals
                            .title_image
                    }
                </span>

                <Image saveValue={this._saveValue} saveValues={this._saveValues} config={this.config} naked={this.isNaked()} />

                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder
                            .component_settings.optin.generals
                            .title_form
                    }
                </span>

                <FormWrapper saveValue={this._saveValue} saveValues={this._saveValues} config={this.config} />

                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder
                            .component_settings.optin.generals
                        .title_form_rgpd
                    }
                </span>

                <FormRgpd saveValue={this._saveValue} saveValues={this._saveValues} config={this.config} />

                {FormDesignEnable && <span className="delipress__builder__side__title">
                        {
                            translationDelipressReact.Builder
                                .component_settings.optin.generals
                                .title_form_design
                        }
                    </span>}

                {FormDesignEnable && <FormDesign saveValue={this._saveValue} saveValues={this._saveValues} config={this.config} />}

                <span className="delipress__builder__side__title">
                    {ButtonTitle}
                </span>

                <Button saveValue={this._saveValue} saveValues={this._saveValues} changeInputValueInputText={this._changeInputValueInputText} config={this.config} naked={this.isNaked()} />
            </div>;
    }
}

export default BaseDefault

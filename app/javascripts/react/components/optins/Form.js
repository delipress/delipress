import React, { Component } from "react"
import * as _ from "underscore"

import { prepareCssOptin } from "javascripts/react/helpers/prepareCssOptin"
import TextOptinSettings from "javascripts/react/components/settings/optin/TextOptinSettings"

// Composition
import Wrapper from "./composition/Wrapper"
import WrapperImage from "./composition/WrapperImage"
import WrapperText from "./composition/WrapperText"
import WrapperForm from "./composition/WrapperForm"
import Rgpd from "./composition/Rgpd"
import Title from "./composition/Title"
import Message from "./composition/Message"

function versionCompareDelipress(left, right) {
    if (typeof left + typeof right != 'stringstring')
        return false;

    var a = left.split('.')
        , b = right.split('.')
        , i = 0, len = Math.max(a.length, b.length);

    for (; i < len; i++) {
        if ((a[i] && !b[i] && parseInt(a[i]) > 0) || (parseInt(a[i]) > parseInt(b[i]))) {
            return 1;
        } else if ((b[i] && !a[i] && parseInt(b[i]) > 0) || (parseInt(a[i]) < parseInt(b[i]))) {
            return -1;
        }
    }

    return 0;
}

class Form extends Component {
    constructor(props) {
        super(props)

        this.conditionForm = this.conditionForm.bind(this)
    }

    getMetaConfig(key, meta, params = {}) {
        const { settings, defaultConfig } = this.props

        const config = this.props.config[settings + "_settings"]

        switch (meta) {
            case "styling":
                return _.isUndefined(config[key])
                    ? !_.isUndefined(defaultConfig[key]) ? prepareCssOptin(defaultConfig[key].styling) : {}
                    : prepareCssOptin(config[key].styling)
                break
            case "attrs":
                return _.isUndefined(config[key])
                    ? !_.isUndefined(defaultConfig[key]) ? defaultConfig[key].attrs[params["attrs"]] : null
                    : config[key].attrs[params["attrs"]]
                break
        }
    }

    getStyleConfig(key) {
        if(this.isNaked()) {
            return {}
        }

        return this.getMetaConfig(key, "styling")
    }

    getAttrsConfig(key, attrs) {
        return this.getMetaConfig(key, "attrs", {
            attrs: attrs
        })
    }

    conditionForm() {
        if (this.props.settings == "success") {
            if (
                !this.getAttrsConfig(
                    "email_input_form",
                    "disable_email_input_form"
                ) ||
                false
            ) {
                return true
            } else {
                return false
            }
        } else {
            return true
        }
    }

    isNaked(){
        const { config } = this.props

        return !_.isUndefined(config.default_settings.form_wrapper) ? config.default_settings.form_wrapper.attrs.naked : false
    }

    render() {
        let { config, defaultConfig, settings } = this.props

        if(_.isUndefined(defaultConfig.information_rgpd)){
            defaultConfig.information_rgpd = { attrs: { content: `${translationDelipressReact.Builder.component_settings.optin.rgpd.information} ${configDelipressRgpd.email_admin}` } };
        }
        const metas = this.getAttrsConfig("form_wrapper", "metas")

        const fieldsEnable = this.getAttrsConfig("form_wrapper", "fields_enable")
        const rgpdEnable = this.getAttrsConfig("rgpd", "active_rgpd")

        return <Wrapper style={this.getStyleConfig("wrapper")} defaultConfig={defaultConfig} type={this.props.config.type} config={config[settings + "_settings"]}>
                <WrapperImage defaultConfig={defaultConfig}>
                    <img style={this.getStyleConfig("wrapper_image")} className="DELI-image" src={this.getAttrsConfig("wrapper_image", "url")} />
                </WrapperImage>

                <WrapperText settings={settings}>
                    <Title settings={settings} style={this.getStyleConfig("title")}>
                        <TextOptinSettings config={config} name="title" settings={settings} defaultConfig={defaultConfig} updateOptin={this.props.params.updateOptin} />
                    </Title>
                    <Message>
                        <TextOptinSettings config={config} name="message" settings={this.props.settings} defaultConfig={this.props.defaultConfig} updateOptin={this.props.params.updateOptin} />
                    </Message>
                </WrapperText>

                <WrapperForm formSize={config.default_settings.form_wrapper.attrs.form_size} style={this.getStyleConfig("form_wrapper")} condition={this.conditionForm}>
                    {fieldsEnable && versionCompareDelipress(configDelipressRgpd.wp_version, "4.9.6") >= 0 && <div>
                                {metas === "single_field" && <input className="DELI-inputField" type="text" style={this.getStyleConfig("fields")} placeholder={config.default_settings.form_wrapper.attrs.name_placeholder} />}
                                {metas === "first_last_name" && <input className="DELI-inputField" type="text" style={this.getStyleConfig("fields")} placeholder={config.default_settings.form_wrapper.attrs.firstname_placeholder} />}
                                {metas === "first_last_name" && <input className="DELI-inputField" type="text" style={this.getStyleConfig("fields")} placeholder={config.default_settings.form_wrapper.attrs.lastname_placeholder} />}
                                <input className="DELI-inputField" type="email" style={this.getStyleConfig("fields")} required placeholder={config.default_settings.form_wrapper.attrs.email_placeholder} />
                            </div>}
                    {rgpdEnable && <Rgpd>
                            <div style={{ fontSize: "13px", padding: '0 30px', margin:'12px 0 0 0', opacity: '.8' }}>
                                <TextOptinSettings config={config} name="information_rgpd" settings={settings} defaultConfig={defaultConfig} updateOptin={this.props.params.updateOptin} />
                            </div>
                            <a href={this.getAttrsConfig("rgpd", "url_privacy")} style={{ fontSize: "13px", display: "inline-block", margin: "10px 0 16px", color: 'inherit', textDecoration: "underline", opacity: '.8' }} target='_blank'>
                                {translationDelipressReact.Builder.component_settings.optin.rgpd.view_privacy_policy}
                            </a>
                        </Rgpd>}

                    <button style={this.getStyleConfig("button")} className="DELI-button" type="submit">
                        {this.getAttrsConfig("button", "content")}
                    </button>
                </WrapperForm>
            </Wrapper>;
    }
}

export default Form

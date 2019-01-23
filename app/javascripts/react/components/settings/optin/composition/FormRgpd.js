import React, { Component } from "react"
import * as _ from "underscore"

import ColorSelector from "javascripts/react/components/ColorSelector"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import Border from "javascripts/react/components/settings/style/Border"
import Checkbox from "javascripts/react/components/inputs/Checkbox"


function versionCompareDelipress(left, right) {
    if (typeof left + typeof right != "stringstring") return false;

    var a = left.split("."),
        b = right.split("."),
        i = 0,
        len = Math.max(a.length, b.length);

    for (; i < len; i++) {
        if (
            (a[i] && !b[i] && parseInt(a[i]) > 0) ||
            parseInt(a[i]) > parseInt(b[i])
        ) {
            return 1;
        } else if (
            (b[i] && !a[i] && parseInt(b[i]) > 0) ||
            parseInt(a[i]) < parseInt(b[i])
        ) {
            return -1;
        }
    }

    return 0;
}

class FormRgpd extends Component {

    render() {
        const { config } = this.props

        if (versionCompareDelipress(configDelipressRgpd.wp_version, "4.9.6") < 0){
            return false
        }

        let _config = config
        if(_.isUndefined(_config.rgpd)){
            _config.rgpd = { attrs: { active_rgpd: 0 } };
        }

        return <div className="container__settings__attributes settings__default">
            <SettingsItem id="form-attrs-active_rgpd" label={translationDelipressReact.Builder.component_settings.optin.form.active_rgpd}>
                    <Checkbox id="form-attrs-active_rgpd" defaultChecked={_config.rgpd.attrs.active_rgpd} handleChange={e => {
                            this.props.saveValues({
                                rgpd: {
                                    attrs: {
                                        active_rgpd: e.target.checked
                                    }
                                }
                            });
                        }} />
                </SettingsItem>
                <SettingsItem label={translationDelipressReact.Builder.component_settings.optin.form.url_privacy}>
                    <p>
                        {translationDelipressReact.Builder.component_settings.optin.form.manage_your}&nbsp;
                        <a
                            href={configDelipressRgpd.admin_privacy}
                            target="_blank"
                        >
                            {translationDelipressReact.Builder.component_settings.optin.form.privacy_page}
                        </a>
                    </p>
                </SettingsItem>
            </div>;
    }
}

export default FormRgpd

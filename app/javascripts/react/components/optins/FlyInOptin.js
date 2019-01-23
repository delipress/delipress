import React, { Component } from "react"
import Form from "./Form"
import classNames from "classnames"

class FlyInOptin extends Component {
    render() {
        const { config } = this.props
        const _class = classNames(
            {
                ["DELI-Flyin--" +
                config.default_settings.wrapper.attrs.orientation_fly_in]:
                    config.default_settings.wrapper.attrs.orientation_fly_in !=
                    "none"
            },
            "DELI-Orientation",
            "DELI-Content",
            "DELI-Flyin"
        )
        return (
            <div id="DELI-Optin" className={_class}>
                <Form
                    {...this.props}
                    defaultConfig={config["default_settings"]}
                />
            </div>
        )
    }
}

export default FlyInOptin

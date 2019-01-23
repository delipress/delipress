import React, { Component } from "react"

import Form from "./Form"

class PopupOptin extends Component {
    render() {
        return (
            <div
                id="DELI-Optin"
                className="DELI-Orientation DELI-Content DELI-Popup-Builder"
            >
                <Form
                    {...this.props}
                    defaultConfig={this.props.config["default_settings"]}
                />
            </div>
        )
    }
}

export default PopupOptin

import React, { Component } from "react"

import Button from "javascripts/react/components/settings/optin/composition/Button"
import Image from "javascripts/react/components/settings/optin/composition/Image"
import Default from "./Default"
import Success from "./Success"

class WidgetSettings extends Component {
    render() {
        return (
            <div className="delipress__builder__side__panel__scroll">
                <div className="delipress__builder__side__panel__tabcontent">
                    {
                        this.props.settings == "default" &&
                        <Default {...this.props} />
                    }
                    {
                        this.props.settings == "success" &&
                        <Success {...this.props} />
                    }
                </div>
            </div>
        )
    }
}

export default WidgetSettings

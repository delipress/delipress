import React, { Component } from "react"

import Default from "./Default"
import Success from "./Success"

class ShortcodeSettings extends Component {
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

export default ShortcodeSettings

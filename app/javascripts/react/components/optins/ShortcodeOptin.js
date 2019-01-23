import React, { Component } from 'react';
import resetcss from "./helpers/reset"
import utils from "./helpers/utils"
import Form from './Form'

class ShortcodeOptin extends Component {

    render() {
        return (
            <div id="DELI-Optin" className="DELI-Orientation DELI-Content">
                <Form
                    {...this.props}
                    defaultConfig={this.props.config["default_settings"]}
                ></Form>
            </div>
        )
    }
}

export default ShortcodeOptin
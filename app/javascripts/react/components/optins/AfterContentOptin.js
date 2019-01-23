import React, { Component } from 'react';
import Form from './Form'

class AfterContentOptin extends Component {

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

export default AfterContentOptin
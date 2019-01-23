import { h, render, Component } from "preact"
import utils from "./utils"
import resetcss from "./styles/reset"
import Form from "./elements/Form"
import OptinScrollr from "./OptinScrollr"
import { FLY_IN_OPTIN } from "./OptinConstants"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

class FlyInOptin extends Component {
    constructor(props) {
        super(props)
        this.state = {
            isHidden: false
        }

        this.handleDismiss    = this.handleDismiss.bind(this)
    }

    handleDismiss() {

        this.setState({
            isHidden: true
        })

    }


    render() {
        if (this.state.isHidden) {
            return false
        }

        const orientationFlyIn =
            "DELI-Flyin--" +
            this.props.config.default_settings.wrapper.attrs.orientation_fly_in
        const flyInClasses = `DELI-Orientation DELI-Flyin ${orientationFlyIn}`

        return (
            // This first div should get the UID as an ID. This ID will also be used to prefix custom css from user
            <div id="DELI-Optin" className={flyInClasses}>
                <Form
                    {...this.props}
                    type={FLY_IN_OPTIN}
                    handleDismiss={this.handleDismiss}
                />
            </div>
        )
    }
}

export default FlyInOptin

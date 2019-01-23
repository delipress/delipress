import { h, render, Component } from "preact"
import utils from "./utils"
import resetcss from "./styles/reset"
import Form from "./elements/Form"
import OptinScrollr from "./OptinScrollr"
import {
    POPUP_OPTIN
} from "./OptinConstants"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

class PopupOptin extends Component {
    constructor(props) {
        super(props)
        this.state = {
            isHidden: false
        }

        this.handleDismiss    = this.handleDismiss.bind(this)
        this.handleDismissEsc = this.handleDismissEsc.bind(this)
    }

    handleDismiss() {
        this.setState({
            isHidden: true
        })

        document.removeEventListener("keydown", this.handleDismissEsc, false)
    }

    handleDismissEsc(e) {
        if (e.keyCode == 27) {
            this.handleDismiss()
        }
    }

    componentWillMount() {
        document.addEventListener("keydown", this.handleDismissEsc, false)
    }

    componentWillUnMount() {
        document.removeEventListener("keydown", this.handleDismissEsc)
    }

    render() {
        if (this.state.isHidden) {
            return false
        }

        return (
            // This first div should get the UID as an ID. This ID will also be used to prefix custom css from user
            <div id="DELI-Optin" className="DELI-Orientation DELI-Popup">
                <div onClick={this.handleDismiss} className="DELI-Overlay" />
                <Form
                    {...this.props}
                    type={POPUP_OPTIN}
                    handleDismiss={this.handleDismiss}
                />
            </div>
        )
    }
}

export default PopupOptin

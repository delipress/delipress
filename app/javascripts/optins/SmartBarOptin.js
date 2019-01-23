import { h, render, Component } from "preact"
import utils from "./utils"
import resetcss from "./styles/reset"
import OptinScrollr from "./OptinScrollr"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

const COOKIE_NAME = "DELI-SmartBarOptin"

class SmartBarOptin extends Component {
    constructor(props) {
        super(props)
        this.state["isHidden"] = true
        const cookie = utils.dOptinVisible(COOKIE_NAME, this.props.visibility)

        // Check if the component should render or not
        if (this.props.delayed) {
            window.setTimeout(() => {
                this.setState({ isHidden: cookie })
            }, this.props.delayed * 1000)
        } else if (this.props.scroll) {
            // Check window height and page height + percent page height
            // And choose between scroll and real appear
            // Also check if element is to high and we can't scroll to him
            // Change scroll to when it's bottom of page and not only top
            // We might want the bar to appear when comment enter the screen but article still visible
            new OptinScrollr(this.props.scroll, true, scrollState => {
                this.setState({ isHidden: !scrollState })
            })
        } else {
            this.state.isHidden = cookie
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)

        this.state.emailField = ""
        this.state.smartBarH = 0
    }

    componentDidMount() {
        // Adjust elements to correctly include the Fixed Optin
        if (this.props.delayed || this.props.scroll) return false

        this.prepareOptinInsert()
    }

    componentDidUpdate() {
        if (!this.state.isHidden) {
            this.prepareOptinInsert()
        } else {
            this.prepareOptinInsert(true)
        }
    }

    /**
     * Prepare the page to receive the optin
     * Change body padding to take optin into account
     * and add optin height to each fixed element
     */
    prepareOptinInsert(reversed = false) {
        // Check if admin-bar present
        const bodyPaddingTop = utils.dCssParsed(document.body, "padding-top")
        let optinHeight = 0

        if (this.state.smartBarH == 0) {
            optinHeight = utils.dCssParsed(
                document.getElementById("DELI-Optin"),
                "height"
            )
            this.state.smartBarH = optinHeight
        } else {
            optinHeight = this.state.smartBarH
        }
        // We reverse the value of optinHeight to come back to normal
        optinHeight = reversed == false ? optinHeight : -optinHeight

        if (!this.props.scroll) {
            document.body.style.paddingTop = bodyPaddingTop + optinHeight + "px"
        }

        const elems = document.querySelectorAll("*")
        for (var i = 0; i < elems.length; i++) {
            var el = elems[i];
             if (utils.dCss(el, "position") == "fixed" && el.getAttribute("id") != "DELI-Optin") {
               el.style.top = utils.dCssParsed(el, "top") + optinHeight + "px"
             }
        }
    }

    handleChange(e) {
        this.setState({ emailField: e.target.value.trim() })
    }

    handleSubmit(e) {
        e.preventDefault()
        let data = {
            action: "subscribe",
            email: this.state.emailField
        }
        utils.dJax(ajaxurl, data, r => {
            if (r.status == "200" && r.responseText != "") {
            }
        })
    }

    render() {
        if (this.state.isHidden) return null
        return (
            <div
                style={styles.fixedOptin}
                id="DELI-Optin"
                className="DELI-fixedOptin"
            >
                <style>
                    {resetcss().replace(/\B\s+/g, "")}
                </style>
                <div style={styles.fixedOptinTitle}>
                    Join our Newsletter today for free
                </div>
                <form style={styles.form} onSubmit={this.handleSubmit}>
                    <div style={styles.divField}>
                        <input
                            style={styles.inputField}
                            value={this.state.emailField}
                            onChange={this.handleChange}
                            type="email"
                            placeholder="Enter your email"
                            id="Deli-Email"
                        />
                    </div>
                    <div style={styles.divField}>
                        <button
                            style={styles.button}
                            onClick={this.handleSubmit}
                        >
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        )
    }
}

export default SmartBarOptin

import { h, render, Component } from "preact"
import resetcss from "./styles/reset"
import utils from "./utils"
import OptinScrollr from "./OptinScrollr"

import {
    SHORTCODE_OPTIN,
    POPUP_OPTIN,
    FLY_IN_OPTIN,
    SMARTBAR_OPTIN,
    SCROLL_OPTIN,
    LOCKED_OPTIN,
    WIDGET_OPTIN,
    AFTER_CONTENT_OPTIN,
    CONTACT_FORM_7
} from "./OptinConstants"

import ContentOptin from "./ContentOptin"
import PopupOptin from "./PopupOptin"
import ShortcodeOptin from "./ShortcodeOptin"
import FlyInOptin from "./FlyInOptin"
import WidgetOptin from "./WidgetOptin"
import AfterContentOptin from "./AfterContentOptin"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

class BaseOptin extends Component {
    constructor(props) {
        super(props)
        // Simple way to compress the css by removing spaces
        this.resetCss = resetcss().replace(/\B\s+/g, "")
        this.emailField = ""
        this.firstName = ""
        this.lastName = ""

        this.alreadyView = false

        const formWrapperAttrs =
            props.config.default_settings.form_wrapper.attrs

        this.state = {
            isHidden: false,
            submitDisabled: false,
            success: false,
            errors: false,
            emailField: "",
            firstName: "",
            lastName: "",
            redirectUrl: formWrapperAttrs.redirect_url
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.exitIntent = this.exitIntent.bind(this)
        this.getOptin = this.getOptin.bind(this)
    }

    componentDidMount() {
        if (
            this.props.behavior != null &&
            this.props.behavior.range_session != null
        ) {
            if (this.props.behavior.range_session != 0) {
                const duration = {
                    number:
                        this.props.behavior == null
                            ? ""
                            : this.props.behavior.number_days_session,
                    range:
                        this.props.behavior == null
                            ? ""
                            : this.props.behavior.range_session
                }
                const value = duration.number + duration.range

                utils.dCookieCreate(this.props.id, value, duration)
            }
        }
        if (!this.state.isHidden) {
            utils.dStat(this.props.id)
        }
    }

    componentDidUpdate() {
        if (!this.state.isHidden) {
            utils.dStat(this.props.id)
            // Create cookie when shown
            const duration = {
                number:
                    this.props.behavior == null
                        ? ""
                        : this.props.behavior.number_days_session,
                range:
                    this.props.behavior == null
                        ? ""
                        : this.props.behavior.range_session
            }
            const value = duration.number + duration.range

            utils.dCookieCreate(this.props.id, value, duration)
        }
    }

    handleChange(e, field) {
        this[field] = e.target.value.trim()
    }

    handleSubmit(e) {
        e.preventDefault()

        this.setState({ submitDisabled: true })
        let data = {
            action: "delipress_subscriber_on_list",
            id: this.props.id,
            email: this.emailField,
            first_name: this.firstName,
            last_name: this.lastName
        }

        utils.dJax(ajaxurl, data, r => {
            if (r.status == "200" && r.responseText != "") {
                this.emailField = ""
                this.firstName = ""
                this.lastName = ""
                if (
                    this.props.behavior !== null &&
                    this.props.behavior.auto_close_after_subscribe
                ) {
                    this.setState({ isHidden: true })
                } else {
                    this.setState({
                        success: true,
                        emailField: "",
                        firstName: "",
                        lastName: "",
                        submitDisabled: false
                    })
                }
                if (this.props.behavior) {
                    if (this.props.behavior.visibility_subscribers) {
                        utils.dCookieCreate(
                            "subscribe-" + this.props.id,
                            "true",
                            {
                                range: "month",
                                number: 36
                            }
                        )
                    }
                }

                // Check for redirect
                if (this.state.redirectUrl != "") {
                    var verifyUrl = document.createElement("input")
                    verifyUrl.setAttribute("type", "url")
                    verifyUrl.setAttribute("value", this.state.redirectUrl)

                    if (verifyUrl.checkValidity()) {
                        window.location.href = this.state.redirectUrl
                    }
                }
            } else {
                this.setState({
                    errors: true,
                    submitDisabled: false
                })
            }
        })
    }

    //  ==================
    //  = Optin triggers =
    //  ==================
    isCookied() {
        if (this.props.behavior.range_session != 0) {
            return utils.dOptinVisible(this.props.id, {
                number: this.props.behavior.number_days_session || "",
                range: this.props.behavior.range_session
            })
        } else {
            return false
        }
    }

    exitIntent(e) {
        if (e.clientY < 20) {
            this.setState({ isHidden: false })
            document.scrollingElement.removeEventListener(
                "mouseleave",
                this.exitIntent
            )
        }
    }

    isRedirectUrl() {
        const { redirectUrl } = this.state

        if (redirectUrl == window.location.href) {
            return true
        }

        return false
    }

    checkTrigger() {
        this.setState({ isHidden: true })

        if (this.isRedirectUrl()) {
            return null
        }

        if (this.isCookied()) {
            return null
        }

        if (this.props.behavior.hide_on_mobile) {
            if (window.matchMedia("(max-width: 768px)").matches) {
                return null
            }
        }

        if (this.props.behavior.exit_intent) {
            if (!this.alreadyView) {
                document.scrollingElement.addEventListener(
                    "mouseleave",
                    this.exitIntent,
                    false
                )
            }
        }

        if (this.props.behavior.trigger_after_time_delay) {
            window.setTimeout(() => {
                if (!this.alreadyView) {
                    this.setState({ isHidden: false })
                }
            }, this.props.behavior.after_time_delay * 1000)
        }

        if (this.props.behavior.trigger_after_scrolling) {
            new OptinScrollr(
                this.props.behavior.after_scrolling_percent + "%",
                false,
                scrollState => {
                    if (!this.alreadyView) {
                        this.setState({ isHidden: false })
                    }
                }
            )
        }

        if (
            !this.props.behavior.trigger_after_scrolling &&
            !this.props.behavior.trigger_after_time_delay &&
            !this.props.behavior.exit_intent
        ) {
            this.setState({ isHidden: false })
        }
    }

    getOptin() {
        const { config, id, type, behavior } = this.props

        switch (type) {
            case POPUP_OPTIN:
                return (
                    <PopupOptin
                        config={config}
                        behavior={behavior}
                        id={id}
                        parentState={this.state}
                        handleChange={this.handleChange}
                        handleSubmit={this.handleSubmit}
                    />
                )
                break
            case SHORTCODE_OPTIN:
                return (
                    <ShortcodeOptin
                        config={config}
                        id={id}
                        parentState={this.state}
                        handleChange={this.handleChange}
                        handleSubmit={this.handleSubmit}
                    />
                )
                break
            case FLY_IN_OPTIN:
                return (
                    <FlyInOptin
                        config={config}
                        behavior={behavior}
                        id={id}
                        parentState={this.state}
                        handleChange={this.handleChange}
                        handleSubmit={this.handleSubmit}
                    />
                )
            case WIDGET_OPTIN:
                return (
                    <WidgetOptin
                        config={config}
                        id={id}
                        parentState={this.state}
                        handleChange={this.handleChange}
                        handleSubmit={this.handleSubmit}
                    />
                )
            case AFTER_CONTENT_OPTIN:
                return (
                    <AfterContentOptin
                        config={config}
                        id={id}
                        parentState={this.state}
                        handleChange={this.handleChange}
                        handleSubmit={this.handleSubmit}
                    />
                )
        }
    }

    isNaked() {
        const { config } = this.props

        return config.default_settings
            ? config.default_settings.form_wrapper.attrs.naked
            : false
    }

    getCustomCss() {
        const { config } = this.props
        return config.custom_css
            ? this.props.config.custom_css.replace(/&nbsp;/g, "")
            : false
    }

    componentWillMount() {
        const { type } = this.props

        switch (type) {
            case POPUP_OPTIN:
            case SMARTBAR_OPTIN:
            case FLY_IN_OPTIN:
            case LOCKED_OPTIN:
                this.checkTrigger()
                break
        }
    }

    render() {
        if (this.props.behavior) {
            if (this.props.behavior.visibility_subscribers) {
                if (utils.dCookieRead(`subscribe-${this.props.id}`) == "true") {
                    return false
                }
            }
        }

        if (this.state.isHidden) {
            return false
        }

        switch (this.props.type) {
            case CONTACT_FORM_7:
                return false
        }
        this.alreadyView = true

        return (
            <div id="DELI-BaseOptin">
                {!this.isNaked() && <style>{this.resetCss}</style>}
                {!this.isNaked() && <style>{this.getCustomCss()}</style>}
                {this.getOptin()}
            </div>
        )
    }
}

const elementsOptin = document.querySelectorAll(".delipress-optin")

for (var i = 0; i < elementsOptin.length; i++) {
    var optin = elementsOptin[i]
    const _type = optin.getAttribute("data-type")
    const _config = JSON.parse(optin.getAttribute("data-config"))
    const _idOptin = optin.getAttribute("data-id")
    const _behavior = JSON.parse(optin.getAttribute("data-behavior"))

    render(
        <BaseOptin
            key={"optin" + _idOptin}
            config={_config}
            id={_idOptin}
            type={_type}
            behavior={_behavior}
        />,
        optin
    )
}

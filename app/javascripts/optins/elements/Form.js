import { h, render, Component } from "preact"
import Success from "./Success"
// import * as _ from "underscore"
import Errors from "./Errors"
import Wrapper from "./composition/Wrapper"
import WrapperImage from "./composition/WrapperImage"
import { prepareCssOptin } from "../styles/prepareCssOptin"
import Cross from "./Cross"

import {
    CONTENT_OPTIN,
    LOCKED_OPTIN,
    SCROLL_OPTIN,
    POPUP_OPTIN,
    FLY_IN_OPTIN,
    SHORTCODE_OPTIN,
    SMARTBAR_OPTIN,
    WIDGET_OPTIN
} from "../OptinConstants"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

export default class Form extends Component {
    constructor(props) {
        super(props)

        this.renderCross = this.renderCross.bind(this)
        this.getPositionCross = this.getPositionCross.bind(this)
    }

    componentWillMount() {
        const { config } = this.props

        this.setState({
            emailField: config.emailField
        })
    }

    componentWillReceiveProps() {
        const { config } = this.props

        this.setState({
            emailField: config.emailField
        })
    }

    getMetaConfig(key, meta, params) {
        const defaultConfig = this.props.config.default_settings
        const config = this.props.config[params["config"] + "_settings"]
        try {
            switch (meta) {
                case "styling":
                    return typeof config[key] === "undefined"
                        ? prepareCssOptin(defaultConfig[key].styling)
                        : prepareCssOptin(config[key].styling)
                    break
                case "attrs":
                    return typeof config[key] === "undefined"
                        ? defaultConfig[key].attrs[params["attrs"]]
                        : config[key].attrs[params["attrs"]]
                    break
            }
        } catch (error) {
            return null
        }
    }

    isNaked() {
        const { config } = this.props

        return config.default_settings.form_wrapper.attrs.naked
    }

    getStyleConfig(key, params) {
        if (this.isNaked()) {
            return {}
        }

        return this.getMetaConfig(key, "styling", {
            config: this.getStateConfig()
        })
    }

    getPositionCross() {
        const wrapperConfig = this.getStyleConfig("wrapper")
        return wrapperConfig.borderRadius
    }

    getStateConfig() {
        const { parentState } = this.props

        let _config = "default"
        if (parentState.success) {
            _config = "success"
        } else if (parentState.errors) {
            _config = "error"
        }

        return _config
    }

    getAttrsConfig(key, attrs) {
        return this.getMetaConfig(
            key,
            "attrs",
            Object.assign(
                {},
                { attrs: attrs },
                { config: this.getStateConfig() }
            )
        )
    }

    renderCross() {
        const { config, type } = this.props
        const orientationFlyIn =
            config.default_settings.wrapper.attrs.orientation_fly_in

        if (type == POPUP_OPTIN || type == FLY_IN_OPTIN) {
            return (
                <Cross
                    action={this.props.handleDismiss}
                    position={this.getPositionCross()}
                    orientation={orientationFlyIn}
                />
            )
        }
    }

    render() {
        const {
            parentState,
            handleChange,
            handleSubmit,
            type,
            config
        } = this.props

        const metas = this.getAttrsConfig("form_wrapper", "metas")

        let formClass = "DELI-formBloc "

        if (
            config.default_settings.form_wrapper.attrs.form_size !== "default"
        ) {
            formClass += `DELI-formBloc--${
                config.default_settings.form_wrapper.attrs.form_size
            }`
        }

        const fieldsEnable = config.default_settings.form_wrapper.attrs.fields_enable === undefined ? true : config.default_settings.form_wrapper.attrs.fields_enable

        const rgpdActive = config.default_settings.rgpd === undefined ? true : config.default_settings.rgpd.attrs.active_rgpd;

        return <Wrapper style={this.getStyleConfig("wrapper")} config={config} defaultConfig={config.default_settings}>
                {this.renderCross()}
                <WrapperImage defaultConfig={config.default_settings}>
                    <img style={this.getStyleConfig("wrapper_image")} className="DELI-image" src={this.getAttrsConfig("wrapper_image", "url")} />
                </WrapperImage>
                {!parentState.success && !parentState.errors && <div className="DELI-textBloc">
                            <div style={this.getStyleConfig("title")} className="DELI-title" dangerouslySetInnerHTML={{ __html: this.getAttrsConfig("title", "content") }} />
                            <div style={this.getStyleConfig("message")} className="DELI-message" dangerouslySetInnerHTML={{ __html: this.getAttrsConfig("message", "content") }} />
                        </div>}

                {parentState.success && <Success text={this.getAttrsConfig("message", "content")} />}

                {((!parentState.success && fieldsEnable) || (parentState.success && !this.getAttrsConfig("email_input_form", "disable_email_input_form"))) && <form style={this.getStyleConfig("form_wrapper")} className={formClass} onSubmit={handleSubmit}>
                        {metas === "single_field" && <input onKeyUp={e => {
                                    this.setState({
                                        firstName: e.target.value.trim()
                                    });
                                    this.props.handleChange(e, "firstName");
                                }} placeholder={config.default_settings.form_wrapper.attrs.name_placeholder} className="DELI-inputField" type="text" />}
                        {metas === "first_last_name" && <input onKeyUp={e => {
                                    this.setState({
                                        firstName: e.target.value.trim()
                                    });
                                    this.props.handleChange(e, "firstName");
                                }} style={this.getStyleConfig("fields")} className="DELI-inputField" placeholder={config.default_settings.form_wrapper.attrs.firstname_placeholder} type="text" />}
                        {metas === "first_last_name" && <input onKeyUp={e => {
                                    this.setState({
                                        lastName: e.target.value.trim()
                                    });
                                    this.props.handleChange(e, "lastName");
                                }} style={this.getStyleConfig("fields")} placeholder={config.default_settings.form_wrapper.attrs.lastname_placeholder} className="DELI-inputField" type="text" />}

                        <input onKeyUp={e => {
                                this.setState({
                                    emailField: e.target.value.trim()
                                });
                                this.props.handleChange(e, "emailField");
                            }} style={this.getStyleConfig("fields")} className="DELI-inputField" type="email" value={this.state.emailField} required placeholder={config.default_settings.form_wrapper.attrs.email_placeholder} />
                        {rgpdActive && !parentState.success && <div className="DELI-rgpd">
                            <div style={{ fontSize: "13px", padding: '0 30px', margin:'12px 0 0 0', opacity: '.8' }} dangerouslySetInnerHTML={{ __html: this.getAttrsConfig("information_rgpd", "content") }} />
                            <a href={DelipressGRPD.privacy_page_url} style={{ fontSize: "13px", display: "inline-block", margin: "10px 0 16px", color: 'inherit', textDecoration: "underline", opacity: '.8' }} target='_blank'>
                                {translationDelipressReact.text_link_rgpd}
                            </a>
                        </div>}
                        <button style={this.getStyleConfig("button")} className="DELI-button" disabled={parentState.submitDisabled} type="submit">
                            {this.getAttrsConfig("button", "content")}
                        </button>
                    </form>}
                {!fieldsEnable && !parentState.success && <div className="DELI-redirect">
                            <a href={config.default_settings.form_wrapper.attrs.redirect_url} style={this.getStyleConfig("button")} className="DELI-button">
                                {this.getAttrsConfig("button", "content")}
                            </a>
                        </div>}
            </Wrapper>;
    }
}

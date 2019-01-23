import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import { connect } from "react-redux"
import { bindActionCreators } from "redux"
import classNames from "classnames"

import EndpointTemplateActions from "javascripts/react/services/actions/EndpointTemplateActions"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"

class SettingsTemplate extends Component {
    constructor(props) {
        super(props)

        this.onChangeSwitch = this.onChangeSwitch.bind(this)
        this.submitTemplate = this.submitTemplate.bind(this)
    }

    componentWillMount() {
        this.setState({
            templateSaveState: "new",
            name_template: "",
            template_choice: "",
            toast_message : "",
        })
        
        this.props.actionsEndpointTemplate.getTemplates()
    }

    onChangeSwitch(event) {
        this.setState({ templateSaveState: event.target.value })
    }

    submitTemplate(e) {
        e.preventDefault()
        
        switch (this.state.templateSaveState) {
            case "new":
                this.props.actionsEndpointTemplate.saveTemplate({
                    name: this.state.name_template,
                    config: this.props.config
                }, () => {
                    this.setState({ "toast_message": translationDelipressReact.Builder.template_settings.template_saved_success})
                    setTimeout(() => {
                        this.setState({"toast_message": ""})
                    }, 3000);
                    this.props.actionsEndpointTemplate.getTemplates()
                })
                break
            default:
                this.props.actionsEndpointTemplate.updateTemplate(
                    this.state.template_choice,
                    {
                        config: this.props.config
                    }, () => {

                        this.setState({ "toast_message": translationDelipressReact.Builder.template_settings.template_updated_success })
                        setTimeout(() => {
                            this.setState({ "toast_message": "" })
                        }, 3000);
                    }
                )
                break
        }
    }

    formSaveTemplate() {
        const _classNew = classNames({
            "delipress__is-active": this.state.templateSaveState == "new",
            delipress__builder__side__form__item: true
        })

        const _classUpdate = classNames({
            "delipress__is-active": this.state.templateSaveState == "update",
            delipress__builder__side__form__item: true
        })

        const _classToast = classNames({
            "delipress__message-success": !_.isEmpty(this.state.toast_message),
        }, "delipress__message")

        const { templateSaveState } = this.state

        return (
            <div className="delipress__builder__side__form">
                <div className={_classNew}>
                    <label className="delipress__builder__side__setting__label">
                        {
                            translationDelipressReact.Builder.template_settings
                                .template_name
                        }
                    </label>
                    <input
                        className="delipress__input"
                        placeholder={
                            translationDelipressReact.Builder.template_settings
                                .template_name_placeholder
                        }
                        name="new-template"
                        type="text"
                        onChange={e => {
                            this.setState({ name_template: e.target.value })
                        }}
                        value={this.name_template}
                    />
                </div>

                <div className={_classUpdate}>
                    <label className="delipress__builder__side__setting__label">
                        {
                            translationDelipressReact.Builder.template_settings
                                .template_choose
                        }
                    </label>
                    <select
                        style={{ minWidth: "100%" }}
                        onChange={e =>
                            this.setState({ template_choice: e.target.value })
                        }
                    >
                        {this.props.templates.map((tpl, key) => {
                            return (
                                <option key={key} value={tpl.ID}>
                                    {tpl.post_title}
                                </option>
                            )
                        })}
                    </select>
                </div>

                <div className="delipress__align-right">
                    <input
                        type="submit"
                        className="delipress__button delipress__button--small delipress__button--save"
                        value={
                            templateSaveState == "new"
                                ? translationDelipressReact.Builder
                                      .template_settings.template_save_btn
                                : translationDelipressReact.Builder
                                      .template_settings.template_update_btn
                        }
                        onClick={this.submitTemplate}
                    />
                </div>
                
                {
                    !_.isEmpty(this.state.toast_message) && 
                    <div className={_classToast}>
                        <p>{this.state.toast_message}</p>
                    </div>
                }
{/* 
                <div className="delipress__message-error">
                    <p>{translationDelipressReact.Builder.template_settings.template_saved_error}</p>
                </div> */}

            </div>
        )
    }

    render() {
        let switchEls = []
        const switchLoop = [
            {
                name: "new",
                text:
                    translationDelipressReact.Builder.template_settings
                        .template_new,
                style : {
                    opacity : 1
                },
                hasInput : true
            },
            {
                name: "update",
                text:
                    translationDelipressReact.Builder.template_settings
                        .template_update,
                style: {
                    opacity: (_.isEmpty(this.props.templates)) ? 0.5 : 1
                },
                hasInput: !_.isEmpty(this.props.templates)
            }
        ]

        _.each(switchLoop, (el, i) => {
            switchEls.push(
                <div className="delipress__buttonsgroup__cell" key={i}>
                    {
                        el.hasInput &&
                        <input
                            type="radio"
                            name="save_template"
                            id={"save_template_" + el.name}
                            name="align"
                            value={el.name}
                            checked={this.state.templateSaveState === el.name}
                            onChange={this.onChangeSwitch}
                        />
                    }
                    <label
                        htmlFor={"save_template_" + el.name}
                        className="delipress__buttonsgroup__cell"
                        style={el.style}
                    >
                        {el.text}
                    </label>
                </div>
            )
        })


        if (!DELIPRESS_LICENSE_STATUS) {
            return (
                <div className="container__settings-template">
                    <div className="container__settings__attributes settings__default">
                        <span className="delipress__builder__side__title">
                            {
                                translationDelipressReact.Builder.template_settings
                                    .template_save_title
                            }
                            <span
                                onClick={this.showPremiumWoocommerceModal}
                                className="delipress__builder__premium"
                            >
                                <i className="dashicons dashicons-awards" />
                                <span>
                                    {translationDelipressReact.premium_only}
                                </span>
                            </span>
                        </span>
                    </div>
                    <div className="delipress__builder__side__content">
                        <div className="delipress__builder__side__components">
                            <div className="delipress__builder__premium-incentive">
                                <span className="delipress__builder__premium-badge">
                                    <i className="dashicons dashicons-awards" />
                                    <span>
                                        {
                                            translationDelipressReact.premium_only
                                        }
                                    </span>
                                </span>
                                <p>
                                    {
                                        translationDelipressReact.premium_woocommerce
                                    }
                                </p>
                                <a
                                    href={DELIPRESS_PREMIUM_URL}
                                    className="delipress__button delipress__button--main delipress__button--small"
                                    target="_blank"
                                >
                                    {translationDelipressReact.view_pricing}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            )
        }

        return (
            <div className="container__settings-template">
                <div className="container__settings__attributes settings__default">
                    <span className="delipress__builder__side__title">
                        {
                            translationDelipressReact.Builder.template_settings
                                .template_save_title
                        }
                    </span>
                    <SettingsItem
                        full
                        label={
                            translationDelipressReact.Builder.template_settings
                                .template_save_text
                        }
                    >
                        <div className="delipress__buttonsgroup">
                            {switchEls}
                        </div>
                    </SettingsItem>

                    {this.formSaveTemplate()}

                    {/* <span className="delipress__builder__side__title">
                        {
                            translationDelipressReact.Builder.template_settings
                                .template_library_title
                        }
                    </span>
                    <div className="delipress__builder__side__setting" /> */}
                </div>
            </div>
        )
    }
}

function mapStateToProps(state) {
    return {
        templates: state.EndpointTemplateReducer.templates,
        config: state.TemplateReducer.config
    }
}

function mapDispatchToProps(dispatch, context) {
    const actionsEndpointTemplate = new EndpointTemplateActions()

    return {
        actionsEndpointTemplate: bindActionCreators(
            actionsEndpointTemplate,
            dispatch
        )
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(SettingsTemplate)

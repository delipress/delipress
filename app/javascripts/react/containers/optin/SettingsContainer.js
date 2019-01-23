import * as _ from "underscore"
import React, { Component } from "react"
import { connect } from "react-redux"
import { bindActionCreators } from "redux"
import classnames from "classnames"

import OptinActions from "javascripts/react/services/actions/OptinActions"
import OptinSettingsFactory from "javascripts/react/services/OptinSettingsFactory"

class SettingsContainer extends Component {
    constructor(props) {
        super(props)

        this.changeTypeSettings = this.changeTypeSettings.bind(this)
    }

    componentDidMount() {
        this.setState({
            typeSettings: "design"
        })
    }

    changeTypeSettings(value) {
        this.setState({
            typeSettings: value
        })
    }

    renderTabs() {
        //let tabs = ["design", "models", "css"]
        let tabs = ["design", "css"]

        tabs = _.map(tabs, (v, k) => {
            const classHeader = classnames(
                {
                    delipress__isactive: v === this.state.typeSettings
                },
                "delipress__tabs__item"
            )

            let txt = ""
            switch (k) {
                case 0:
                    txt = translationDelipressReact.Optin.Settings.tab_first
                    break
                case 1:
                    txt = translationDelipressReact.Optin.Settings.tab_third
                    break
                // case 2:
                //     txt = translationDelipressReact.Optin.Settings.tab_third
                //     break
            }

            return (
                <div
                    key={`tab_${k}`}
                    onClick={() => {
                        this.changeTypeSettings(v)
                    }}
                    className={classHeader}
                >
                    {txt}
                </div>
            )
        })

        return tabs
    }

    render() {
        const { type, config, settings } = this.props

        if (_.isNull(config) || _.isNull(type)) {
            return false
        }

        return (
            <div className="delipress__builder__side">
                <div
                    id="tabs__settings "
                    className="delipress__tabs delipress__tabs--small elements-2"
                >
                    {this.renderTabs()}
                </div>
                <div className="delipress__builder__side__panel">
                    {this.state.typeSettings === "design" &&
                        OptinSettingsFactory.getSettingsComponent(type, {
                            updateOptin: this.props.updateOptin,
                            config: config,
                            settings: settings,
                            type: type
                        })}
                    {this.state.typeSettings === "css" &&
                        OptinSettingsFactory.getSettingsCustomCss({
                            updateOptin: this.props.updateOptin,
                            config: config,
                            settings: settings,
                            type: type
                        })}
                    {this.state.typeSettings === "models" &&
                        OptinSettingsFactory.getSettingsModels({
                            updateOptin: this.props.updateOptin,
                            config: config,
                            settings: settings,
                            type: type
                        })}
                </div>
            </div>
        )
    }
}

function mapStateToProps(state) {
    return {
        type: state.OptinReducer.type,
        config: state.OptinReducer.config,
        settings: state.OptinReducer.settings
    }
}

export default connect(mapStateToProps)(SettingsContainer)

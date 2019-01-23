import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import { connect } from "react-redux"
import { compose, bindActionCreators } from "redux"

import TemplateActions from "javascripts/react/services/actions/TemplateActions"
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

class EmailOnlineActive extends Component {

    constructor(props){
        super(props)

        this._toggleEmailOnlineActive = this._toggleEmailOnlineActive.bind(this)
    }

    componentWillMount() {
        const { emailOnlineActive } = this.props

        this.setState({ email_online_active: emailOnlineActive })
    }

    _toggleEmailOnlineActive(event) {
        
        this.setState({
            email_online_active: event.target.checked
        })

        this.props.updateEmailOnlineActive(event.target.checked)
        return false
    }

    render() {
        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .email_online_settings.title_default
                    }
                </span>
                <SettingsItem label={translationDelipressReact.activate}>
                    <input
                        type="checkbox"
                        id="email_online_active_settings"
                        className="delipress__switch__input"
                        checked={this.state.email_online_active}
                        onChange={this._toggleEmailOnlineActive}
                    />
                    <label
                        htmlFor="email_online_active_settings"
                        className="delipress__switch"
                    >
                        <div className="delipress__switch__slider" />
                        <div className="delipress__switch__on">I</div>
                        <div className="delipress__switch__off">0</div>
                    </label>
                </SettingsItem>
            </div>
        )
    }
}

function mapStateToProps(state) {
    const emailOnlineActive = state.TemplateReducer.config.email_online_active

    return {
        emailOnlineActive: emailOnlineActive
    }
}

export default connect(mapStateToProps)(EmailOnlineActive)

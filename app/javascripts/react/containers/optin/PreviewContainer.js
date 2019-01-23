import React, { Component } from "react"
import { connect } from "react-redux"
import { bindActionCreators } from "redux"
import classNames from "classnames"

import {
    POPUP_OPTIN,
    SHORTCODE_OPTIN,
    FLY_IN_OPTIN,
    WIDGET_OPTIN,
    AFTER_CONTENT
} from 'javascripts/react/constants/OptinConstants'

import BaseOptin from "javascripts/react/components/optins/BaseOptin"
import FakeSite from "./FakeSite"
import FakePopup from "./FakePopup"
import FakeFlyIn from "./FakeFlyIn"
import OptinStates from "./OptinStates"

class PreviewContainer extends Component {
    render() {
        const { config, settings } = this.props

        if (_.isNull(config)) {
            return false
        }

        return (
            <div className="delipress__builder__main">
                <div className="delipress__builder__main__preview delipress__builder__main__preview-optin">
                    <div className="delipress__builder__main__preview__scroll delipress__fake">
                        <OptinStates
                            settings={settings}
                            actionsOptin={this.props.actionsOptin}
                        />

                        {(config.type == SHORTCODE_OPTIN || config.type == WIDGET_OPTIN || config.type == AFTER_CONTENT) &&
                            <FakeSite>
                                <BaseOptin
                                    config={config}
                                    settings={settings}
                                    updateOptin={this.props.updateOptin}
                                />
                            </FakeSite>}
                        {(config.type == POPUP_OPTIN) &&
                            <FakePopup>
                                <BaseOptin
                                    config={config}
                                    settings={settings}
                                    updateOptin={this.props.updateOptin}
                                />
                        
                            </FakePopup>}
                        {(config.type == FLY_IN_OPTIN) &&
                            <FakeFlyIn>
                                <BaseOptin
                                    config={config}
                                    settings={settings}
                                    updateOptin={this.props.updateOptin}
                                />
                            </FakeFlyIn>}
                    </div>
                </div>
            </div>
        )
    }
}

function mapStateToProps(state) {
    if (_.isNull(state.OptinReducer.config)) {
        return {
            config: null,
            settings: state.OptinReducer.settings,
            id: DELIPRESS_OPTIN_ID
        }
    }

    const _config = _.extend(state.OptinReducer.config, {
        type: state.OptinReducer.type,
        id: DELIPRESS_OPTIN_ID
    })

    return {
        naked: _config.default_settings.form_wrapper.attrs.naked || false,
        config: _config,
        settings: state.OptinReducer.settings
    }
}

export default connect(mapStateToProps)(PreviewContainer)

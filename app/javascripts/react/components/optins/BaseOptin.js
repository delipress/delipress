import React, { Component } from "react"
import resetcss from "./helpers/reset"
import utils from "./helpers/utils"

import ShortcodeOptin from './ShortcodeOptin'
import PopupOptin from './PopupOptin'
import FlyInOptin from './FlyInOptin'
import WidgetOptin from './WidgetOptin'
import AfterContentOptin from './AfterContentOptin'


import {
    SHORTCODE_OPTIN,
    POPUP_OPTIN,
    FLY_IN_OPTIN,
    WIDGET_OPTIN,
    AFTER_CONTENT
} from 'javascripts/react/constants/OptinConstants'

class BaseOptin extends Component {
    constructor(props) {
        super(props)

        this.resetCss = resetcss().replace(/\B\s+/g, "")
        this.getOptin = this.getOptin.bind(this)
    }


    getOptin(params){
        const {
            config,
            settings
        } = this.props

        switch(config.type){

            case SHORTCODE_OPTIN:
                return (
                    <ShortcodeOptin
                        config={config}
                        settings={settings}
                        params={params}
                    />
                )
            case POPUP_OPTIN:
                return (
                    <PopupOptin
                        config={config}
                        settings={settings}
                        params={params}
                    />
                )
            case FLY_IN_OPTIN:
                return (
                    <FlyInOptin
                        config={config}
                        settings={settings}
                        params={params}
                    />
                )
            case WIDGET_OPTIN:
                return (
                    <WidgetOptin
                        config={config}
                        settings={settings}
                        params={params}
                    />
                )
            case AFTER_CONTENT:
                return (
                    <AfterContentOptin
                        config={config}
                        settings={settings}
                        params={params}
                    />
                )

        }
    }

    render() {
        if (_.isNull(this.props.config)) {
            return false
        }

        const params = {
            updateOptin: this.props.updateOptin
        }

        return (
            <div id={`DELI-${this.props.config.type}-${this.props.config.id}`}>
                <div id="DELI-BaseOptin">
                    <style>
                        {this.resetCss}
                    </style>
                    <style>
                        {this.props.config.custom_css}
                    </style>
                    {this.getOptin(params)}
                </div>
            </div>
        )
    }
}

export default BaseOptin

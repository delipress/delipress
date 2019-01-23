import React, { Component, cloneElement } from 'react'
import PropTypes from 'prop-types'
import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'

import ColorSelector from 'javascripts/react/components/ColorSelector'
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'


class BaseGeneral extends Component  {

    _handleChangeColor(key, color){
        const {
            updateTheme
        } = this.props

        let {
            mjAll,
            mjText,
            mjContainer,
            linkColor
        } = this.props

        switch(key){
            case "background-color":
                mjContainer["background-color"] = color
                break;
            case "text-color":
                mjText["color"] = color
                break;
            case "link-color":
                linkColor = color
                break;
        }

        this.setState({
            "mjAll" : mjAll,
            "mjContainer" : mjContainer,
            "mjText" : mjText,
            "linkColor" : linkColor
        })

        updateTheme({
            "mj-attributes": {
                "mj-all" : mjAll,
                "mj-text" : mjText,
                "mj-container": mjContainer
            },
            "mj-styles" :{
                "link-color" : linkColor
            }
        })
    }

    _renderThemeDefault() {
        const {
            mjAll,
            mjContainer,
            mjText,
            linkColor
        } = this.props

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                   {translationDelipressReact.Builder.component_settings.base_general.title_background_campaign}
                </span>
                <SettingsItem label={translationDelipressReact.Builder.component_settings.base_general.background_color}>
                    <ColorSelector
                        picker="sketch"
                        handleChangeComplete={this._handleChangeColor.bind(this, "background-color")}
                        disabledAlpha={false}
                        idSelector=".delipress__builder__main"
                        typeColor="backgroundColor"
                        color={(_.isUndefined(mjContainer) ) ? {
                            rgb : {
                                r : 255,
                                g : 255,
                                b : 255,
                                a : 1
                            }
                        } : mjContainer["background-color"]}
                    />
                </SettingsItem>
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.base_general.title_text_component}
                </span>
                <SettingsItem label={translationDelipressReact.Builder.component_settings.base_general.text_color}>
                    <ColorSelector
                        picker="sketch"
                        handleChangeComplete={this._handleChangeColor.bind(this, "text-color")}
                        disabledAlpha={false}
                        color={(_.isUndefined(mjText) ) ? {
                            rgb : {
                                r : 0,
                                g : 0,
                                b : 0,
                                a : 1
                            }
                        } : mjText["color"]}
                    />
                </SettingsItem>
                <SettingsItem label={translationDelipressReact.Builder.component_settings.base_general.link_color}>
                    <ColorSelector
                        picker="sketch"
                        disabledAlpha={false}
                        handleChangeComplete={this._handleChangeColor.bind(this, "link-color")}
                        color={(_.isUndefined(linkColor) ) ? {
                           rgb: {
                                r : 245,
                                g : 101,
                                b : 106,
                                a : 1
                           }
                        } : linkColor}
                    />
                </SettingsItem>
            </div>
        )
    }
}

BaseGeneral.propTypes = {
    updateTheme: PropTypes.func.isRequired
}


export default BaseGeneral

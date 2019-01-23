import React, { Component } from 'react'

import ShortcodeSettings from 'javascripts/react/components/settings/optin/shortcode/ShortcodeSettings'
import PopupSettings from 'javascripts/react/components/settings/optin/popup/PopupSettings'
import FlyInSettings from 'javascripts/react/components/settings/optin/flyin/FlyInSettings'
import WidgetSettings from 'javascripts/react/components/settings/optin/widget/WidgetSettings'
import AfterContentSettings from 'javascripts/react/components/settings/optin/aftercontent/AfterContentSettings'

import CustomCssSettings from 'javascripts/react/components/settings/optin/customcss/CustomCssSettings'
import ModelsSettings from 'javascripts/react/components/settings/optin/models/ModelsSettings'

import {
    CONTENT_OPTIN,
    LOCKED_OPTIN,
    SCROLL_OPTIN,
    POPUP_OPTIN,
    SHORTCODE_OPTIN,
    SMARTBAR_OPTIN,
    WIDGET_OPTIN,
    FLY_IN_OPTIN,
    AFTER_CONTENT
} from 'javascripts/react/constants/OptinConstants'


export default class OptinSettingsFactory{
    
    static getSettingsModels(params = {}){
        return (
            <ModelsSettings {...params} />
        )
    }

    static getSettingsCustomCss(params = {}){
        return (
            <CustomCssSettings {...params} />
        )
    }

    static getSettingsComponent(type, params = {}) {

        switch(type){
        
            case SHORTCODE_OPTIN:
                return (
                    <ShortcodeSettings {...params} />
                )
            case POPUP_OPTIN:
                return (
                    <PopupSettings {...params} />
                )
            case FLY_IN_OPTIN:
                return (
                    <FlyInSettings {...params} />
                )
            case WIDGET_OPTIN:
                return (
                    <WidgetSettings {...params} />
                )
            case AFTER_CONTENT:
                return (
                    <AfterContentSettings {...params} />
                )
        }
    }
}

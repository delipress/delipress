import React, { Component } from 'react'

import {
    SETTINGS_EDITOR,
    SETTINGS_STYLE,
    SETTINGS_CONTENT ,
    SETTINGS_LIST_CONTENTS,
    SETTINGS_GENERAL,
    SETTINGS_TEMPLATE,
    SETTINGS_SECTION
} from 'javascripts/react/constants/EditorConstants'

import ListContentsComponent from 'javascripts/react/components/settings/ListContentsComponent'
import EditorComponent from 'javascripts/react/components/settings/EditorComponent'
import StyleComponent from 'javascripts/react/components/settings/StyleComponent'
import SettingsGeneral from 'javascripts/react/components/settings/SettingsGeneral'
import SettingsTemplate from 'javascripts/react/components/settings/SettingsTemplate'

export default class SettingsFactory{

    static getSettingsComponent(component, item = null, params = []) {

        switch(component){
            case SETTINGS_EDITOR:
                return (
                    <EditorComponent item={item} {...params} />
                )
            case SETTINGS_LIST_CONTENTS:
                return (
                    <ListContentsComponent />
                )
            case SETTINGS_GENERAL:
                return (
                    <SettingsGeneral {...params}  />
                )
            case SETTINGS_TEMPLATE:
                return (
                    <SettingsTemplate {...params}  />
                )
            case SETTINGS_STYLE:
                return (
                    <StyleComponent item={item} {...params}/>
                )
            default:
                return false

            
        }
    }
}

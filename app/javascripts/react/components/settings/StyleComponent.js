import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'
import { BlockPicker } from 'react-color';
import reactCSS from 'reactcss'

import {
    SETTINGS_LIST_CONTENTS
} from '../../constants/EditorConstants'

import {
    TEXT,
    DIVIDER,
    IMAGE,
    BUTTON,
    SOCIAL_BUTTON,
    SPACER,
    SECTION,
    SECTION_EMAIL_ONLINE,
    SECTION_UNSUBSCRIBE,
    WP_CUSTOM_POST,
    WP_ARTICLE,
    TITLE,
    EMAIL_ONLINE,
    UNSUBSCRIBE
}   from 'javascripts/react/constants/TemplateContentConstants'

import EditorActions from 'javascripts/react/services/actions/EditorActions'
import AttributesDefault from './style/AttributesDefault'
import SectionSettings from 'javascripts/react/components/settings/SectionSettings'

class StyleComponent extends Component  {

    constructor(props){
        super(props)

        this.saveEditor        = this.saveEditor.bind(this)
        this.saveEditorColumn  = this.saveEditorColumn.bind(this)
        this.saveEditorColumns = this.saveEditorColumns.bind(this)
    }

    saveEditor(newStyles){
        let {
            actionsEditor,
            item,
            changeItemSuccess
        } = this.props


        item = _.extend(item, {
            "styles" : newStyles
        })
        
        let deferred = null
        switch(item.type){
            case SECTION:
                deferred = actionsEditor.changeStyleSection(item)
                break;
            case SECTION_EMAIL_ONLINE:
            case SECTION_UNSUBSCRIBE:
                deferred = actionsEditor.changeStyleSectionFix(item)
                break;
            case EMAIL_ONLINE:
            case UNSUBSCRIBE:
                deferred = actionsEditor.changeStyleComponentFix(item)
                break
            default:
                deferred = actionsEditor.changeItem(item)
                break;
        }

        if(!_.isUndefined(changeItemSuccess)){
            deferred.then(changeItemSuccess)
        }


    }

    saveEditorColumn(payload){
        const {
            actionsEditor,
            changeItemSuccess
        } = this.props

        const deferred = actionsEditor.changeStyleColumn(payload)

        if(!_.isUndefined(changeItemSuccess)){
            deferred.then(changeItemSuccess)
        }
    }

    saveEditorColumns(payload){
        const {
            actionsEditor,
            changeItemSuccess
        } = this.props

        const deferred = actionsEditor.changeStyleColumns(payload)

        if(!_.isUndefined(changeItemSuccess)){
            deferred.then(changeItemSuccess)
        }
    }

    render() {
        const { item }    = this.props
        let _styleCustom  = false

        switch(item.type){
            case TEXT:
            case DIVIDER:
            case SOCIAL_BUTTON:
            case BUTTON:
            case IMAGE:
            case SECTION_EMAIL_ONLINE:
            case SECTION_UNSUBSCRIBE:
            case TITLE:
            case EMAIL_ONLINE:
            case UNSUBSCRIBE:
                _styleCustom = (
                    <AttributesDefault
                        item={item}
                        saveEditor={this.saveEditor}
                    />
                )
                break;
            case SECTION:
                _styleCustom = (
                    <SectionSettings
                        saveEditor={this.saveEditor}
                        saveEditorColumn={this.saveEditorColumn}
                        saveEditorColumns={this.saveEditorColumns}
                    />
                )
                break;
        }

        return (
            <div className="delipress__builder__side__panel__tabcontent">
                {_styleCustom}
            </div>
        )
    }

}

function mapDispatchToProps(dispatch, context){
    let actionsEditor = new EditorActions()
    return {
        "actionsEditor" : bindActionCreators(actionsEditor, dispatch)
    }
}


export default connect(null, mapDispatchToProps)(StyleComponent)

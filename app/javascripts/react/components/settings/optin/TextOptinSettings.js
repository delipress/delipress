import * as _ from "underscore"
import React, { Component } from 'react'
import { connect } from 'react-redux'
import classNames from 'classnames'
import { compose, bindActionCreators } from 'redux'

import TinyMCE from 'javascripts/backend/react-tinymce/index';
import BaseSettingsOptin from 'javascripts/react/components/settings/base/BaseSettingsOptin'
import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'

import {
    CHANGE_SETTINGS_OPTIN
} from 'javascripts/react/constants/OptinConstants'


class TextOptinSettings extends BaseSettingsOptin {

    constructor(props){
        super(props)

        this._handleOnChangeContent = this._handleOnChangeContent.bind(this)
    }

    shouldComponentUpdate(nextProps){
        if(
            _.find([
                CHANGE_SETTINGS_OPTIN
            ], (value) => { return value == nextProps.fromAction } )
        ){
            return true
        }

        return false
    }

    _handleOnChangeContent(event){
        const { 
            updateOptin,
            name
        }  = this.props

        clearTimeout(this.saveChangeContent)

        this.saveChangeContent = setTimeout(() => {

            this._saveValues({
                [name]: {
                    attrs: {
                        content: event.target.getContent()
                    }
                }
            })


        }, 1500)
    }

    render(){
        const {
            name,
            config
        } = this.props


        return (
            <TinyMCE
                content={this.props.config[this.props.settings + "_settings"][name].attrs.content}
                config={{
                    inline: true,
                    menubar:false,
                    paste_as_text: true,
                    plugins: 'paste colorpicker textcolor',
                    relative_urls: false,
                    convert_urls: false,
                    language_url: configDelipressReact.tinymce_lang_url,
                    fixed_toolbar_container: "#react-toolbar-tinymce",
                    protect: [
                        /\<\/?(if|endif)\>/g,  // Protect <if> & </endif>
                        /\<xsl\:[^>]+\>/g,  // Protect <xsl:...>
                        /<\?php.*?\?>/g  // Protect php code
                    ],
                    toolbar: []
                }}
                onChange={this._handleOnChangeContent}
            />
        )
    }
}

function mapStateToProps(state){
    return {
        fromAction : state.OptinReducer.fromAction
    }
}

export default connect(mapStateToProps)(TextOptinSettings)

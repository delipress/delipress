import * as _ from "underscore"
import React, { Component } from 'react'
import { connect } from 'react-redux'
import classNames from 'classnames'
import { compose, bindActionCreators } from 'redux'

import BaseContentComponent from './BaseContentComponent'
import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'

import {
    TEXT,
    CHANGE_POSITION_CONTENT,
    CHANGE_POSITION_SECTION,
    ADD_TEMPLATE_CONTENT,
    ADD_TEMPLATE_CONTENT_EMPTY
} from 'javascripts/react/constants/TemplateContentConstants'

import {
    ACTIVE_ITEM
} from 'javascripts/react/constants/EditorConstants'


class BaseTextContentComponent extends BaseContentComponent {

    constructor(props){
        super(props)

        this._handleOnKeyUpContent = this._handleOnKeyUpContent.bind(this)
    }

    componentWillMount() {
        const { 
            item
        }  = this.props

        this.oldStyles  = _.clone(item.styles)
    }


    shouldComponentUpdate(nextProps, nextState){

        if(
            _.find([
                CHANGE_POSITION_CONTENT,
                CHANGE_POSITION_SECTION,
                ADD_TEMPLATE_CONTENT,
                ADD_TEMPLATE_CONTENT_EMPTY,
                ACTIVE_ITEM
            ], (value) => { return value == nextProps.item.fromAction } )
        ){
            return true
        }

        if(
            shallowEqual(nextProps.item.styles, this.oldStyles)
        ){
            return false
        }

        return true
    }


    componentWillUpdate(nextProps, nextState){
        this.oldStyles = _.clone(nextProps.item.styles)
    }

    _handleOnKeyUpContent(newValue){
         const { 
            item,
            changeItemSuccess,
            actionsEditor
        }  = this.props

        this.saveChangeContent = setTimeout(() => {

            item.value =  newValue

            if(!_.isUndefined(changeItemSuccess)){
                const deferred = actionsEditor.changeItemText(item)
                deferred.then(changeItemSuccess)
            }
        }, 350)
    }

    writeCSS(){
        const {
            item
        } = this.props

        let itemStyles = {}
        if(!_.isUndefined(item.styles.presetChoice)){
            itemStyles = _.clone(
                _.find(item.styles.presets, {"type" : item.styles.presetChoice})
            )
        }
        else{
            itemStyles = _.clone(item.styles)
        }

        const idStyle  = `delipress-component-${item.keyRow}${item.keyColumn}${item["_id"]}`

        let css = `\n#delipress-react-selector #${idStyle} .mce-content-body * {
            line-height:${itemStyles["line-height"]};
            font-size:${itemStyles["font-size"]}px;
            font-family:${itemStyles["font-family"]} , Helvetica, Arial, sans-serif;
        }`

        css += `\n#delipress-react-selector #${idStyle} {
            text-align: ${itemStyles.align};
        }

        \n#delipress-react-selector #${idStyle} .mce-content-body > *{
            margin-top: 1em;
        }
        #delipress-react-selector #${idStyle} .mce-content-body > *:first-child{
            margin-top: 0;
        } #delipress-react-selector #${idStyle} .mce-content-body > *:last-child{
            margin-bottom: 0;
        }`


        css += `\n#delipress-react-selector #${idStyle} .mce-content-body p,
            #delipress-react-selector #${idStyle} .mce-content-body ul,
            #delipress-react-selector #${idStyle} .mce-content-body li,
            #delipress-react-selector #${idStyle} .mce-content-body h1,
            #delipress-react-selector #${idStyle} .mce-content-body h2,
            #delipress-react-selector #${idStyle} .mce-content-body h3,
            #delipress-react-selector #${idStyle} .mce-content-body ol
        {`

            if(!_.isUndefined(itemStyles["color"]) && !_.isUndefined(itemStyles.color.rgb)){
                css += `color:rgba(${itemStyles.color.rgb.r}, ${itemStyles.color.rgb.g}, ${itemStyles.color.rgb.b}, ${itemStyles.color.rgb.a});`
            }

        css += '}';


        css += `\n#delipress-react-selector #${idStyle} a {
            text-decoration:underline;
        }`

        return css
    }


}


export default BaseTextContentComponent

import * as _ from "underscore"
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classNames from 'classnames'
import reactCSS from 'reactcss'

import ActiveContentComponent from '../ActiveContentComponent'
import {
    SETTINGS_EDITOR,
    SETTINGS_STYLE,
} from 'javascripts/react/constants/EditorConstants'

import {
    TEXT,
    EMAIL_ONLINE
} from 'javascripts/react/constants/TemplateContentConstants'

class BaseContentComponent extends Component {

    constructor(props, ctx) {
        super(props, ctx)
        this.getClasses              = this.getClasses.bind(this)
        this.getStyles               = this.getStyles.bind(this)
        this._activeComponent        = this._activeComponent.bind(this)
    }

    getStyles(styles){

        const { item }   = this.props

        let itemStyles = {}
        itemStyles = _.clone(item.styles)

        let _bg = "transparent"
        if(!_.isUndefined(itemStyles.background)){
            // if(!_.isUndefined(itemStyles.background.rgb)){
            //     _bg = `rgba(${ itemStyles.background.rgb.r }, ${ itemStyles.background.rgb.g }, ${ itemStyles.background.rgb.b }, ${ itemStyles.background.rgb.a })`
            // }
            if(!_.isUndefined(itemStyles.background.hex)){
                _bg = itemStyles.background.hex
            }
        }

        const _defaultComponent = {
            "backgroundColor": _bg,
            "paddingTop" : itemStyles["padding-top"] + "px",
            "paddingBottom" : itemStyles["padding-bottom"] + "px",
            "paddingLeft" : itemStyles["padding-left"] + "px",
            "paddingRight" : itemStyles["padding-right"] + "px",
        }

        const _styles = {
            'default': {
                component: _.extend(_.clone(_defaultComponent), styles)
            }
        }

        return reactCSS(_styles)

    }

    getClasses(defaultClass){
        const {
            itemEditor,
            item,
            activeItem
        } = this.props

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`
        const idSelector = `id_selector_${index}`
        return classNames({
            "delipress__builder__main__preview__component__inner" : true,
            "delipress--is-active" : index === activeItem
        }, defaultClass, idSelector)
    }

     componentWillUnmount(){
        const {
            actionsEditor,
            activeItem,
            keyComponent
        } = this.props

        if(activeItem === keyComponent){
            actionsEditor.activeItem(null)
        }

    }


    _activeComponent(){
        const {
            actionsEditor,
            activeItem,
            keyComponent,
            item
        } = this.props

        if(activeItem !== keyComponent){
            actionsEditor.activeItem(keyComponent)
            let _settingsChange = SETTINGS_EDITOR

            actionsEditor.changeItemOnSettingsContainer(item, _settingsChange)
        }

    }

}



BaseContentComponent.propTypes = {
    item : PropTypes.object.isRequired
}


export default BaseContentComponent

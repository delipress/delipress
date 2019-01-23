import * as _ from "underscore"
import React, { Component } from 'react'
import classNames from "classnames"

import {
    WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION
} from 'javascripts/react/constants/TemplateContentConstants'

function collect(connect, monitor) {
    return {
        connectDragSource: connect.dragSource(),
        connectDragPreview: connect.dragPreview(),
        isDragging: monitor.isDragging()
    }
}


class BaseAdd extends Component {

    render(){
        const {
            children,
            connectDragSource,
            isDragging,
            className
        } = this.props

        let _classNames = className
        if(_.isUndefined(className)){
             _classNames = classNames("delipress__builder__side__draggable")
        }

        if(_.contains(WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION, this.props.type) && !DELIPRESS_LICENSE_STATUS){
            return (
                <div
                    className={_classNames}
                    style={{
                        opacity: 0.5,
                    }}
                >
                    {children}
                </div>
            )
        }

        return connectDragSource(
            <div
                className={_classNames}
                style={{
                opacity: isDragging ? 0.5 : 1,
                cursor: 'move'
              }}>
                {children}
            </div>
        )
    }
}


export default {
    BaseAdd,
    collect
}

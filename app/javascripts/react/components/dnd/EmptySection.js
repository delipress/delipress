import { DragSource, DropTarget } from 'react-dnd';
import PropTypes from 'prop-types'
import { findDOMNode } from 'react-dom';
import React, { Component } from 'react'
import { bindActionCreators, compose } from 'redux'
import { connect } from 'react-redux'
import classNames from 'classnames'

import {
    SECTION,
    LIST_TEMPLATE_CONTENT_LIKE_SECTION,
    WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION
} from 'javascripts/react/constants/TemplateContentConstants';

import { ItemTypes } from '../../constants/TemplateContentConstants';
import { getAfterOrBefore } from 'javascripts/react/helpers/getAfterOrBefore'

const cardDrop = {
    drop(props, monitor, component){
        
        const afterOrBefore      = getAfterOrBefore(monitor, component)

        const _afterOrBeforeItem = {
            "before" : (afterOrBefore == "before") ? true : false,
            "after" : (afterOrBefore == "after") ? false : true
        }

        if(monitor.getItemType() === ItemTypes.ADD_SECTION){
            let   _new   = props.index
            const _type  = monitor.getItem().type

             _new = _.extend({}, _afterOrBeforeItem, {
                "abItmId" : _new,
                "number" : monitor.getItem().number,
                "type" : SECTION
            })
            
            // if(
            //     !_.isUndefined(_type) && 
            //     ( 
            //         LIST_TEMPLATE_CONTENT_LIKE_SECTION.indexOf(_type) >= 0 ||
            //         WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION.indexOf(_type) >= 0
            //     )
            // ){

            //     let _newItem ={
            //         "keyRow"    : 0,
            //         "keyColumn" : 0,
            //         "_id"       : 0,
            //         "type"      : _type
            //     }

            //     props.addSection(_new, _newItem)
            // }
            // else{
                props.addSection(_new)
            // }


        }
        else if(monitor.getItemType() === ItemTypes.MOVE_SECTION){
            let _new   = props.index
            const _old = monitor.getItem().index

            _new = _.extend({}, _afterOrBeforeItem, {
                "abItmId" : _new
            })

            props.moveSection(_old, _new)

        }
        else if(monitor.getItemType() === ItemTypes.ADD_ITEM){
            let _new   = props.index

            _new = _.extend({}, {
                "abItmId" : 0,
                "before" : true,
                "after" : false,
                "number"  : 1,
                "type"    : SECTION
            })

            let _newItem ={
                "keyRow"    : 0,
                "keyColumn" : 0,
                "_id"       : 0,
                "type"      : monitor.getItem().type
            }

            props.addSection(_new, _newItem)
        }

    }
}

function collectDrop(connect, monitor){
    return {
        connectDropTarget: connect.dropTarget(),
        isOver: monitor.isOver(),
        canDrop: monitor.canDrop()
    }
}


class EmptySection extends Component {

    addSection(number){
        const {
            index,
            addSection
        } = this.props

        const newSection = _.extend({}, {
            "after": true,
            "before": false,
            "abItmId" : index,
            "number" : number,
            "keyRow" : 0,
            "type" : SECTION
        })

        addSection(newSection)

    }

    render(){
        const {
            children,
            connectDropTarget,
            isOver,
            index
        } = this.props

        const _class = classNames("delipress__builder__main__preview__newsection", index)

        return (
            connectDropTarget(
                <div
                  className={_class}
                >
                    <div
                        className="delipress__builder__main__preview__newsection__wrap"
                        style={{
                            boxShadow: isOver ? "inset 0 0 0 4px #59C9A5" : "",
                        }}
                    >
                        <p className="delipress__builder__main__preview__newsection__title">
                            {translationDelipressReact.Builder.component.dnd.empty_section.text}
                        </p>
                        <div className="delipress__builder__main__preview__newsection__layouts">
                            <a onClick={this.addSection.bind(this,1)} className="delipress__builder__main__preview__newsection__layout">
                                <div className="delipress__builder__main__preview__newsection__layout__schema"></div>
                            </a>
                            <a onClick={this.addSection.bind(this,2)} className="delipress__builder__main__preview__newsection__layout">
                                <div className="delipress__builder__main__preview__newsection__layout__schema delipress__builder__main__preview__newsection__layout__schema--two"></div>
                            </a>
                            <a onClick={this.addSection.bind(this,3)} className="delipress__builder__main__preview__newsection__layout">
                                <div className="delipress__builder__main__preview__newsection__layout__schema delipress__builder__main__preview__newsection__layout__schema--three"></div>
                            </a>
                            <a onClick={this.addSection.bind(this,4)} className="delipress__builder__main__preview__newsection__layout">
                                <div className="delipress__builder__main__preview__newsection__layout__schema delipress__builder__main__preview__newsection__layout__schema--four"></div>
                            </a>
                        </div>
                    </div>
                </div>
            )
        )
    }
}


EmptySection.propType = {
    addSection : PropTypes.func.isRequired,
    moveSection : PropTypes.func.isRequired
}

export default compose(
    DropTarget([
        ItemTypes.ADD_SECTION,
        ItemTypes.MOVE_SECTION,
        ItemTypes.ADD_ITEM
    ], cardDrop, collectDrop)
)(EmptySection)

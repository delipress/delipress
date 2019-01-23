import * as _ from "underscore"
import { DragSource, DropTarget } from 'react-dnd';
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classNames from 'classnames'
import { findDOMNode } from 'react-dom';
import { bindActionCreators, compose } from 'redux'
import { connect } from 'react-redux'

import {
    ItemTypes,
    SECTION,
    LIST_TEMPLATE_CONTENT_LIKE_SECTION,
    WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION
} from '../../constants/TemplateContentConstants';
import { undecorate } from 'javascripts/react/helpers/undecorate'
import { getAfterOrBefore } from 'javascripts/react/helpers/getAfterOrBefore'
import { stringIndexToObjectPosition } from '../../helpers/structureToTemplate'
import HandleButton from './HandleButton'
import EditorActions from 'javascripts/react/services/actions/EditorActions'



const cardDrop = {
    drop(props, monitor, component){

        const itemMonitor        = monitor.getItem()
        const afterOrBefore      = getAfterOrBefore(monitor, component)

        const _afterOrBeforeItem = {
            "before" : (afterOrBefore == "before") ? true : false,
            "after" : (afterOrBefore == "after") ? true : false
        }

        if(monitor.getItemType() === ItemTypes.ADD_SECTION){

            let _new   = props.index

            _new = _.extend({}, _afterOrBeforeItem, {
                "abItmId" : Number(_new),
                "number" : monitor.getItem().number,
                "type" : SECTION
            })

            // if(!_.isUndefined(itemMonitor.type) &&
            //     (
            //         LIST_TEMPLATE_CONTENT_LIKE_SECTION.indexOf(itemMonitor.type) >= 0 ||
            //         WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION.indexOf(itemMonitor.type) >= 0
            //     )
            // ){

            //     let _newItem ={
            //         "keyRow"    : (afterOrBefore == "before") ? _new.abItmId : Number(_new.abItmId) + 1,
            //         "keyColumn" : 0,
            //         "_id"       : 0,
            //         "type"      : itemMonitor.type
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
                "abItmId" : Number(_new)
            })

            props.moveSection(_old, _new)

        }

        props.actionsEditor.activeSection(null)

    },

    hover(props, monitor, component) {
        if (!monitor.canDrop() || monitor.getItem().index == props.index) {
            return;
        }

        const rawComponent    = undecorate(component);

        rawComponent.setElementHover(getAfterOrBefore(monitor, component))
    }
}

function collectDrop(connect, monitor){
    return {
        connectDropTarget: connect.dropTarget(),
        isOver: monitor.isOver({ shallow: true }),
        isElementOver: monitor.isOver(),
        canDrop: monitor.canDrop()
    }
}

class MoveSection extends Component {
    constructor(props) {
        super(props);

        this.state = {
            elementHover: null
        }
    }
    componentWillReceiveProps(nextProps) {
        if (this.props.isElementOver && !nextProps.isElementOver) {
            this.setState({ elementHover: null });
        }
    }

    setElementHover(elementHover) {
        if (elementHover !== this.state.elementHover) {
            this.setState({ elementHover });
        }
    }

    render(){
        const {
            children,
            connectDragSource,
            connectDragPreview,
            connectDropTarget,
            isDragging,
            isOver,
            index,
            activeItem
        } = this.props

        const { elementHover } = this.state;

        let _style = {}

        if (elementHover === "before") {
            _style = _.extend(_style, {
                opacity: 1,
                top: "-5px",
                zIndex: 500
            })
        } else if (elementHover === "after") {
            _style = _.extend(_style, {
                opacity: 1,
                bottom: "-5px",
                zIndex: 500
            })
        }

        const _classNames = classNames({
            "delipress--is-active" : index === activeItem
        }, index)

        return connectDropTarget(
            <div
                style={{
                    opacity: isDragging ? 0.5 : 1,
                    cursor: 'default',
                    position: 'relative'
                }}
                className={_classNames}
            >
                {children}
                <div className="delipress__builder__main__preview__section__dropzone" style={_style} />
            </div>
        )
    }
}


MoveSection.propType = {
    addSection : PropTypes.func,
    moveSection : PropTypes.func
}

function mapDispatchToProps(dispatch, context){
    const actionsEditor   = new EditorActions()

    return {
        "actionsEditor"  : bindActionCreators(actionsEditor, dispatch)
    }
}

function mapStateToProps(state){
    return {
        "activeSection" : state.EditorReducer.activeSection,
        "activeItem" : state.EditorReducer.activeItem
    }
}


export default compose(
    connect(mapStateToProps,mapDispatchToProps),
    // DragSource(ItemTypes.MOVE_SECTION, cardDrag, collectDrag),
    DropTarget([ItemTypes.MOVE_SECTION, ItemTypes.ADD_SECTION], cardDrop, collectDrop),
)(MoveSection)

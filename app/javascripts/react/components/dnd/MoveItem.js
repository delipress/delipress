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
    LIST_TEMPLATE_CONTENT_LIKE_SECTION
} from 'javascripts/react/constants/TemplateContentConstants';

import {
    SETTINGS_LIST_CONTENTS
} from 'javascripts/react/constants/EditorConstants'

import {
    TEXT,
    TITLE
} from 'javascripts/react/constants/TemplateContentConstants'

import { undecorate } from 'javascripts/react/helpers/undecorate'
import { getAfterOrBefore } from 'javascripts/react/helpers/getAfterOrBefore'
import { stringIndexToObjectPosition } from 'javascripts/react/helpers/structureToTemplate'
import HandleButton from 'javascripts/react/components/dnd/HandleButton'
import EditorActions from 'javascripts/react/services/actions/EditorActions'

const cardDrop = {
    drop(props, monitor, component){

        const afterOrBefore      = getAfterOrBefore(monitor, component)

        const _afterOrBeforeItem = {
            "before" : (afterOrBefore == "before") ? true : false,
            "after" : (afterOrBefore == "after") ? true : false
        }

        if(monitor.getItemType() === ItemTypes.ADD_ITEM){

            let _new   = stringIndexToObjectPosition(props.index)

            _new = _.extend(_new, _afterOrBeforeItem, {
                "type" : monitor.getItem().type,
                "abItmId" : _new._id
            })

            props.addItem(_new)

        }
        else if(monitor.getItemType() === ItemTypes.MOVE_ITEM){
            let _new   = stringIndexToObjectPosition(props.index)
            const _old = stringIndexToObjectPosition(monitor.getItem().index)

            _new = _.extend(_new, _afterOrBeforeItem, {
                "abItmId" : _new._id
            })

            if(
                _old.keyColumn == _new.keyColumn &&
                (
                    ( Number(_old._id-1) == _new._id && _afterOrBeforeItem.after ) ||
                    ( Number(_old._id+1) == _new._id && _afterOrBeforeItem.before)
                )
            ){
                return;
            }

            props.moveItem(_old, _new)

        }

        // props.actionsEditor.activeItem(null)

    },

    hover(props, monitor, component) {

        if (
            !monitor.canDrop() ||
            monitor.getItem().index == props.index
            // LIST_TEMPLATE_CONTENT_LIKE_SECTION.indexOf(props.item.type) >= 0
        ) {
            return;
        }

        const rawComponent    = undecorate(component);

        rawComponent.setElementHover(getAfterOrBefore(monitor, component))
    },
    canDrop(props, monitor){
        if (
            monitor.getItem().index == props.index
            // LIST_TEMPLATE_CONTENT_LIKE_SECTION.indexOf(props.item.type) >= 0
        ) {
            return false;
        }

        return true;
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


class MoveItem extends Component {
    constructor(props) {
        super(props);

        this.state = {
            elementHover: null
        }
    }

    componentDidMount() {
        const {
            connectDragPreview
        } = this.props;
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
            connectDragPreview,
            connectDropTarget,
            isDragging,
            isOver,
            index,
            activeItem,
            item,
            actionsEditor
        } = this.props

        const {
            elementHover
        } = this.state;

        let _style = {}

        if (elementHover === "before") {
            _style = _.extend(_style, {
                opacity: 1,
                top: "-5px",
            })
        } else if (elementHover === "after") {
            _style = _.extend(_style, {
                opacity: 1,
                bottom: "-5px",
            })
        }

        const _classNames = classNames({
            "delipress__builder__main__preview__component" : true,
            "delipress--is-active" : index === activeItem
        }, index)

        let _cursor = "default"

        if(index === activeItem){
            switch(this.props.item.type){
                case TITLE:
                case TEXT:
                    _cursor = "text"
                    break;
                default:
                    _cursor = "move"
                    break;
            }
        }

        let _zIndex = 999
        if(item._id != 0){
            _zIndex -= Number(item._id)
        }

        if(activeItem == index){
            _zIndex = 5000
        }

        return (
            connectDropTarget(
                <div
                    style={{
                        opacity: isDragging ? 0.5 : 1,
                        cursor: _cursor,
                        position: 'relative',
                        zIndex : _zIndex
                    }}
                    className={_classNames}
                >
                    {children}
                    <div className="delipress__builder__main__preview__component__dropzone" style={_style} />
                </div>
            )

        )


    }
}


MoveItem.propType = {
    addItem : PropTypes.func.isRequired,
    moveItem : PropTypes.func.isRequired
}

function mapDispatchToProps(dispatch, context){
    const actionsEditor   = new EditorActions()

    return {
        "actionsEditor"  : bindActionCreators(actionsEditor, dispatch)
    }
}


export default compose(
    connect(null,mapDispatchToProps),
    DropTarget([ItemTypes.MOVE_ITEM, ItemTypes.ADD_ITEM], cardDrop, collectDrop),
)(MoveItem)

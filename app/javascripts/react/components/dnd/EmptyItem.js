import { DragSource, DropTarget } from 'react-dnd';
import { findDOMNode } from 'react-dom';
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { bindActionCreators, compose } from 'redux'
import { connect } from 'react-redux'
import classNames from 'classnames'

import { stringIndexToObjectPosition } from '../../helpers/structureToTemplate'
import { ItemTypes } from '../../constants/TemplateContentConstants';

const cardDrop = {
    drop(props, monitor, component){

        let _new   = stringIndexToObjectPosition(props.index)

        if(monitor.getItemType() === ItemTypes.ADD_ITEM){
            _new = _.extend(_new, {
                "type" : monitor.getItem().type
            })

            props.addItemOnEmpty(_new)
        }
        else if(monitor.getItemType() === ItemTypes.MOVE_ITEM ) {

            const _old = stringIndexToObjectPosition(monitor.getItem().index)

            props.moveItem(_old, _new)
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


class EmptyItem extends Component {

    render(){
        const {
            children,
            connectDropTarget,
            isOver,
            index
        } = this.props

        const _class = classNames("delipress__builder__main__preview__addcomponent", index)
        return (
            connectDropTarget(
                <div
                    style={{
                        background: isOver ? "repeating-linear-gradient(-55deg,RGBA(93, 196, 245, .1), RGBA(93, 196, 245, .1) 3px, RGBA(93, 196, 245, 0) 3px, RGBA(93, 196, 245, 0) 6px)" : "",
                    }}
                    className={_class}
                >
                    {children}
                </div>
            )
        )
    }
}


EmptyItem.propType = {
    addItem : PropTypes.func.isRequired,
    moveItem : PropTypes.func.isRequired
}

export default compose(
    DropTarget([ItemTypes.ADD_ITEM, ItemTypes.MOVE_ITEM], cardDrop, collectDrop)
)(EmptyItem)

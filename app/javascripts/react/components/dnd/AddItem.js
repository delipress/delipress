import { DragSource } from 'react-dnd';
import React, { Component } from 'react'
import { bindActionCreators } from 'redux'
import { connect } from 'react-redux'

import { ItemTypes } from '../../constants/TemplateContentConstants';
import BaseAddComponent from 'javascripts/react/components/dnd/BaseAdd'

const moveSource = {
    beginDrag(props) {
        return {
            "type" : props.type,
            "item" : props.children
        }
    }
}


class AddItem extends BaseAddComponent.BaseAdd {}


export default DragSource(ItemTypes.ADD_ITEM, moveSource, BaseAddComponent.collect)(AddItem);

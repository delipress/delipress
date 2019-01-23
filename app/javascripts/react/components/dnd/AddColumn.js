import { DragSource } from 'react-dnd';
import React, { Component } from 'react'
import { bindActionCreators } from 'redux'
import { connect } from 'react-redux'

import { ItemTypes } from '../../constants/TemplateContentConstants';
import BaseAddComponent from 'javascripts/react/components/dnd/BaseAdd'

const moveSource = {
    beginDrag(props) {
        return {
            "number" : props.number,
            "item" : props.children,
            "type": props.type || null
        }
    }
}

class AddColumn extends BaseAddComponent.BaseAdd {}


export default DragSource(ItemTypes.ADD_SECTION, moveSource, BaseAddComponent.collect)(AddColumn);

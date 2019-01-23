import { DragSource, DropTarget } from 'react-dnd';
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classNames from 'classnames'
import { findDOMNode } from 'react-dom';
import { bindActionCreators, compose } from 'redux'
import { connect } from 'react-redux'

import { ItemTypes } from '../../constants/TemplateContentConstants';
import {
    SETTINGS_LIST_CONTENTS
} from 'javascripts/react/constants/EditorConstants'

import {
    TEXT
} from 'javascripts/react/constants/TemplateContentConstants'
import { undecorate } from 'javascripts/react/helpers/undecorate'
import { stringIndexToObjectPosition } from '../../helpers/structureToTemplate'
import HandleButton from './HandleButton'
import EditorActions from 'javascripts/react/services/actions/EditorActions'


class NoMoveItem extends Component {


    render(){
        const {
            children,
            index,
            activeItem,
        } = this.props


        const _classNames = classNames({
            "delipress__builder__main__preview__component" : true,
            "delipress--is-active" : index === activeItem
        }, index)
        

        return (
            <div
                className={_classNames}
            >
                {children}
            </div>
        )
    }
}


function mapStateToProps(state){
    return {
        "activeItem" : state.EditorReducer.activeItem
    }
}


export default connect(mapStateToProps)(NoMoveItem)

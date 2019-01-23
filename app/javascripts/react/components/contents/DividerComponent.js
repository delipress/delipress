import * as _ from "underscore"
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { mjml2html } from 'mjml';
import { bindActionCreators } from 'redux'

import { transformItemDividerAlone } from 'javascripts/react/helpers/transformToMjml'
import EditorActions from 'javascripts/react/services/actions/EditorActions'
import BaseContentComponent from './BaseContentComponent'

class DividerComponent extends BaseContentComponent {

    render(){
        const { item }  = this.props
        const styleItem = this.getStyles()
        const element   = transformItemDividerAlone(item)
        const result    = mjml2html(element)

        let tempDom   = jQuery('<output>').append(jQuery.parseHTML(result.html));
        let content   = jQuery('p', tempDom).wrap( "<div></div>").parent();

        let _html = ""
        if(content.length > 0){
            _html = content.html()
        }

        return (
            <div
                className={this.getClasses()}
                style={styleItem.component}
                onClick={this._activeComponent}
            >
                <div className="delipress__builder__main__preview__component__mjml delipress__builder__main__preview__component__mjml--divider" dangerouslySetInnerHTML={{"__html" :_html}} />
            </div>
        )
    }
}


function mapDispatchToProps(dispatch, context){
    const actionsEditor   = new EditorActions()

    return {
        "actionsEditor"  : bindActionCreators(actionsEditor, dispatch)
    }
}


function mapStateToProps(state){
    return {
        "activeItem" : state.EditorReducer.activeItem
    }
}


export default connect(mapStateToProps, mapDispatchToProps)(DividerComponent)

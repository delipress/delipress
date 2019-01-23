import * as _ from "underscore"
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { mjml2html } from 'mjml';
import { bindActionCreators } from 'redux'

import { transformItemButtonAlone } from 'javascripts/react/helpers/transformToMjml'
import EditorActions from 'javascripts/react/services/actions/EditorActions'
import BaseContentComponent from './BaseContentComponent'

class ButtonComponent extends BaseContentComponent {

    render(){
        const { item }  = this.props
        const styleItem = this.getStyles()
        const element   = transformItemButtonAlone(item)
        const result    = mjml2html(element)

        let tempDom   = jQuery('<output>').append(jQuery.parseHTML(result.html));
        let content   = jQuery('table', tempDom).wrap( "<div></div>").parent();

        let _html = ""
        if(content.length > 0){
            _html = content.html()
        }

        const id = `delipress-component-button-${item.keyRow}${item.keyColumn}${item["_id"]}`
        const _styleInline = `
            #delipress-react-selector #${id} a{
                display:block;
            }
        `
        return (
            <div
                id={id}
                className={this.getClasses()}
                style={styleItem.component}
                onClick={this._activeComponent}
            >   
                <style>{_styleInline}</style>
                <div className="delipress__builder__main__preview__component__mjml delipress__builder__main__preview__component__mjml--button" dangerouslySetInnerHTML={{"__html" :_html}} />
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


export default connect(mapStateToProps, mapDispatchToProps)(ButtonComponent)

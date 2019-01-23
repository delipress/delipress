import React, { Component } from 'react'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'

import { mjml2html } from 'mjml';
import { transformItemSocialAlone } from '../../helpers/transformToMjml'
import EditorActions from 'javascripts/react/services/actions/EditorActions'
import BaseContentComponent from './BaseContentComponent'

class SocialComponent extends BaseContentComponent {

    componentDidMount(){
        const { item } = this.props

        jQuery(`#${item.keyRow}${item.keyColumn}${item._id} a`).on("click", function(e) {
            e.preventDefault();
        })
    }

    render(){

        const { item }  = this.props
        const styleItem = this.getStyles()

        const element   = transformItemSocialAlone(item)
        const result    = mjml2html(element)

        let tempDom   = jQuery('<output>').append(jQuery.parseHTML(result.html));
        let content   = jQuery('div', tempDom);

        let _html = ""
        if(content.length > 0){
            _html = content.html()
        }

        return (
            <div
                id={`${item.keyRow}${item.keyColumn}${item._id}`}
                className={this.getClasses("delipress__content__social")}
                onClick={this._activeComponent}
                style={styleItem.component}
            >
                <div className="delipress__builder__main__preview__component__mjml delipress__builder__main__preview__component__mjml--social" dangerouslySetInnerHTML={{"__html" :_html}} />
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


export default connect(mapStateToProps, mapDispatchToProps)(SocialComponent)

import * as _ from "underscore"
import React, { Component } from 'react'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'

import { mjml2html } from 'mjml';
import { transformItemImageAlone  } from '../../helpers/transformToMjml'
import EditorActions from 'javascripts/react/services/actions/EditorActions'
import BaseContentComponent from './BaseContentComponent'

class ImageComponent extends BaseContentComponent {

    render(){
        const { item }  = this.props
        const styleItem = this.getStyles()


        if(_.isEmpty(item.styles.src)){
            return (
                <div
                    className={this.getClasses()}
                    onClick={this._activeComponent}
                    style={styleItem.component}
                >
                    <div
                        className="delipress__builder__main__preview__addimage"
                    >
                        <span className="dashicons dashicons-format-image"></span>
                    </div>
                </div>
            )
        }

        const element   = transformItemImageAlone(item)
        const result    = mjml2html(element)

        let tempDom   = jQuery('<output>').append(jQuery.parseHTML(result.html));
        let content   = jQuery('table', tempDom).wrap( "<div></div>").parent();

        let _html = ""
        if(content.length > 0){
            _html = content.html()
        }


        return (
            <div
                className={this.getClasses()}
                onClick={this._activeComponent}
                style={styleItem.component}
            >
                <div className="delipress__builder__main__preview__component__mjml delipress__builder__main__preview__component__mjml--image" dangerouslySetInnerHTML={{"__html" :_html}} />
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



export default connect(mapStateToProps, mapDispatchToProps)(ImageComponent)

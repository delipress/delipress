import * as _ from "underscore"
import React, { Component } from 'react'
import { connect } from 'react-redux'
import classNames from 'classnames'
import { compose, bindActionCreators } from 'redux'
import { mjml2html } from 'mjml';

import BaseHeaderFooter from 'javascripts/react/components/contents/base/BaseHeaderFooter'
import EditorActions from '../../services/actions/EditorActions'


class EmailOnlineComponent extends Component {

    render(){
        return (
            <BaseHeaderFooter
                metaReplace="email_online"
                {...this.props}
            />
        )
    }
}



function mapDispatchToProps(dispatch, context){

    let actionsEditor = new EditorActions()

    return {
        "actionsEditor" : bindActionCreators(actionsEditor, dispatch)
    }
}

function mapStateToProps(state){
    return {
        "activeItem" : state.EditorReducer.activeItem
    }
}


export default connect(mapStateToProps, mapDispatchToProps)(EmailOnlineComponent)

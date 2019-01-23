import React, { Component } from 'react'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'


import BaseContentComponent from './BaseContentComponent'
import EditorActions from 'javascripts/react/services/actions/EditorActions'
import FakePost from 'javascripts/react/components/misc/FakePost'

class WPArticleComponent extends BaseContentComponent {

    render(){
        return (
            <div
                className={this.getClasses()}
                onClick={this._activeComponent}
            >
                <FakePost />
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



export default connect(mapStateToProps, mapDispatchToProps)(WPArticleComponent)

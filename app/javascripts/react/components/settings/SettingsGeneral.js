import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'

import TemplateActions from 'javascripts/react/services/actions/TemplateActions'
import EditorActions from 'javascripts/react/services/actions/EditorActions'

import General from 'javascripts/react/components/settings/style/General'

class SettingsGeneral extends Component  {
    
    constructor(props){
        super(props)

        this.updateEmailOnlineActive = this.updateEmailOnlineActive.bind(this)
        this.updateTheme             = this.updateTheme.bind(this)
    }

    updateTheme(themeObj){
        const { 
            actionsTemplate,
            changeItemSuccess
        } = this.props

        const deferred = actionsTemplate.changeTheme(themeObj)

        if(!_.isUndefined(changeItemSuccess)){
            deferred.then(changeItemSuccess)
        }
    
    }

    updateEmailOnlineActive(checked){
        const { 
            actionsEditor,
            changeItemSuccess
        } = this.props

        const deferred = actionsEditor.changeEmailOnlineActive(checked)

        if(!_.isUndefined(changeItemSuccess)){
            deferred.then(changeItemSuccess)
        }
    }

    render() {

        return (
            <div className="container__settings-general">
                <General 
                    updateTheme={this.updateTheme}
                    updateEmailOnlineActive={this.updateEmailOnlineActive}
                />
            </div>
        )
    }
}

function mapStateToProps(state){
  
    return {
        "theme" : state.TemplateReducer.config.theme
    }
}

function mapDispatchToProps(dispatch, context){
    const actionsTemplate = new TemplateActions()
    const actionsEditor = new EditorActions()

    return {
        "actionsTemplate": bindActionCreators(actionsTemplate, dispatch),
        "actionsEditor": bindActionCreators(actionsEditor, dispatch)
    }   
}

export default connect(mapStateToProps, mapDispatchToProps)(SettingsGeneral)


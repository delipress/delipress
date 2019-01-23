import React, { Component, cloneElement } from 'react'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'
import * as _ from 'underscore'

import SettingsContainer from 'javascripts/react/containers/optin/SettingsContainer'
import PreviewContainer from 'javascripts/react/containers/optin/PreviewContainer'
import OptinActions from 'javascripts/react/services/actions/OptinActions'


class CreateOptinStepTwo extends Component  {
    
    constructor(props){
        super(props)

        this.updateOptin     = this.updateOptin.bind(this)
        this.saveValuesOptin = null
    }

    componentWillMount(){
        const { 
            actionsOptin
        } = this.props

        if(!_.isEmpty(DELIPRESS_OPTIN_ID)){
            actionsOptin.getOptin(DELIPRESS_OPTIN_ID)
        }

    }

    updateOptin(optinConfig){
        const { 
            actionsOptin,
            type
        } = this.props

        const deferred = actionsOptin.changeOptin(optinConfig)

        deferred.then(() => {
            clearTimeout(this.saveValuesOptin)

            this.saveValuesOptin = setTimeout(() => {
                actionsOptin.saveOptin(
                    DELIPRESS_OPTIN_ID,
                    {
                        "type"   : type,
                        "config" : optinConfig
                    }
                )
            }, 500)
            
        })

    }

    render() {

        return (
            <div className="delipress__builder">
                <SettingsContainer 
                    updateOptin={this.updateOptin}
                />
                <PreviewContainer 
                    updateOptin={this.updateOptin}
                    actionsOptin={this.props.actionsOptin}
                />
            </div>    
        )
    }
}

function mapStateToProps(state){
    return { 
        "config" : state.OptinReducer.config,
    } 
}

function mapDispatchToProps(dispatch, context){
    const actionsOptin = new OptinActions()

    return {
        "actionsOptin": bindActionCreators(actionsOptin, dispatch),
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(CreateOptinStepTwo)


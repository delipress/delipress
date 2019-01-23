import React, { Component, cloneElement } from 'react'

import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'

import BaseGeneral from 'javascripts/react/components/settings/base/BaseGeneral'
import EmailOnlineActive from 'javascripts/react/components/settings/style/EmailOnlineActive'


class General extends BaseGeneral  {

    render(){

        return (
            <span>
                <EmailOnlineActive updateEmailOnlineActive={this.props.updateEmailOnlineActive} />
                {this._renderThemeDefault()}
            </span>
        )
    }
}


function mapStateToProps(state){
    const theme = state.TemplateReducer.config.theme

    if(_.isNull(theme)){
        return {}
    }

    return {
        "mjAll"       : theme["mj-attributes"]["mj-all"],
        "mjContainer" : theme["mj-attributes"]["mj-container"],
        "mjText"      : theme["mj-attributes"]["mj-text"],
        "linkColor"   : theme["mj-styles"]["link-color"]
    }
}


export default connect(mapStateToProps)(General)


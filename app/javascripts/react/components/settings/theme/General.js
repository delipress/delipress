import React, { Component, cloneElement } from 'react'

import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'

import BaseGeneral from 'javascripts/react/components/settings/base/BaseGeneral'


class General extends BaseGeneral  {

    render(){
        return (
            <span>
                {this._renderThemeDefault()}
            </span>
        )
    }
    
}


function mapStateToProps(state){
    const theme = state.ThemeReducer.theme

    if(_.isNull(theme)){
        return {}
    }

    return {
        "mjAll" : theme["mj-attributes"]["mj-all"],
        "mjContainer" : theme["mj-attributes"]["mj-container"],
        "linkColor" : theme["mj-styles"]["link-color"]
    }
}


export default connect(mapStateToProps)(General)


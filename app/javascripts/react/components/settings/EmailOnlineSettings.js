import React, { Component } from 'react'
import { connect } from 'react-redux'

import BaseHeaderFooterSettings from 'javascripts/react/components/settings/base/BaseHeaderFooterSettings'

class EmailOnlineSettings extends BaseHeaderFooterSettings {}


function mapStateToProps(state){
    if(_.isNull(state.EditorReducer.activeItem)){
        return {
            item : null
        }
    }

    return {
        item : state.TemplateReducer.config.email_online[0].columns[0].items[0]
    }
}


export default connect(mapStateToProps)(EmailOnlineSettings)



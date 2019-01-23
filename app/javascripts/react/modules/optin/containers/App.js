import React, { Component } from 'react'
import { connect } from 'react-redux'
import * as _ from 'underscore'

class App extends Component {
    
    componentDidMount(){
        jQuery("#delipress__autosave").css("display", "block")
    }

    render() {
        const { children } = this.props

        return (    
            <div>
                {children}
            </div>

        )
    }
}


export default connect()(App)

    
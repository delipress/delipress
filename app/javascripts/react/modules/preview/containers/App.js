import React, { Component } from 'react'
import { connect } from 'react-redux'
import * as _ from 'underscore'

class App extends Component {

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

    
import React, { Component } from 'react'
import { connect } from 'react-redux'

class EmptyComponent extends Component {

    render(){

        return (
            <div className="delipress__builder__main__preview__addcomponent__text">
                <span className="dashicons dashicons-migrate"></span><br/>
                <span>{translationDelipressReact.drag}</span>
            </div>
        )
    }
}


export default connect()(EmptyComponent)

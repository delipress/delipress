import React, { Component } from 'react'

class HandleButton extends Component {

    render(){
        return (
            <a
                title={translationDelipressReact.Builder.actions.move}
                className="delipress__builder__main__preview__actions__button"
            >
                <span className="dashicons dashicons-move"></span>
            </a>
        )
    }
}

export default HandleButton

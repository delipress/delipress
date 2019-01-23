import React, { Component, cloneElement } from 'react'

class SettingsContainer extends Component  {
    render() {
        return (
            <div className="delipress__builder__side">
                {this.props.children}
            </div>

        )
    }
}

export default SettingsContainer

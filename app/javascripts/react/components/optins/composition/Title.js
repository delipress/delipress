import React from "react"

const Title = props => {
    if (props.settings == "default") {
        return (
            <div style={props.style} className="DELI-title">
                {props.children}
            </div>
        )
    } else {
        return false
    }
}

export default Title

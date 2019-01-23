import React from "react"

const WrapperForm = props => {
    if (!props.condition()) {
        return false
    }

    let formClass = "DELI-formBloc "

    if (props.formSize !== "default") {
        formClass += `DELI-formBloc--${props.formSize}`
    }

    return (
        <form style={props.style} className={formClass}>
            {props.children}
        </form>
    )
}

export default WrapperForm

import React from "react"

const SettingsItem = props => {
    let classNames = "delipress__builder__side__setting__input"
    let rootClassNames = "delipress__builder__side__setting"

    if (props.classModifier != null) {
        classNames += " " + props.classModifier
    }

    if (props.full != null) {
        rootClassNames += " delipress__builder__side__setting--full"
    }

    if (props.rootClassModifier != null) {
        rootClassNames += " " + props.rootClassModifier
    }

    const { id } = props

    return (
        <div className={rootClassNames}>
            {props.label != null &&
                <label
                    htmlFor={id || ""}
                    className="delipress__builder__side__setting__label"
                >
                    {props.label}
                </label>}
            <div className={classNames}>
                {props.children}
            </div>
        </div>
    )
}

export default SettingsItem

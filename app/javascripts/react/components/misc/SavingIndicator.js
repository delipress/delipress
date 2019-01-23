import React from "react"

function Saving(props) {
    return (
        <div>
            <span className="dashicons dashicons-update dashicons--roll" />
            <span className="delipress__builder__main__actions__autosave__text">
                {translationDelipressReact.saving}
            </span>
        </div>
    )
}

function Saved(props) {
    return (
        <div>
            <span className="dashicons dashicons-yes" />
            <span className="delipress__builder__main__actions__autosave__text">
                {translationDelipressReact.saved}
            </span>
        </div>
    )
}

const SavingIndicator = props => {
    const saving = props.saving
    let save = null

    if (saving) {
        save = <Saving />
    } else {
        save = <Saved />
    }
    return (
        <div className="delipress__builder__main__actions__autosave">
            {save}
        </div>
    )
}

export default SavingIndicator

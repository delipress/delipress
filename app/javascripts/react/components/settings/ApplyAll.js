import React from 'react'
import SettingsItem from "javascripts/react/components/settings/SettingsItem"

const ApplyAll = (props) => {
    return (
        <div className="delipress__builder__side__setting__apply_all">
            <span>
                {props.text}
            </span>
            <button
                className="delipress__button delipress__button--second delipress__button--small"
                onClick={props.handleApply}
            >
                {translationDelipressReact.Builder.component_settings.apply_all_button}
            </button>
        </div>
    )
}

export default ApplyAll

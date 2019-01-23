import React from "react"
const OptinStates = props => {
    const optinStates = [
        {
            label: translationDelipressReact.default,
            key: "default"
        }, 
        {
            label: translationDelipressReact.success,
            key: "success"
        }
    ]

    let optinStatesRender = []

    _.each(
        optinStates,
        (el, i) => {
            optinStatesRender.push(
                <div className="delipress__buttonsgroup__cell" key={i}>
                    <input
                        type="radio"
                        name="optin-state"
                        id={"optin-state-" + el.key}
                        name="Optin State"
                        value={el.key}
                        checked={props.settings === el.key}
                        onChange={e =>
                            props.actionsOptin.changeSettingsOptin(
                                e.target.value
                            )}
                    />

                    <label
                        htmlFor={"optin-state-" + el.key}
                        className="delipress__buttonsgroup__cell"
                    >
                        {el.label}
                    </label>
                </div>
            )
        },
        this
    )

    return (
        <div className="delipress__optins__state">
            <strong>
                {
                    translationDelipressReact.Builder.component_settings.optin
                        .state
                }
            </strong>

            <div className="delipress__buttonsgroup">
                {optinStatesRender}
            </div>
        </div>
    )
}

export default OptinStates

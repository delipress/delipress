import React from "react"

const FakePopup = props => {
    return (
        <div className="delipress__fake__site delipress__fake__site--flyin">
            <div className="delipress__fake__flyin">
                {props.children}
            </div>
        </div>
    )
}

export default FakePopup

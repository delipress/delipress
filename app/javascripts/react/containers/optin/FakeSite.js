import React from "react"

const FakeSite = props => {
    return (
        <div className="delipress__fake__site">
            <div className="delipress__fake__post">
                <div className="delipress__fake__title" />
                <span className="delipress__fake__text" />
                <span
                    className="delipress__fake__text"
                    style={{ width: "70%" }}
                />
                {props.children}
                <span className="delipress__fake__text" />
                <span
                    className="delipress__fake__text"
                    style={{ width: "80%" }}
                />
                <span
                    className="delipress__fake__text"
                    style={{ width: "90%" }}
                />
                <span
                    className="delipress__fake__text"
                    style={{ width: "60%" }}
                />
            </div>
        </div>
    )
}

export default FakeSite

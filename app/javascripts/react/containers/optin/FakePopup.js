import React from "react"

const FakePopup = props => {
    return (
        <div className="delipress__fake__site">
            <div className="delipress__fake__post">
                <div className="delipress__fake__overlay" />
                <div className="delipress__fake__title" />
                <span className="delipress__fake__text" />
                <span
                    className="delipress__fake__text"
                    style={{ width: "70%" }}
                />
                <span
                    className="delipress__fake__text"
                    style={{ width: "76%" }}
                />
                <span
                    className="delipress__fake__text"
                    style={{ width: "82%" }}
                />
                <div className="delipress__fake__image" />
                <div className="delipress__fake__popup">
                    {props.children}
                </div>
                <span className="delipress__fake__text" />
                <span
                    className="delipress__fake__text"
                    style={{ width: "80%" }}
                />
                <span
                    className="delipress__fake__text"
                    style={{ width: "85%" }}
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

export default FakePopup

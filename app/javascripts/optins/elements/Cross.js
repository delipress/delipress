import { h, render, Component } from "preact"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

const Cross = props => {
    const positionCross =
        Number(props.position.replace(/(\d+)(\D+)?/gm, "$1")) / 2 + 8

    let styleCross = ""
    if (props.orientation == "right") {
        styleCross = `top: ${positionCross}px; left: ${positionCross}px`
    } else {
        styleCross = `top: ${positionCross}px; right: ${positionCross}px`
    }

    return (
        <span class="DELI-close" style={styleCross} onClick={props.action}>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 16 16"
                width="16"
                height="16"
                fill="none"
                stroke="currentcolor"
                stroke-width="3"
                style="display:inline-block;vertical-align:middle;overflow:visible;"
            >
                <path d="M1.060 1.060 L14.939 14.939" />
                <path d="M14.939 1.060 L1.060 14.939" />
            </svg>
        </span>
    )
}

export default Cross

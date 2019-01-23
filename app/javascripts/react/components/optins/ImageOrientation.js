import React from "react"

const ImageOrientation = props => {
    switch (props.orientation) {
        case "top":
            return (
                <svg
                    width="18"
                    height="13"
                    viewBox="0 0 18 13"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g fill="none">
                        <path fill="#778F9B" d="M6 11h6v2h-6zM0 8h18v2h-18z" />
                        <path fill="#778F9B" d="M4 0h10v7h-10z" />
                    </g>
                </svg>
            )
            break
        case "bottom":
            return (
                <svg
                    width="18"
                    height="13"
                    viewBox="0 0 18 13"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g fill="none">
                        <path fill="#778F9B" d="M6 5h6v-2h-6zM0 2h18v-2h-18z" />
                        <path fill="#778F9B" d="M4 13h10v-7h-10z" />
                    </g>
                </svg>
            )
            break
        case "left":
            return (
                <svg
                    width="20"
                    height="8"
                    viewBox="0 0 20 8"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g fill="none">
                        <path
                            fill="#778F9B"
                            d="M14 6h4v2h-4zM12 0h8v2h-8zM12 3h8v2h-8z"
                        />
                        <path fill="#778F9B" d="M0 0h10v7h-10z" />
                    </g>
                </svg>
            )
            break
        case "right":
            return (
                <svg
                    width="20"
                    height="8"
                    viewBox="0 0 20 8"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g fill="none">
                        <path
                            fill="#778F9B"
                            d="M6 6h-4v2h4zM8 0h-8v2h8zM8 3h-8v2h8z"
                        />
                        <path fill="#778F9B" d="M20 0h-10v7h10z" />
                    </g>
                </svg>
            )
            break
        default:
            return (
                <svg
                    width="20"
                    height="8"
                    viewBox="0 0 20 8"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g fill="none">
                        <path d="M22-8h-24v24h24z" />
                        <path
                            fill="#778F9B"
                            d="M6 6h-4v2h4zM8 0h-8v2h8zM8 3h-8v2h8z"
                        />
                        <path fill="#778F9B" d="M20 0h-10v7h10z" />
                    </g>
                </svg>
            )
    }
}

export default ImageOrientation

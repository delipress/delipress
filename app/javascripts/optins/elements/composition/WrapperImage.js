import { h, render, Component } from "preact"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

const WrapperImage = (props) => {

    if(!props.defaultConfig.wrapper_image.attrs.active){
        return false
    }

    return (
        <div className="DELI-wrapper-image">
            {props.children}
        </div>
    )
}

export default WrapperImage
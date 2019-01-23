import { h, render, Component } from "preact"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

const Wrapper = props => {
    const attrs = props.defaultConfig.wrapper.attrs
    let googleFontUrl = ""

    let _class = "DELI-wrapper ";

    if(props.defaultConfig.wrapper_image.attrs.active){
        _class += attrs.orientation
    }

    if (attrs.animation != "none") {
        _class += " DELI-animated"
    }

    if (attrs.animation != "none") {
        _class += " DELI-" + attrs.animation
    }

    if (
        attrs.fontFamily != undefined &&
        attrs.fontFamily !== "Website font" &&
        attrs.fontFamily !== "WordPress font"
    ) {
        const googleFontName = attrs.fontFamily.replace(" ", "+")
        googleFontUrl = `@import url('https://fonts.googleapis.com/css?family=${googleFontName}');`
    }

    if (props.defaultConfig.form_wrapper.attrs.naked) {
        _class += " DELI-naked"
    }

    return (
        <div style={props.style} id={props.config.uid} className={_class}>
            <style>
                {googleFontUrl}
            </style>
            {props.children}
        </div>
    )
}

export default Wrapper

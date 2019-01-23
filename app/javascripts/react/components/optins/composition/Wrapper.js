import React from "react"
import classNames from "classnames"
import * as _ from "underscore"

const Wrapper = props => {
    const attrs = props.defaultConfig.wrapper.attrs
    let googleFontUrl = ""

    let _class = classNames(
        {
            [attrs.orientation]: (props.defaultConfig.wrapper_image.attrs.active),
            "DELI-animated": attrs.animation != "none"
        },
        "DELI-wrapper"
    )

    if (attrs.animation != "none") {
        _class += " DELI-" + attrs.animation
    }

    if (
        !_.isUndefined(attrs.fontFamily) &&
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

import * as _ from "underscore"

export function transformStyleSectionToTemplate(attributes) {
    if (_.isUndefined(attributes)) {
        return {}
    }

    let _prepareAttributes = {
        width: configDelipressReact.container_width + "px"
    }

    if (_.isNumber(attributes["padding-top"])) {
        _prepareAttributes["paddingTop"] = attributes["padding-top"] + "px"
    }

    if (_.isNumber(attributes["padding-bottom"])) {
        _prepareAttributes["paddingBottom"] =
            attributes["padding-bottom"] + "px"
    }

    if (_.isNumber(attributes["padding-left"])) {
        _prepareAttributes["paddingLeft"] = attributes["padding-left"] + "px"
    }

    if (_.isNumber(attributes["padding-right"])) {
        _prepareAttributes["paddingRight"] = attributes["padding-right"] + "px"
    }

    if (
        _.isObject(attributes["background"]) &&
        !_.isUndefined(attributes["background"].rgb)
    ) {
        _prepareAttributes["backgroundColor"] = `rgba(${attributes["background"]
            .rgb.r}, ${attributes["background"].rgb.g}, ${attributes[
            "background"
        ].rgb.b}, ${attributes["background"].rgb.a})`
    }

    if (!_.isEmpty(attributes["background-url"])) {
        _prepareAttributes["backgroundImage"] = `url(${attributes[
            "background-url"
        ]})`
        _prepareAttributes["backgroundPosition"] = "top center"
        _prepareAttributes["backgroundSize"] = "auto"
    }

    if (!_.isEmpty(attributes["display"])) {
        _prepareAttributes["display"] = attributes["display"]
    }

    return _prepareAttributes
}

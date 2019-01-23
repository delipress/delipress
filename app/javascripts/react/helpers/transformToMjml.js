import { mjml2html } from "mjml"
import * as _ from "underscore"

import {
    TEXT,
    DIVIDER,
    IMAGE,
    TITLE,
    BUTTON,
    SPACER,
    SOCIAL_BUTTON,
    EMAIL_ONLINE,
    UNSUBSCRIBE
} from "../constants/TemplateContentConstants"

function getAttrsMjContainer(){
    return {
        width : configDelipressReact.container_width
    }
}


// Attributes padding for a component
function transformAttributesPadding(styles) {
    let _attrs = {
        "padding-top": 0,
        "padding-bottom": 0,
        "padding-left": 0,
        "padding-right": 0
    }

    if (_.isNumber(styles["padding-top"])) {
        _attrs["padding-top"] = styles["padding-top"] + "px"
    }

    if (_.isNumber(styles["padding-bottom"])) {
        _attrs["padding-bottom"] = styles["padding-bottom"] + "px"
    }

    if (_.isNumber(styles["padding-left"])) {
        _attrs["padding-left"] = styles["padding-left"] + "px"
    }

    if (_.isNumber(styles["padding-right"])) {
        _attrs["padding-right"] = styles["padding-right"] + "px"
    }

    return _attrs
}

function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}


function rgbaToHex(red, green, blue) {
    return rgbToHex(red, green, blue)
}

function transformAttributesSection(styles) {
    if (_.isUndefined(styles)) {
        return {}
    }

    let _attrs = transformAttributesPadding(_.clone(styles))

    if (
        _.isObject(styles["background"]) &&
        !_.isUndefined(styles["background"].rgb)
    ) {
        _attrs["background-color"] = rgbaToHex(
            styles["background"].rgb.r,
            styles["background"].rgb.g,
            styles["background"].rgb.b
        )
    } else if (_.isObject(styles["background"])) {
        _attrs["background-color"] = styles["background"].hex
    }

    if (!_.isEmpty(styles["background-url"])) {
        _attrs["background-url"] = styles["background-url"]
    }

    return _attrs
}

// Attributes mj-column
function transformAttributesColumn(styles) {
    if (_.isUndefined(styles)) {
        return {}
    }

    let _attrs = {
        "background-color": "transparent"
    }

    if (!_.isUndefined(styles["width"])) {
        _attrs["width"] = styles["width"] + "%"
    }

    if (!_.isUndefined(styles["vertical-align"])) {
        _attrs["vertical-align"] = styles["vertical-align"]
    }

    return _attrs
}

// Prepare mj-section
function transformSection(section) {
    return {
        tagName: "mj-section",
        attributes: transformAttributesSection(section.styles),
        children: _.map(section.columns, transformColumn)
    }
}

// Prepare mj-column with children
function transformColumn(column) {
    let _childrens = []
    _.each(column.items, (item, key) => {
        let _newItem = transformItem(item)

        if (_.isArray(_newItem)) {
            _.each(_newItem, _itm => {
                _childrens.push(_itm)
            })
        } else if (_.isObject(_newItem)) {
            _childrens.push(_newItem)
        }
    })

    return {
        tagName: "mj-column",
        attributes: transformAttributesColumn(column.styles),
        children: _childrens
    }
}

// Prepare component
function transformItem(item) {
    let _tagName = ""
    switch (item.type) {
        case TEXT:
        case TITLE:
        case UNSUBSCRIBE:
        case EMAIL_ONLINE:
            return transformItemText(item)
        case IMAGE:
            return transformItemImage(item)
        case SOCIAL_BUTTON:
            return transformItemSocial(item)
        case DIVIDER:
            return transformItemDivider(item)
        case BUTTON:
            return transformItemButton(item)
        case SPACER:
            return transformItemSpacer(item)
    }
}

// Prepare ButtonComponent
export function transformItemButton(item) {
    return {
        tagName: "mj-button",
        content: item.styles.value ? item.styles.value : "",
        attributes: _.extend(
            transformAttributesButton(item.styles),
            transformAttributesDefault(item.styles)
        )
    }
}

// Prepare ButtonComponent for render alone
export function transformItemButtonAlone(item) {
    let _itemButton = {}

    _itemButton = transformItemButton(item)
    _itemButton["attributes"]["padding-top"] = "0px"
    _itemButton["attributes"]["padding-left"] = "0px"
    _itemButton["attributes"]["padding-right"] = "0px"
    _itemButton["attributes"]["padding-bottom"] = "0px"

    return {
        tagName: "mjml",
        children: [
            {
                tagName: "mj-body",
                children: [
                    {
                        tagName: "mj-container",
                        attributes: getAttrsMjContainer(),
                        children: [
                            {
                                tagName: "mj-section",
                                attributes: {
                                    padding: "0px"
                                },
                                children: [
                                    {
                                        tagName: "mj-column",
                                        children: [_itemButton]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    }
}

// Attributes ButtonComponent
function transformAttributesButton(styles) {
    if (_.isUndefined(styles)) {
        return {}
    }
    let _attrs = {}

    if (!_.isUndefined(styles["border-radius"])) {
        _attrs["border-radius"] = styles["border-radius"] + "px"
    }

    if (!_.isUndefined(styles.height)) {
        _attrs["height"] = styles.height + "px"
    }

    if (!_.isUndefined(styles.width)) {
        _attrs["width"] = styles.width + "px"
    }

    if (!_.isUndefined(styles.href)) {
        _attrs["href"] = styles.href
    }

    if (!_.isUndefined(styles["font-size"])) {
        _attrs["font-size"] = styles["font-size"] + "px"
    }

    if (!_.isUndefined(styles["font-weight"])) {
        _attrs["font-weight"] = styles["font-weight"]
    }

    if (!_.isUndefined(styles["font-family"])) {
        _attrs["font-family"] =
            styles["font-family"] + ", Helvetica, Arial, sans-serif"
    }

    if (_.isObject(styles["background-color"])) {
        _attrs["background-color"] = styles["background-color"].hex
    }

    if (_.isObject(styles["color"])) {
        _attrs["color"] = styles["color"].hex
    }

    if (
        !_.isUndefined(styles["borderWidth"]) &&
        !_.isUndefined(styles["borderStyle"]) &&
        !_.isUndefined(styles["borderColor"])
    ) {
        _attrs["border"] =
            styles["borderWidth"] +
            "px " +
            styles["borderStyle"] +
            ` ${rgbaToHex(
                styles["borderColor"].rgb.r,
                styles["borderColor"].rgb.g,
                styles["borderColor"].rgb.b
            )}`
    }

    if (
        _.isNumber(styles["inner-padding-top-bottom"]) &&
        _.isNumber(styles["inner-padding-left-right"])
    ) {
        _attrs["inner-padding"] =
            styles["inner-padding-top-bottom"] +
            "px" +
            " " +
            styles["inner-padding-left-right"] +
            "px"
    }

    return _attrs
}

// Prepare TextComponent
export function transformItemText(item) {
    let _value = ""
    switch (item.type) {
        default:
            if (item.value) {
                _value = item.value
            }
            break
    }

    let _obj = {
        tagName: "mj-text",
        content: _value,
        attributes: _.extend(
            transformAttributesDefault(item.styles),
            transformAttributesText(item.styles),
            transformAttributesPreset(item, TEXT)
        )
    }

    if(item.keyRow == "email_online"){
        _obj.attributes["css-class"] = "email_online"
    }

    if(item.keyRow == "unsubscribe"){
        _obj.attributes["css-class"] = "unsubscribe"
    }

    return _obj
}

function transformAttributesPreset(item, type) {
    if (_.isUndefined(item.styles.presetChoice)) {
        return {}
    }

    const presetChoice = _.find(item.styles.presets, {
        type: item.styles.presetChoice
    })

    switch (type) {
        case TEXT:
        case TITLE:
        case UNSUBSCRIBE:
        case EMAIL_ONLINE:
            return transformAttributesText(presetChoice)
        default:
            return {}
    }
}

// Prepare TitleComponent for render alone
export function transformItemTitleAlone(item) {
    let _itemText = {}

    _itemText = transformItemText(item)

    return {
        tagName: "mjml",
        children: [
            {
                tagName: "mj-body",
                children: [
                    {
                        tagName: "mj-container",
                        attributes: getAttrsMjContainer(),
                        children: [
                            {
                                tagName: "mj-section",
                                attributes: {
                                    padding: "0px"
                                },
                                children: [
                                    {
                                        tagName: "mj-column",
                                        children: [_itemText]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    }
}

// Prepare TextComponent for render alone
export function transformItemTextAlone(item) {
    let _itemText = {}

    _itemText = transformItemText(item)

    _itemText["attributes"]["padding-top"] = "0px"
    _itemText["attributes"]["padding-left"] = "0px"
    _itemText["attributes"]["padding-right"] = "0px"
    _itemText["attributes"]["padding-bottom"] = "0px"

    if (!_.isUndefined(item.styles["font-size"])) {
        _itemText["attributes"]["font-size"] = item.styles["font-size"] + "px"
    }
    else{
        if (item.type == TEXT){
            _itemText["attributes"]["font-size"] = "15px"
        }
    }

    return {
        tagName: "mjml",
        children: [
            {
                tagName: "mj-body",
                children: [
                    {
                        tagName: "mj-container",
                        attributes: getAttrsMjContainer(),
                        children: [
                            {
                                tagName: "mj-section",
                                attributes: {
                                    padding: "0px"
                                },
                                children: [
                                    {
                                        tagName: "mj-column",
                                        children: [_itemText]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    }
}

function transformAttributesText(styles) {
    if (_.isUndefined(styles)) {
        return {}
    }
    let _attrs = {}

    if (!_.isUndefined(styles["font-size"])) {
        _attrs["font-size"] = styles["font-size"] + "px"
    }

    if (!_.isUndefined(styles["font-weight"])) {
        _attrs["font-weight"] = styles["font-weight"]
    }

    if (!_.isUndefined(styles["font-family"])) {
        _attrs["font-family"] = styles["font-family"]
    }

    if (!_.isUndefined(styles["line-height"])) {
        _attrs["line-height"] = styles["line-height"]
    }

    if (!_.isUndefined(styles["align"])) {
        _attrs["align"] = styles["align"]
    }

    if (!_.isUndefined(styles["color"])) {
        if (
            _.isObject(styles["color"]) &&
            !_.isUndefined(styles["color"].rgb)
        ) {
            _attrs["color"] = rgbaToHex(
                styles["color"].rgb.r,
                styles["color"].rgb.g,
                styles["color"].rgb.b
            )
        } else if (_.isObject(styles["color"])) {
            _attrs["color"] = styles["color"].hex
        }
    }

    return _attrs
}

// Prepare DividerComponent
export function transformItemDivider(item) {
    return {
        tagName: "mj-divider",
        attributes: _.extend(
            transformAttributesDefault(item.styles),
            transformAttributesDivider(item.styles)
        )
    }
}

// Prepare DividerComponent for render builder
export function transformItemDividerAlone(item) {
    let _prepareMjml = {
        tagName: "mjml",
        children: [
            {
                tagName: "mj-body",
                children: [
                    {
                        tagName: "mj-container",
                        attributes: getAttrsMjContainer(),
                        children: [
                            {
                                tagName: "mj-section",
                                attributes: {
                                    padding: "0px"
                                },
                                children: []
                            }
                        ]
                    }
                ]
            }
        ]
    }

    _prepareMjml.children[0].children[0].children[0].children.push(
        transformItemDivider(item)
    )
    return _prepareMjml
}

// Prepare SpacerComponent
export function transformItemSpacer(item) {
    return {
        tagName: "mj-spacer",
        attributes: _.extend(transformAttributesSpacer(item.styles))
    }
}

// Prepare SpacerComponent for render builder
export function transformItemSpacerAlone(item) {
    let _itemSpacer = {}

    _itemSpacer = transformItemSpacer(item)

    let _prepareMjml = {
        tagName: "mjml",
        children: [
            {
                tagName: "mj-body",
                children: [
                    {
                        tagName: "mj-container",
                        attributes: getAttrsMjContainer(),
                        children: [
                            {
                                tagName: "mj-section",
                                attributes: {
                                    padding: "0px"
                                },
                                children: [
                                    {
                                        tagName: "mj-column",
                                        children: [_itemSpacer]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    }

    return _prepareMjml
}

// Attributes SpacerComponent
function transformAttributesSpacer(styles) {
    let _attrs = {}

    if (_.isNumber(styles["height"])) {
        _attrs["height"] = styles["height"] + "px"
    }

    return _attrs
}

// Prepare ImageComponent
export function transformItemImage(item) {
    return {
        tagName: "mj-image",
        attributes: _.extend(
            transformAttributesDefault(item.styles),
            transformAttributesImage(item.styles)
        )
    }
}

// Prepare ImageComponent for render builder
export function transformItemImageAlone(item) {
    let _itemImage = {}

    _itemImage = transformItemImage(item)
    _itemImage["attributes"]["padding-top"] = "0px"
    _itemImage["attributes"]["padding-left"] = "0px"
    _itemImage["attributes"]["padding-right"] = "0px"
    _itemImage["attributes"]["padding-bottom"] = "0px"
    _itemImage["attributes"]["container-background-color"] = "transparent"

    const _prepareMjml = {
        tagName: "mjml",
        children: [
            {
                tagName: "mj-body",
                children: [
                    {
                        tagName: "mj-container",
                        attributes: getAttrsMjContainer(),
                        children: [
                            {
                                tagName: "mj-section",
                                attributes: {
                                    padding: "0px"
                                },
                                children: [
                                    {
                                        tagName: "mj-column",
                                        children: [_itemImage]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    }

    return _prepareMjml
}

// Prepare SocialComponent
export function transformItemSocial(item) {
    return {
        tagName: "mj-social",
        attributes: _.extend(
            transformAttributesDefault(item.styles),
            transformAttributesSocial(item.styles)
        )
    }
}

// Prepare SocialComponent for render builder
export function transformItemSocialAlone(item) {
    let _itemSocial = {}

    _itemSocial = transformItemSocial(item)

    _itemSocial["attributes"]["padding-top"] = "0px"
    _itemSocial["attributes"]["padding-left"] = "0px"
    _itemSocial["attributes"]["padding-right"] = "0px"
    _itemSocial["attributes"]["padding-bottom"] = "0px"
    _itemSocial["attributes"]["padding-bottom"] = "0px"
    _itemSocial["attributes"]["container-background-color"] = "transparent"

    const _prepareMjml = {
        tagName: "mjml",
        children: [
            {
                tagName: "mj-body",
                children: [
                    {
                        tagName: "mj-container",
                        attributes: getAttrsMjContainer(),
                        children: [
                            {
                                tagName: "mj-section",
                                attributes: {
                                    padding: "0px"
                                },
                                children: [
                                    {
                                        tagName: "mj-column",
                                        children: [_itemSocial]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    }

    return _prepareMjml
}

// Attributes default for a component
function transformAttributesDefault(styles) {
    if (_.isUndefined(styles)) {
        return {}
    }

    let _attrs = _.extend({}, transformAttributesPadding(_.clone(styles)))

    if (!_.isUndefined(styles["background"])) {
        if(styles["background"].hex != "transparent"){
            _attrs["container-background-color"] = rgbaToHex(
                styles["background"].rgb.r,
                styles["background"].rgb.g,
                styles["background"].rgb.b
            )
        }
    }

    if (!_.isUndefined(styles["align"])) {
        _attrs["align"] = styles["align"]
    }

    if (!_.isUndefined(styles["css-class"])) {
        _attrs["css-class"] = styles["css-class"]
    }

    return _attrs
}

// Attributes ImageComponent
function transformAttributesImage(styles) {
    if (_.isUndefined(styles)) {
        return {}
    }
    let _attrs = {}

    if (!_.isUndefined(styles.src)) {
        _attrs["src"] = styles.src
    }

    if (!_.isUndefined(styles.width)) {
        _attrs["width"] = styles.width + "px"
    }

    if (!_.isUndefined(styles["border-radius"])) {
        _attrs["border-radius"] = styles["border-radius"] + "px"
    }

    if (!_.isUndefined(styles.href)) {
        _attrs["href"] = styles.href
    }

    return _attrs
}

// Attributes DividerComponent
function transformAttributesDivider(styles) {
    if (_.isUndefined(styles)) {
        return {}
    }
    let _attrs = {}

    if (
        _.isObject(styles["border-color"]) &&
        !_.isUndefined(styles["border-color"].rgb)
    ) {
        _attrs["border-color"] = rgbaToHex(
            styles["border-color"].rgb.r,
            styles["border-color"].rgb.g,
            styles["border-color"].rgb.b
        )
    } else if (_.isObject(styles["border-color"])) {
        _attrs["border-color"] = styles["border-color"].hex
    }

    if (_.isNumber(styles["border-width"])) {
        _attrs["border-width"] = styles["border-width"] + "px"
    }

    if (!_.isUndefined(styles["border-style"])) {
        _attrs["border-style"] = styles["border-style"]
    }

    if (_.isNumber(styles["width"])) {
        _attrs["width"] = styles["width"] + "%"
    }

    return _attrs
}

// Attributes SocialComponent
function transformAttributesSocial(styles) {
    if (_.isUndefined(styles)) {
        return {}
    }

    let _attrs = {
        display: ""
    }

    _.each(styles, function(attr, key) {
        if (key.indexOf("toggle_") >= 0 && attr) {
            _attrs.display += key.replace("toggle_", "") + " "
        } else if (key.indexOf("content_") >= 0) {
            _attrs[key.replace("content_", "") + "-content"] = attr
        } else if (key.indexOf("url_") >= 0) {
            _attrs[key.replace("url_", "") + "-href"] = attr
        } else if (key.indexOf("color_") >= 0) {
            if (!_.isUndefined(attr.rgb)) {
                _attrs[key.replace("color_", "") + "-icon-color"] = rgbaToHex(
                    attr.rgb.r,
                    attr.rgb.g,
                    attr.rgb.b
                )
            } else {
                _attrs[key.replace("color_", "") + "-icon-color"] = attr.hex
            }
        }
    })

    if (!_.isUndefined(styles["font-family"])) {
        _attrs["font-family"] =
            styles["font-family"] + ", Helvetica, Arial, sans-serif"
    }

    if (!_.isUndefined(styles["align"])) {
        _attrs["align"] = styles["align"]
    }

    if (!_.isUndefined(styles["css-class"])) {
        _attrs["css-class"] = styles["css-class"]
    }

    if (!_.isUndefined(styles["icon-size"])) {
        _attrs["icon-size"] = styles["icon-size"] + "px"
    }

    if (!_.isUndefined(styles["font-size"])) {
        _attrs["font-size"] = styles["font-size"] + "px"
    }

    if (
        _.isObject(styles["textColor"]) &&
        !_.isUndefined(styles["textColor"].rgb)
    ) {
        _attrs["color"] = rgbaToHex(
            styles["textColor"].rgb.r,
            styles["textColor"].rgb.g,
            styles["textColor"].rgb.b
        )
    }

    if (_.isEmpty(_attrs.display)) {
        return {}
    }

    // Icon for special social media
    // if (_attrs.display.match(/youtube/m) != null) {
    //     _attrs["youtube-icon"] =
    //         DELIPRESS_PATH_PUBLIC_IMG + "/external/youtube.svg"
    // }
    // if (_attrs["youtube-content"] == "") {
    //     _attrs["text-mode"] = false
    // }

    return _attrs
}

function prepareTransformThemeAttr(attr, key) {
    switch (key) {
        case "color":
        case "background-color":
            if (!_.isUndefined(attr.rgb)) {
                return rgbaToHex(attr.rgb.r, attr.rgb.g, attr.rgb.b)
            }
        default:
            return attr
    }
}

function transformThemeAttr(attr, key) {
    switch (key) {
        case "mj-all":
            return {
                tagName: "mj-all",
                attributes: _.mapObject(attr, prepareTransformThemeAttr)
            }
        case "mj-text":
            return {
                tagName: "mj-text",
                attributes: _.mapObject(attr, prepareTransformThemeAttr)
            }
        case "mj-container":
            return {
                tagName: "mj-container",
                attributes: _.extend(getAttrsMjContainer(), _.mapObject(attr, prepareTransformThemeAttr) )
            }
    }
}

function transformStyleAttr(attr, key) {
    switch (key) {
        case "link-color":
            return `
                p a{ color: ${rgbaToHex(attr.rgb.r, attr.rgb.g, attr.rgb.b)}; }
            `
    }
}

// Prepare mj-attributes
function transformThemeAttributes(attrs) {
    return {
        tagName: "mj-attributes",
        children: _.map(attrs, transformThemeAttr)
    }
}

// Prepare mj-styles
function transformThemeStyles(attrs) {
    let _str = "* { color: currentColor; }\n"
    _str +=
        ".mjtext div * { margin-top: 1em;} .mjtext div *:first-child { margin-top: 0; } .mjtext div *:last-child { margin-bottom: 0;}  \n"
    _str += ".mjtext img { max-width:100%; }  \n"
    _str += ".email_online p { margin:0px !important; } \n"

    // Fix: apply color to header / footer (in mjml)
    _str += ".email_online a { color: inherit !important; } \n"
    _str += ".unsubscribe a { color: inherit !important; } \n"

    _.each(attrs, (attr, key) => {
        _str += transformStyleAttr(attr, key)
    })

    return {
        tagName: "mj-style",
        content: _str,
        attributes: _str
    }
}

export function transformToMjml(config, theme = null) {
    const _main = {
        tagName: "mjml",
        attributes: {},
        children: []
    }

    if (!_.isNull(theme)) {
        const _childrenAttributes = transformThemeAttributes(
            theme["mj-attributes"]
        )
        const _childrenStyles = transformThemeStyles(theme["mj-styles"])

        const _mjHead = {
            tagName: "mj-head",
            children: [_childrenAttributes, _childrenStyles]
        }

        _main.children.push(_mjHead)
    }

    const _childrenContainer = {
        tagName: "mj-container",
        attributes: getAttrsMjContainer(),
        children: []
    }

    // EmailOnline
    if (config.email_online_active) {
        let _emailOnline = _.map(config.email_online, transformSection)
        _.each(_emailOnline, mjSection => {
            _childrenContainer.children.push(mjSection)
        })
    }

    // Items
    const _items = _.map(config.items, transformSection)
    _.each(_items, mjSection => {
        _childrenContainer.children.push(mjSection)
    })

    // Unsubscribe
    const _unsubscribe = _.map(config.unsubscribe, transformSection)
    _.each(_unsubscribe, mjSection => {
        _childrenContainer.children.push(mjSection)
    })

    const _childrenBody = {
        tagName: "mj-body",
        attributes: {},
        children: [_childrenContainer]
    }

    _main.children.push(_childrenBody)

    return mjml2html(_main)
}

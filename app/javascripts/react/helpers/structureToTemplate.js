import * as _ from "underscore"
import {
    TEXT,
    DIVIDER,
    IMAGE,
    BUTTON,
    SOCIAL_BUTTON,
    TITLE,
    SPACER,
    WP_ARTICLE,
    WP_CUSTOM_POST,
    WP_ARCHIVE_CUSTOM_POST,
    WP_ARCHIVE_ARTICLE,
    WP_WOO_PRODUCT,
    WP_WOO_ARCHIVE_PRODUCT
} from "javascripts/react/constants/TemplateContentConstants"

export function createDefaultItemByType(type) {
    switch (type) {
        case TEXT:
            return createTextDefault()
        case SOCIAL_BUTTON:
            return createSocialButtonDefault()
        case DIVIDER:
            return createDividerDefault()
        case IMAGE:
            return createImageDefault()
        case WP_ARCHIVE_CUSTOM_POST:
            return createWPArchivePostDefault()
        case WP_WOO_ARCHIVE_PRODUCT:
            return createWooArchiveDefault()
        case WP_ARCHIVE_ARTICLE:
            return createWPArchiveArticleDefault()
        case WP_CUSTOM_POST:
            return createWPPostDefault()
        case WP_ARTICLE:
            return createWPArticleDefault()
        case WP_WOO_PRODUCT:
            return createWooProductDefault()
        case BUTTON:
            return createButtonDefault()
        case TITLE:
            return createTitleDefault()
        case SPACER:
            return createSpacerDefault()
        default:
            return {}
    }
}

export function createWPPostContent(item) {
    let _str = ""
    if (
        _.isUndefined(item.wp_post.postObject) ||
        _.isEmpty(item.wp_post.postObject)
    ) {
        return _str
    }

    if (_.isUndefined(item.wp_post.postObject.post_title)) {
        return _str
    }

    if (item.wp_post.options.content.full) {
        _str += item.wp_post.postObject.post_content
    } else if (item.wp_post.options.content.excerpt) {
        _str += item.wp_post.postObject.post_excerpt
    }

    return _str
}

const _stylesDefault = {
    background: {
        rgb: {
            r: 255,
            g: 255,
            b: 255,
            a: 1
        },
        hex: "transparent"
    },
    "padding-top": 10,
    "padding-bottom": 10,
    "padding-left": 10,
    "padding-right": 10
}

const _wpArchiveOptionsDefault = {
    title: true,
    image: true,
    type_content: {
        full: false,
        excerpt: true
    },
    post_type: {},
    post: {},
    choicePosts: []
}

function createWPPostDefault(ctx = {}) {
    let _prepareItem = {
        type: WP_CUSTOM_POST,
        wp_post: {
            post: "",
            post_type: "",
            postObject: "",
            options: {
                image: true,
                content: {
                    full: false,
                    excerpt: true,
                    title: true
                }
            }
        },
        styles: _.clone(_stylesDefault)
    }

    _prepareItem.wp_post = _.extend({}, _prepareItem.wp_post)

    return _.extend(ctx, _prepareItem)
}

function createWPArchivePostDefault(ctx = {}) {
    return _.extend(ctx, {
        type: WP_ARCHIVE_CUSTOM_POST,
        styles: _.extend(_.clone(_stylesDefault), {
            options: _.clone(_wpArchiveOptionsDefault)
        })
    })
}

function createWPArchiveArticleDefault(ctx = {}) {
    return _.extend(ctx, {
        type: WP_ARCHIVE_ARTICLE,
        styles: _.extend(
            _.clone(_stylesDefault),
            _.extend(
                {},
                {
                    options: _.extend({}, _.clone(_wpArchiveOptionsDefault), {
                        post_type: {
                            label: "Article",
                            value: "post"
                        },
                        choicePosts: []
                    })
                }
            )
        )
    })
}

function createWooArchiveDefault(ctx = {}) {
    return _.extend(ctx, {
        type: WP_WOO_ARCHIVE_PRODUCT,
        styles: _.extend(
            _.clone(_stylesDefault),
            _.extend(
                {},
                {
                    options: _.extend({}, _.clone(_wpArchiveOptionsDefault), {
                        post_type: {
                            label: "WooCommerce",
                            value: "product"
                        },
                        choicePosts: []
                    })
                }
            )
        )
    })
}

function createWPArticleDefault(ctx = {}) {
    let _prepareItem = {
        type: WP_ARTICLE,
        wp_post: {
            post: "",
            post_type: "post",
            postObject: "",
            options: {
                image: true,
                content: {
                    full: false,
                    excerpt: true,
                    title: true
                }
            }
        },
        styles: _.clone(_stylesDefault)
    }

    _prepareItem.wp_post = _.extend({}, _prepareItem.wp_post)

    return _.extend(ctx, _prepareItem)
}

function createWooProductDefault(ctx = {}) {
    let _prepareItem = {
        type: WP_WOO_PRODUCT,
        wp_post: {
            post: "",
            post_type: "product",
            postObject: "",
            options: {
                image: true,
                content: {
                    full: false,
                    excerpt: true,
                    title: true
                }
            }
        },
        styles: _.clone(_stylesDefault)
    }

    _prepareItem.wp_post = _.extend({}, _prepareItem.wp_post)

    return _.extend(ctx, _prepareItem)
}

export function createButtonDefault(ctx = {}, text = null) {
    let styleStorage = JSON.parse(
        localStorage.getItem("dp_default_component_" + BUTTON)
    )

    if (_.isNull(styleStorage)) {
        styleStorage = _.extend(_.clone(_stylesDefault), {
            value: text || "My button",
            color: {
                hex: "#ffffff",
                rgb: {
                    r: 255,
                    g: 255,
                    b: 255,
                    a: 1
                }
            },
            "background-color": {
                hex: "#414141",
                rgb: {
                    r: 65,
                    g: 65,
                    b: 65,
                    a: 1
                }
            },
            align: "center",
            "font-size": 13,
            "font-weight": "bold",
            "font-family": "Arial",
            border: "0px solid #00000",
            borderWidth: 0,
            borderColor: {
                hex: "#000000",
                rgb: {
                    r: 0,
                    g: 0,
                    b: 0,
                    a: 1
                }
            },
            borderStyle: "solid",
            "border-radius": 3,
            "padding-top": 15,
            "padding-bottom": 15,
            "padding-right": 20,
            "padding-left": 20,
            "inner-padding-top-bottom": 15,
            "inner-padding-left-right": 25
        })
    }

    return _.extend(ctx, {
        type: BUTTON,
        styles: _.clone(styleStorage)
    })
}

export function createSpacerDefault(ctx = {}, text = null) {
    return _.extend(ctx, {
        type: SPACER,
        styles: _.extend(
            {},
            {
                height: 30
            }
        )
    })
}

export function createTitleDefault(ctx = {}, text = null) {
    let styleStorage = JSON.parse(
        localStorage.getItem("dp_default_component_" + TITLE)
    )

    if (_.isNull(styleStorage)) {
        styleStorage = _.extend(_.clone(_stylesDefault), {
            presetChoice: "H1",
            presets: [
                {
                    type: "H1",
                    "font-size": 32,
                    "font-weight": "bold",
                    "font-family": "Arial",
                    color: {
                        hex: "#000000",
                        rgb: {
                            r: 0,
                            g: 0,
                            b: 0,
                            a: 1
                        }
                    },
                    "line-height": 1.1,
                    align: "left"
                },
                {
                    type: "H2",
                    "font-size": 24,
                    "font-weight": "bold",
                    "font-family": "Arial",
                    color: {
                        hex: "#000000",
                        rgb: {
                            r: 0,
                            g: 0,
                            b: 0,
                            a: 1
                        }
                    },
                    "line-height": 1.1,
                    align: "left"
                },
                {
                    type: "H3",
                    "font-size": 18,
                    "font-weight": "bold",
                    "font-family": "Arial",
                    color: {
                        hex: "#000000",
                        rgb: {
                            r: 0,
                            g: 0,
                            b: 0,
                            a: 1
                        }
                    },
                    "line-height": 1.1,
                    align: "left"
                }
            ]
        })
    }

    return _.extend(ctx, {
        type: TITLE,
        value: text || translationDelipressReact.Builder.default.title,
        styles: _.clone(styleStorage)
    })
}

export function createTextDefault(ctx = {}, text = null) {
    let styleStorage = JSON.parse(
        localStorage.getItem("dp_default_component_" + TEXT)
    )

    if (_.isNull(styleStorage)) {
        styleStorage = _.extend(_.clone(_stylesDefault), {
            "line-height": 1.5,
            "font-size": 15,
            "font-family": "Arial",
            "css-class": "mjtext"
        })
    }

    return _.extend(ctx, {
        type: TEXT,
        value:
            text || `<p>${translationDelipressReact.Builder.default.text}</p>`,
        styles: _.clone(styleStorage)
    })
}

export function createImageDefault(ctx = {}, src = "") {
    let styleStorage = JSON.parse(
        localStorage.getItem("dp_default_component_" + IMAGE)
    )

    if (_.isNull(styleStorage)) {
        styleStorage = _.extend(_.clone(_stylesDefault), {
            src: src,
            width: 100,
            height: "auto",
            href: "",
            align: "center",
            sizeSelect: "full",
            "border-radius": 0,
            "padding-top": 0,
            "padding-bottom": 0,
            "padding-left": 0,
            "padding-right": 0,
            valuePercent: 100
        })
    }

    return _.extend(ctx, {
        type: IMAGE,
        styles: _.clone(styleStorage)
    })
}

function createDividerDefault(ctx = {}) {
    return _.extend(ctx, _.clone(_stylesDefault), {
        type: DIVIDER,
        styles: createDividerAttributesDefault()
    })
}


function createSocialButtonDefault(ctx = {}) {
    return _.extend(ctx, _.clone(_stylesDefault), {
        type: SOCIAL_BUTTON,
        styles: createSocialButtonAttributesDefault()
    })
}

export function createDividerAttributesDefault() {
    let styleStorage = JSON.parse(
        localStorage.getItem("dp_default_component_" + DIVIDER)
    )

    if (_.isNull(styleStorage)) {
        return _.extend(_.clone(_stylesDefault), {
            "border-style": "solid",
            "border-width": 4,
            "border-color": {
                hex: "#000000",
                rgb: {
                    r: 0,
                    g: 0,
                    b: 0,
                    a: 1
                }
            },
            width: 100
        })
    }

    return styleStorage
}

export function createSocialButtonAttributesDefault() {
    let styleStorage = JSON.parse(
        localStorage.getItem("dp_default_component_" + SOCIAL_BUTTON)
    )

    if (!_.isNull(styleStorage)) {
        return styleStorage
    }

    return _.extend(_.clone(_stylesDefault), {
        toggle_facebook: true,
        toggle_twitter: true,
        "font-size": 13,
        "icon-size": 20,
        textColor: {
            hex: "#000000",
            rgb: {
                r: 0,
                g: 0,
                b: 0,
                a: 1
            }
        },
        "font-family": "Arial",
        content_facebook: "Share",
        content_twitter: "Tweet",
        content_google: "+1",
        content_youtube: "Subscribe",
        align: "center",
        "css-class": "mjsocial",
        monochromeActive: false,
        monochromeColor: {
            hex: "#C1C1C1",
            rgb: {
                r: 193,
                g: 193,
                b: 193,
                a: 1
            }
        }
    })
}

export function stringIndexToObjectPosition(string) {
    const arr = string.split("_")

    let _obj = {
        keyRow: null,
        keyColumn: null,
        _id: null
    }

    _.each(arr, function(value, key) {
        switch (key) {
            case 0:
                _obj.keyRow = Number(value)
                break
            case 1:
                _obj.keyColumn = Number(value)
                break
            case 2:
                _obj._id = Number(value)
                break
        }
    })

    return _obj
}

import * as _ from "underscore"
import React, { Component } from "react"
import { connect } from "react-redux"
import classNames from "classnames"
import { compose, bindActionCreators } from "redux"
import { mjml2html } from 'mjml';
import { transformItemTextAlone } from 'javascripts/react/helpers/transformToMjml'

import BaseTextContentComponent from "./BaseTextContentComponent"
import EditorActions from "../../services/actions/EditorActions"
import { shallowEqual } from "javascripts/react/helpers/shallowEqual"

import {
    TEXT,
    CHANGE_POSITION_CONTENT,
    CHANGE_POSITION_SECTION,
    ADD_TEMPLATE_CONTENT,
    ADD_TEMPLATE_CONTENT_EMPTY
} from "javascripts/react/constants/TemplateContentConstants"

import TinyMCE from "javascripts/backend/react-tinymce/index"

class TextComponent extends BaseTextContentComponent {
    componentWillMount() {
        this.randomId =
            "react-toolbar-tinymce-" + Math.floor(Math.random() * 10000 + 1)
    }

    writeCSS() {
        const { item } = this.props

        const idStyle = `delipress-component-text-${item.keyRow}${item.keyColumn}${item[
            "_id"
        ]}`
        let css = `\n#delipress-react-selector #${idStyle} .mce-content-body * {
            line-height:${item.styles["line-height"]};
            font-size:${item.styles["font-size"]}px;
            font-family:${item.styles[
                "font-family"
            ]} , Helvetica, Arial, sans-serif;
        }`
        css += `\n
        #delipress-react-selector #${idStyle} .mce-content-body > *{
            margin-top: 1em;
        }
        #delipress-react-selector #${idStyle} .mce-content-body > *:first-child{
            margin-top: 0;
        } #delipress-react-selector #${idStyle} .mce-content-body > *:last-child{
            margin-bottom: 0;
        }`

        // Fix text blocks styles
        css += `\n#delipress-react-selector tr.mjtext p {
            color: inherit !important;
            font-size: inherit !important;
            line-height: inherit !important;
        }`

        css += `\n#delipress-react-selector tr.mjtext p:not(:first-child),
            #delipress-react-selector tr.mjtext ul {
            margin-top: 1em !important;
            margin-bottom: 0 !important;
        }`

        css += `\n#delipress-react-selector tr.mjtext li {
            margin-top: 0.08em !important;
            margin-bottom: 0 !important;
            padding-left: 20px !important;
            list-style-position: inside !important;
            list-style-type: disc !important;
        }`

        return css
    }

    translateTinyMce() {
        if (
            _.isNull(this.props.sizeColumn) ||
            _.isNull(this.props.columnPosition)
        ) {
            return {}
        }
        const translateValues = {
            half: {
                1: "100"
            },
            two_third_left: {
                1: "48"
            },
            two_third_right: {
                1: "210"
            },
            one_quarter_left: {
                1: "30"
            },
            one_quarter_right: {
                1: "330"
            },
            third: {
                1: "100",
                2: "210"
            },
            half_middle: {
                1: "45",
                2: "330"
            },
            half_left: {
                1: "215",
                2: "330"
            },
            half_right: {
                1: "100",
                2: "100"
            },
            quarter: {
                1: "100",
                2: "215",
                3: "330"
            }
        }
        const selectedColumnValue =
            translateValues[this.props.sizeColumn][this.props.columnPosition]
        return { transform: `translateX(-${selectedColumnValue}%)` }
    }

    render() {
        const { item, activeItem } = this.props

        const element = transformItemTextAlone(item)
        const result = mjml2html(element)

        let tempDom = jQuery('<output>').append(jQuery.parseHTML(result.html));
        let content = jQuery('table', tempDom).wrap("<div></div>").parent();

        let _html = ""
        if (content.length > 0) {
            _html = content.html()
        }


        const styleItem = this.getStyles()
        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        return (
            <div
                id={
                    "delipress-component-text-" +
                    item.keyRow +
                    item.keyColumn +
                    item["_id"]
                }
                className={this.getClasses("delipress__content__text")}
                onClick={this._activeComponent}
                style={styleItem.component}
            >
                <div
                    style={this.translateTinyMce()}
                    className={this.randomId + " react-toolbar-tinymce"}
                >
                    <div className="react-toolbar-holder" />
                </div>

                <style>
                    {this.writeCSS()}
                </style>
                {
                    (index == activeItem) ? (
                        <TinyMCE
                            content={item.value}
                            config={{
                                inline: true,
                                menubar: false,
                                themes: "modern",
                                paste_as_text: true,
                                language_url: configDelipressReact.tinymce_lang_url,
                                fixed_toolbar_container:
                                    "." + this.randomId + " .react-toolbar-holder",
                                plugins: "paste textcolor colorpicker lists",
                                relative_urls: false,
                                convert_urls: false,
                                external_plugins: {
                                    link: configDelipressReact.tinymce_plugins.link
                                },
                                protect: [
                                    /\<\/?(if|endif)\>/g, // Protect <if> & </endif>
                                    /\<xsl\:[^>]+\>/g, // Protect <xsl:...>
                                    /<\?php.*?\?>/g // Protect php code
                                ],
                                toolbar: [
                                    "undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | blockquote bullist numlist | link | forecolor backcolor "
                                ],
                                setup: (ed) => {
                                    ed.on("change", () => {
                                        this._handleOnKeyUpContent( ed.getContent() );
                                    })
                                }
                            }}

                        />
                    ) : (
                        <div className="delipress__builder__main__preview__component__mjml delipress__builder__main__preview__component__mjml--text" dangerouslySetInnerHTML={{ "__html": _html }} />
                    )
                }
            </div>
        )
    }
}

function mapDispatchToProps(dispatch, context) {
    let actionsEditor = new EditorActions()

    return {
        actionsEditor: bindActionCreators(actionsEditor, dispatch)
    }
}

function mapStateToProps(state) {
    let _sizeColumn = null
    let _columnPosition = null

    if (!_.isNull(state.EditorReducer.activeItem)) {
        const splitItem = state.EditorReducer.activeItem.split("_")
        if(splitItem[0] == "unsubscribe" || splitItem[0] == "email"){
            _sizeColumn     = null;
            _columnPosition = 0;
        }
        else{
            _sizeColumn =
                state.TemplateReducer.config.items[splitItem[0]].styles
                    .sizeColumnChoice
            _columnPosition = splitItem[1]

            if (_.isUndefined(_sizeColumn)) {
                switch (state.TemplateReducer.config.items[splitItem[0]].columns
                    .length) {
                    case 1:
                    default:
                        _sizeColumn = null
                        break
                    case 2:
                        _sizeColumn = "half"
                        break
                    case 3:
                        _sizeColumn = "third"
                        break
                    case 4:
                        _sizeColumn = "quarter"
                        break
                }
            }
        }
    }

    return {
        activeItem: state.EditorReducer.activeItem,
        sizeColumn: _sizeColumn,
        columnPosition: _columnPosition
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(TextComponent)

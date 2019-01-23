import * as _ from "underscore"
import React, { Component } from "react"
import PropTypes from "prop-types"
import { connect } from "react-redux"
import classNames from "classnames"
import { compose, bindActionCreators } from "redux"
import { mjml2html } from "mjml"
import { transformItemTextAlone } from "javascripts/react/helpers/transformToMjml"

import BaseTextContentComponent from "javascripts/react/components/contents/BaseTextContentComponent"
import EditorActions from "javascripts/react/services/actions/EditorActions"
import TinyMCE from "javascripts/backend/react-tinymce/index"

import { shallowEqual } from "javascripts/react/helpers/shallowEqual"

class BaseHeaderFooter extends BaseTextContentComponent {
    render() {
        const { textTheme, item } = this.props

        if (_.isEmpty(item.value)) {
            item.value =
                "<p><a href='[delipress_view_campaign_online]'>" +
                translationDelipressReact.view_online +
                "</a></p>"
        }

        const element = transformItemTextAlone(item)
        const result = mjml2html(element)

        let tempDom = jQuery("<output>").append(jQuery.parseHTML(result.html))
        let content = jQuery("table", tempDom).wrap("<div></div>").parent()

        let _html = ""
        if (content.length > 0) {
            _html = content.html()
        }

        let styleItem = this.getStyles()
        const addClass =
            "delipress__content__header_footer delipress-component-" +
            item.keyRow

        return (
            <div
                id={
                    "delipress-component-" +
                    item.keyRow +
                    item.keyColumn +
                    item["_id"]
                }
                className={this.getClasses(addClass)}
                onClick={this._activeComponent}
                style={styleItem.component}
            >
                {this.props.children}
                <div
                    id={
                            "delipress-component-" +
                            item.keyRow +
                            item.keyColumn +
                            item["_id"]
                        }
                        className={this.getClasses(addClass)}
                        dangerouslySetInnerHTML={{ __html: _html }}
                    />
            </div>
        )
    }
}

BaseHeaderFooter.propTypes = {
    memetaReplace: PropTypes.string,
    fix: PropTypes.bool
}

export default BaseHeaderFooter

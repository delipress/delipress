import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from 'prop-types'
import classNames from 'classnames'
import Select from 'react-select';

import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'
import PostTypeActions from 'javascripts/react/services/actions/PostTypeActions'
import ColorSelector from 'javascripts/react/components/ColorSelector'
import BaseWPSettings from 'javascripts/react/components/settings/base/BaseWPSettings'


class WPArticleSettings extends BaseWPSettings  {

    componentWillMount(){

        super.componentWillMount()

        const {
            actionPostType,
            _onChangeChoicePostType,
            item
        } = this.props


        _onChangeChoicePostType(
            {
                "post_type" : item.wp_post.post_type
            }
        )

        this._saveEditor()
    }

    render() {
        const { item } = this.props

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.wp_post.title}
                </span>
                {this.renderSettingsImage()}
                {this.renderSettingsContent()}
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.wp_post.settings_choose_article.title}
                </span>
                {this.renderChoicePost()}
                {this.renderImportPost()}
            </div>
        )
    }
}



export default WPArticleSettings

import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import PropTypes from 'prop-types'
import classNames from 'classnames'
import Select from 'react-select';
import { connect } from "react-redux"

import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'
import PostTypeActions from 'javascripts/react/services/actions/PostTypeActions'
import ColorSelector from 'javascripts/react/components/ColorSelector'
import BaseWPArchiveSettings from 'javascripts/react/components/settings/base/BaseWPArchiveSettings'
import PictureSettings from 'javascripts/react/components/settings/wp/PictureSettings'
import Posts from 'javascripts/react/components/settings/wp/Posts'
import ChoicePostsImport from 'javascripts/react/components/settings/wp/ChoicePostsImport'
import TypeContent from 'javascripts/react/components/settings/wp/TypeContent'
import SettingsItem from "javascripts/react/components/settings/SettingsItem"


class WooArchiveProductSettings extends BaseWPArchiveSettings  {

    render() {

        if(_.isNull(this.props.item) || _.isUndefined(this.props.item)){
            return false
        }
        
        const {
            image,
            title,
            post,
            choicePosts,
            post_type,
            type_content
        } = this.styles.options

        let _post = post
        if(_.isArray(_post)){
            _post = {}
        }

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.wp_post.title}
                </span>
                <PictureSettings
                    toggleOption={(value) => {
                        this.saveOptionValue("image", value)
                    }}
                    valueOption={image}
                />
                <TypeContent 
                    toggleOption={(type_content) => {

                        this.styles.options.type_content.full = type_content.full
                        this.styles.options.type_content.excerpt = type_content.excerpt

                        this.saveEditor()
                    }}
                    valueOption={type_content}
                />
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.wp_archive_post.settings_choose_article.title}
                </span>
                <Posts
                    changeChoicePost={(obj) => {
                        this.saveOptionValue("post", obj)
                    }}
                    valueOption={_post}
                    post_type={post_type}
                />
                
                {
                    !_.isEmpty(post) ?
                    <SettingsItem>
                        <a
                            className="delipress__button delipress__button--soft"
                            onClick={() => {
                                this.styles.options.choicePosts.push(post)
                                this.styles.options.post = {}
                                this.props.saveEditor(this.styles)
                            }}
                        >
                            {translationDelipressReact.Builder.component_settings.wp_archive_post.add_post}
                        </a>
                    </SettingsItem> :
                    false
                }
                <ChoicePostsImport
                    choicePosts={choicePosts}
                    config={this.styles.options}
                    importPostsWP={this.props.importPostsWP}
                    removeImportPost={this.removeImportPost}
                />
            </div>
        )
    }
}

function mapStateToProps(state){
    if(_.isNull(state.EditorReducer.activeItem)){
        return {
            item : null
        }
    }

    const arr = state.EditorReducer.activeItem.split("_")

    return {
        item : state.TemplateReducer.config.items[arr[0]].columns[arr[1]].items[arr[2]]
    }
}

export default connect(mapStateToProps)(WooArchiveProductSettings)

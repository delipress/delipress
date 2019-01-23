import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import classNames from "classnames"
import Select from "react-select"

import { shallowEqual } from "javascripts/react/helpers/shallowEqual"
import PostTypeActions from "javascripts/react/services/actions/PostTypeActions"
import ColorSelector from "javascripts/react/components/ColorSelector"
import BaseSettingsComponent from "javascripts/react/components/settings/base/BaseSettingsComponent"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"

class BaseWPSettings extends BaseSettingsComponent {

    constructor(props){
        super(props)

        this._toggleTitle             = this._toggleTitle.bind(this)
        this._toggleImage             = this._toggleImage.bind(this)
        this._onChangeSettingsContent = this._onChangeSettingsContent.bind(this)
        this._onChangeChoicePostType  = this._onChangeChoicePostType.bind(this)
        this._onChangeChoicePost      = this._onChangeChoicePost.bind(this)
    }

    componentWillMount() {
        const { item } = this.props

        if (!_.isUndefined(item.wp_post)) {
            this.setState({
                wp_post: _.extend({}, item.wp_post),
                isLoading: false,
                importLoad: false,
            })
        }
    }

    componentWillUpdate(nextProps, nextState) {
        if (!shallowEqual(this.props.posts, nextProps.posts)) {
            this.setState({
                isLoading: false
            })
        }
    }

    _toggleTitle(value) {
        this.state.wp_post.options.content.title = event.target.checked

        this.setState({
            wp_post: this.state.wp_post
        })

        this._saveEditor()
    }

    renderSettingsTitle() {
        const { item } = this.props

        return (
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.wp_post
                        .settings_title.title
                }
            >
                <input
                    type="checkbox"
                    id="settings_title"
                    className="delipress__switch__input"
                    checked={this.state.wp_post.options.content.title}
                    onChange={this._toggleTitle}
                />
                <label htmlFor="settings_title" className="delipress__switch">
                    <div className="delipress__switch__slider" />
                    <div className="delipress__switch__on">I</div>
                    <div className="delipress__switch__off">0</div>
                </label>
            </SettingsItem>
        )
    }

    _toggleImage(event) {
        this.state.wp_post.options.image = event.target.checked

        this.setState({
            wp_post: this.state.wp_post
        })

        this._saveEditor()
    }

    renderSettingsImage() {
        const { item } = this.props

        return (
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.wp_post
                        .settings_image.title
                }
            >
                <input
                    type="checkbox"
                    id="settings_image"
                    className="delipress__switch__input"
                    checked={this.state.wp_post.options.image}
                    onChange={this._toggleImage}
                />
                <label htmlFor="settings_image" className="delipress__switch">
                    <div className="delipress__switch__slider" />
                    <div className="delipress__switch__on">I</div>
                    <div className="delipress__switch__off">0</div>
                </label>
            </SettingsItem>
        )
    }

    _onChangeSettingsContent(event) {
        const value = event.target.value

        this.state.wp_post.options.content[value] = true

        if (value === "full") {
            this.state.wp_post.options.content.excerpt = false
        } else {
            this.state.wp_post.options.content.full = false
        }

        this.setState({
            wp_post: this.state.wp_post
        })

        this._saveEditor()
    }

    renderSettingsContent() {
        const { item } = this.props

        return (
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.wp_post
                        .settings_content.title
                }
                classModifier="delipress__builder__side__setting__input--align"
            >
                <div className="delipress__buttonsgroup">
                    <div className="delipress__buttonsgroup__cell">
                        <input
                            type="radio"
                            name="settings__content"
                            id="settings__content_full"
                            value="full"
                            checked={this.state.wp_post.options.content.full}
                            onChange={this._onChangeSettingsContent}
                        />
                        <label htmlFor="settings__content_full">
                            {
                                translationDelipressReact.Builder
                                    .component_settings.wp_post.settings_content
                                    .full
                            }
                        </label>
                    </div>
                    <div className="delipress__buttonsgroup__cell">
                        <input
                            type="radio"
                            name="settings__content"
                            id="settings__content_excerpt"
                            value="excerpt"
                            onChange={this._onChangeSettingsContent}
                            checked={this.state.wp_post.options.content.excerpt}
                        />
                        <label htmlFor="settings__content_excerpt">
                            {
                                translationDelipressReact.Builder
                                    .component_settings.wp_post.settings_content
                                    .excerpt
                            }
                        </label>
                    </div>
                </div>
            </SettingsItem>
        )
    }

    _onChangeChoicePostType(obj) {
        const { actionPostType, _onChangeChoicePostType } = this.props

        this.state.wp_post.post_type = obj
        this.state.wp_post.post = ""

        this.setState({
            wp_post: this.state.wp_post,
            isLoading: true
        })

        _onChangeChoicePostType({
            post_type: obj.value
        })

        this._saveEditor()
    }

    _onChangeChoicePost(obj) {
        this.state.wp_post.post = obj

        this.setState({
            wp_post: this.state.wp_post
        })

        this._saveEditor()
    }

    renderChoicePostTypes() {
        const { postTypes, posts } = this.props

        const options = _.map(postTypes, postType => {

            return {
                value: postType.name,
                label: postType.label
            }
        })

        return (
            <SettingsItem>
                <Select
                    options={options}
                    value={this.state.wp_post.post_type}
                    onChange={this._onChangeChoicePostType}
                    placeholder={translationDelipressReact.Builder.component_settings.wp_archive_post.settings_choose_type.placeholder}
                />
            </SettingsItem>
        )
    }

    renderImportPost() {
        return (
            <SettingsItem>
                <a
                    className="delipress__button delipress__button--main"
                    onClick={() => {
                        if(this.state.importLoad){
                            return;
                        }
                        this.setState({importLoad: true})
                        this.props.importPost()
                    }}
                >
                    {!this.state.importLoad &&
                        translationDelipressReact.Builder.component_settings
                            .wp_post.button_import
                    }
                    {
                        this.state.importLoad &&
                        <span className="dashicons dashicons-update dashicons--roll"></span>
                    }
                </a>
            </SettingsItem>
        )
    }

    _onInputChange(value) {
        const { actionPostType, _onChangeChoicePostType } = this.props

        if (!_.isEmpty(this.state.wp_post.post_type)) {
            clearTimeout(this.searchPostType)

            this.searchPostType = setTimeout(() => {
                this.setState({
                    isLoading: true
                })
                _onChangeChoicePostType({
                    s: value,
                    post_type: this.state.wp_post.post_type
                })
            }, 500)
        }
    }

    renderChoicePost() {
        const { postTypes, posts } = this.props

        const optionPosts = _.map(posts, post => {
            return {
                value: post.ID,
                label: _.isEmpty(post.post_title)
                    ? `ID : ${post.ID}`
                    : jQuery('<div/>').html(post.post_title).text()
            }
        })

        return (
            <SettingsItem>
                <Select
                    options={optionPosts}
                    value={this.state.wp_post.post}
                    disabled={_.isEmpty(this.state.wp_post.post_type)}
                    placeholder={translationDelipressReact.Builder.component_settings.wp_post.placeholder}
                    onInputChange={this._onInputChange.bind(this)}
                    onChange={this._onChangeChoicePost}
                    isLoading={this.state.isLoading}
                />
            </SettingsItem>
        )
    }
}

BaseWPSettings.propType = {
    postTypes: PropTypes.array.isRequired,
    posts: PropTypes.array.isRequired,
    _onChangeChoicePostType: PropTypes.func.isRequired
}

export default BaseWPSettings

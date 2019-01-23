import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import { connect } from "react-redux"
import { compose, bindActionCreators } from "redux"

import SocialSettings from "./SocialSettings"
import DividerSettings from "./DividerSettings"
import SpacerSettings from "./SpacerSettings"
import WPPostSettings from "./WPPostSettings"
import WPArticleSettings from "./WPArticleSettings"
import EmailOnlineSettings from "./EmailOnlineSettings"
import UnsubscribeSettings from "./UnsubscribeSettings"
import ButtonSettings from "./ButtonSettings"
import TitleSettings from "./TitleSettings"
import TextSettings from "./TextSettings"
import ImageSettings from "./image/ImageSettings"
import WPArchivePostSettings from "./wp/WPArchivePostSettings"
import WPArchiveArticleSettings from "./wp/WPArchiveArticleSettings"

import { SETTINGS_LIST_CONTENTS } from "javascripts/react/constants/EditorConstants"

import {
    TEXT,
    DIVIDER,
    IMAGE,
    BUTTON,
    SOCIAL_BUTTON,
    SPACER,
    WP_CUSTOM_POST,
    WP_ARTICLE,
    WP_ARCHIVE_CUSTOM_POST,
    WP_ARCHIVE_ARTICLE,
    TITLE,
    EMAIL_ONLINE,
    UNSUBSCRIBE,
    WP_WOO_ARCHIVE_PRODUCT,
    WP_WOO_PRODUCT
} from "javascripts/react/constants/TemplateContentConstants"

import EditorActions from "javascripts/react/services/actions/EditorActions"
import PostTypeActions from "javascripts/react/services/actions/PostTypeActions"

class EditorComponent extends Component {
    constructor(props) {
        super(props)

        this.updateItem              = this.updateItem.bind(this)
        this.saveEditor              = this.saveEditor.bind(this)
        this._importPost             = this._importPost.bind(this)
        this._importPostsWP          = this._importPostsWP.bind(this)
        this.updateAllStyles         = this.updateAllStyles.bind(this)
        this._onChangeChoicePostType = this._onChangeChoicePostType.bind(this)
    }

    componentWillMount() {
        const { item } = this.props
        if (
            item.type === WP_CUSTOM_POST &&
            !_.isUndefined(item.wp_post.post_type)
        ) {
            this._onChangeChoicePostType({
                post_type: item.wp_post.post_type.value
            })
        }
    }

    componentWillUpdate(nextProps, nextState) {
        const indexNext     = `${nextProps.item.keyRow}_${nextProps.item.keyColumn}_${nextProps.item._id}`
        const index = `${this.props.item.keyRow}_${this.props.item.keyColumn}_${this.props.item._id}`
        if(index !== indexNext){
            jQuery(".delipress__builder__side__panel__tabcontent").addClass(
                "delipress--is-animating"
            )
        }
    }

    componentDidUpdate(prevProps, prevState) {
        const indexPrev  = `${prevProps.item.keyRow}_${prevProps.item.keyColumn}_${prevProps.item._id}`
        const index = `${this.props.item.keyRow}_${this.props.item.keyColumn}_${this.props.item._id}`
        if(indexPrev !== index){
            setTimeout(function() {
                jQuery(".delipress__builder__side__panel__tabcontent").removeClass(
                    "delipress--is-animating"
                )
            }, 275)
        }
    }

    updateItem(item) {
        const { actionsEditor, changeItemSuccess } = this.props

        let deferred = null
        switch (item.type) {
            case EMAIL_ONLINE:
            case UNSUBSCRIBE:
                deferred = actionsEditor.changeStyleComponentFix(item)
                break
            default:
                deferred = actionsEditor.changeItem(item)
                break
        }

        if (!_.isUndefined(changeItemSuccess)) {
            deferred.then(changeItemSuccess)
        }
    }

    saveEditor(newObj) {
        let {
            actionsEditor,
            actionPostType,
            item,
            changeItemSuccess
        } = this.props

        if (
            _.find([WP_CUSTOM_POST, WP_ARTICLE, WP_WOO_PRODUCT], value => {
                return value == item.type
            })
        ) {
            item = _.extend(item, {
                wp_post: newObj.wp_post
            })
        } else {
            item = _.extend(item, {
                styles: newObj
            })
        }

        this.updateItem(item)
    }

    updateAllStyles(newObj, presetChoice = null) {
        const { actionsEditor, changeItemSuccess } = this.props

        let { item } = this.props

        if (_.isNull(presetChoice)) {
            item = _.extend(item, {
                styles: newObj
            })
        } else {
            const index = _.findIndex(item.styles.presets, {
                type: presetChoice
            })
            if (index >= 0) {
                item.styles.presets[index] = _.extend(
                    item.styles.presets[index],
                    newObj
                )
            }
        }

        actionsEditor.updateAllStyles(item).then(changeItemSuccess)
    }

    _importPost() {
        const {
            actionPostType,
            actionsEditor,
            changeItemSuccess,
            item
        } = this.props

        if (!_.isUndefined(item.wp_post.post.value)) {
            actionPostType.getPostToWPPost(
                {
                    post_id: item.wp_post.post.value,
                    with_image: item.wp_post.options.image,
                    type_content: item.wp_post.options.content
                },
                item,
                () => {
                    if (!_.isUndefined(changeItemSuccess)) {
                        actionsEditor
                            .changeSettingsComponent(SETTINGS_LIST_CONTENTS)
                            .then(() => {
                                actionsEditor.activeItem(null).then(() => {
                                    changeItemSuccess()
                                })
                            })
                    }
                }
            )
        }
    }

    _importPostsWP(posts, config) {
        const {
            actionPostType,
            actionsEditor,
            changeItemSuccess,
            item
        } = this.props
        
        if (!_.isEmpty(posts)) {
            actionPostType.importPostsWP(
                {
                    posts: posts,
                    config: config
                },
                item,
                () => {
                    if (!_.isUndefined(changeItemSuccess)) {
                        actionsEditor
                            .changeSettingsComponent(SETTINGS_LIST_CONTENTS)
                            .then(() => {
                                actionsEditor.activeItem(null).then(() => {
                                    changeItemSuccess()
                                })
                            })
                    }
                }
            )
        }
    }

    _onChangeChoicePostType(params) {
        const { actionPostType } = this.props

        actionPostType.getPosts(params)
    }

    render() {
        const { item, postTypes, posts } = this.props

        let _editorCustom = false

        switch (item.type) {
            case DIVIDER:
                _editorCustom = (
                    <DividerSettings 
                        saveEditor={this.saveEditor} 
                        item={item}
                        updateAllStyles={this.updateAllStyles}
                    />
                )
                break
            case SOCIAL_BUTTON:
                _editorCustom = (
                    <SocialSettings 
                        saveEditor={this.saveEditor} 
                        updateAllStyles={this.updateAllStyles}
                    />
                )
                break
            case IMAGE:
                _editorCustom = (
                    <ImageSettings 
                        saveEditor={this.saveEditor} 
                        updateAllStyles={this.updateAllStyles}
                    />
                )
                break
            case TEXT:
                _editorCustom = (
                    <TextSettings 
                        saveEditor={this.saveEditor} 
                        updateAllStyles={this.updateAllStyles}
                    />
                )
                break
            case BUTTON:
                _editorCustom = (
                    <ButtonSettings
                        saveEditor={this.saveEditor}
                        updateAllStyles={this.updateAllStyles}
                    />
                )
                break
            case TITLE:
                _editorCustom = (
                    <TitleSettings
                        saveEditor={this.saveEditor}
                        updateAllStyles={this.updateAllStyles}
                    />
                )
                break
            case SPACER:
                _editorCustom = (
                    <SpacerSettings
                        saveEditor={this.saveEditor}
                        updateAllStyles={this.updateAllStyles}
                    />
                )
                break
            case WP_CUSTOM_POST:
                _editorCustom = (
                    <WPPostSettings
                        item={item}
                        posts={posts}
                        postTypes={postTypes}
                        _onChangeChoicePostType={this._onChangeChoicePostType}
                        saveEditor={this.saveEditor}
                        importPost={this._importPost}
                    />
                )
                break
            case WP_ARCHIVE_CUSTOM_POST:
                _editorCustom = (
                    <WPArchivePostSettings
                        item={item}
                        postTypes={postTypes}
                        onChangeChoicePostType={this._onChangeChoicePostType}
                        saveEditor={this.saveEditor}
                        importPostsWP={this._importPostsWP}
                    />
                )
                break
            case WP_ARCHIVE_ARTICLE:
            case WP_WOO_ARCHIVE_PRODUCT:
                _editorCustom = (
                    <WPArchiveArticleSettings
                        item={item}
                        onChangeChoicePostType={this._onChangeChoicePostType}
                        saveEditor={this.saveEditor}
                        importPostsWP={this._importPostsWP}
                    />
                )
                break
            case WP_ARTICLE:
            case WP_WOO_PRODUCT:
                _editorCustom = (
                    <WPArticleSettings
                        item={item}
                        posts={posts}
                        postTypes={postTypes}
                        _onChangeChoicePostType={this._onChangeChoicePostType}
                        saveEditor={this.saveEditor}
                        importPost={this._importPost}
                    />
                )
                break
            case EMAIL_ONLINE:
                _editorCustom = (
                    <EmailOnlineSettings
                        item={item}
                        saveEditor={this.saveEditor}
                    />
                )
                break
            case UNSUBSCRIBE:
                _editorCustom = (
                    <UnsubscribeSettings
                        item={item}
                        saveEditor={this.saveEditor}
                    />
                )
                break
        }

        return (
            <div className="delipress__builder__side__panel__tabcontent">
                {_editorCustom}
            </div>
        )
    }
}

EditorComponent.propType = {
    item: PropTypes.object.isRequired,
    changeItemSuccess: PropTypes.func
}

function mapDispatchToProps(dispatch, context) {
    const actionsEditor = new EditorActions()
    const actionPostType = new PostTypeActions()

    return {
        actionsEditor: bindActionCreators(actionsEditor, dispatch),
        actionPostType: bindActionCreators(actionPostType, dispatch)
    }
}

function mapStateToProps(state) {
    return {
        postTypes: state.PostTypeReducer.postTypes,
        posts: state.PostTypeReducer.posts
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(EditorComponent)

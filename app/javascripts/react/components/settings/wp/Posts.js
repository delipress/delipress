import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import { bindActionCreators } from "redux"
import { connect } from "react-redux"
import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'

import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import PostTypeActions from 'javascripts/react/services/actions/PostTypeActions'
import Select from "react-select"

class Posts extends Component {

    constructor(props){
        super(props)

        this.state = {
            isLoading : false
        }
        this._onChangeChoicePost = this._onChangeChoicePost.bind(this)
        this._onInputChange      = this._onInputChange.bind(this)
    }

    componentWillMount(){
        const {
            actionPostType,
            post_type
        } = this.props

        actionPostType.getPosts({
            post_type: post_type.value
        })
    }

    componentWillUpdate(nextProps, nextState) {
        if (!shallowEqual(this.props.posts, nextProps.posts)) {
            this.setState({
                isLoading: false
            })
        }
    }

    _onChangeChoicePost(obj) {
        let _sendObj = {}
        if(!_.isNull(obj)){
            _sendObj = obj
        }
        this.props.changeChoicePost(_sendObj)
    }

     _onInputChange(value) {
        const {
            actionPostType,
            post_type
        } = this.props

        if (!_.isEmpty(post_type)) {

            clearTimeout(this.searchPostType)

            this.searchPostType = setTimeout(() => {
                this.setState({
                    isLoading: true
                })

                actionPostType.getPosts({
                    s: value,
                    post_type: post_type.value
                })
            }, 500)
        }
    }

    render() {
        const {
            posts,
            postTypes,
            valueOption
        } = this.props

        const optionPosts = _.map(posts, post => {
            return {
                value: post.ID,
                label: _.isEmpty(post.post_title)
                    ? `ID : ${post.ID}`
                    : jQuery('<div/>').html(post.post_title).text()
            }
        })

        return (
            <SettingsItem label={translationDelipressReact.Builder.component_settings.wp_archive_post.settings_choose_article.posts}>
                <Select
                    options={optionPosts}
                    value={valueOption}
                    disabled={_.isEmpty(postTypes)}
                    onInputChange={this._onInputChange}
                    onChange={this._onChangeChoicePost}
                    isLoading={this.state.isLoading}
                />
            </SettingsItem>
        )
    }

}

Posts.propTypes = {
    changeChoicePost : PropTypes.func.isRequired,
    valueOption: PropTypes.object.isRequired,
    post_type: PropTypes.object.isRequired
}

function mapStateToProps(state){
    return {
        posts : state.PostTypeReducer.posts,
        postTypes : state.PostTypeReducer.postTypes
    }
}

function mapDispatchToProps(dispatch, context){
    const actionPostType = new PostTypeActions()

    return {
        "actionPostType" : bindActionCreators(actionPostType, dispatch)
    }
}


export default connect(mapStateToProps,mapDispatchToProps)(Posts)
